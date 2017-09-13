<?php
/**
 * Blackbird Agency
 *
 * @category    Blackbird
 * Date: 03/07/17
 * Time: 15:30
 * @copyright   Copyright (c) 2017 Blackbird Agency. (http://black.bird.eu)
 * @author Jérémie Poisson (hello@bird.eu)
 */


abstract class Blackbird_Monetico_Controller_Action extends Mage_Core_Controller_Front_Action
{
    protected $_moneticoResponse = null;
    protected $_realOrderIds;
    protected $_quote;

    /**
     * Get current Monetico Method Instance
     *
     * @return Blackbird_Monetico_Model_Method_Onetime|Blackbird_Monetico_Model_Method_Multitime
     */
    abstract public function getMethodInstance();

    /**
     * Get quote model
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        if (!$this->_quote) {
            $session = Mage::getSingleton('checkout/session');
            $this->_quote = Mage::getModel('sales/quote')->load($session->getMoneticoPaymentQuoteId());

            if (!$this->_quote->getId()) {
                $realOrderIds = $this->getRealOrderIds();
                if (count($realOrderIds)) {
                    $order = Mage::getModel('sales/order')->loadByIncrementId($realOrderIds[0]);
                    $this->_quote = Mage::getModel('sales/quote')->load($order->getQuoteId());
                }
            }
        }
        return $this->_quote;
    }

    /**
     * Get real order ids
     *
     * @return array
     */
    public function getRealOrderIds()
    {
        if (!$this->_realOrderIds) {
            if ($this->_moneticoResponse) {
                $this->_realOrderIds = explode(',', $this->_moneticoResponse['reference']);
            } elseif ($realOrderIds = Mage::getSingleton('checkout/session')->getMoneticoRealOrderIds()) {
                $this->_realOrderIds = explode(',', $realOrderIds);
            } else {
                return array();
            }
        }
        return $this->_realOrderIds;
    }

    /**
     * seting response after returning from monetico
     *
     * @param array $response
     * @return object $this
     */
    protected function setMoneticoResponse($response)
    {
        if (count($response)) {
            $this->_moneticoResponse = $response;
        }
        return $this;
    }

    /**
     * When a customer chooses Monetico on Checkout/Payment page
     *
     */
    public function redirectAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setMoneticoPaymentQuoteId($session->getLastQuoteId());

        if ($this->getQuote()->getIsMultiShipping()) {
            $realOrderIds = explode(',', $session->getRealOrderIds());
            $session->setMoneticoRealOrderIds($session->getRealOrderIds());
        } else {
            $realOrderIds = array($session->getLastRealOrderId());
            $session->setMoneticoRealOrderIds($session->getLastRealOrderId());
        }

        foreach ($realOrderIds as $realOrderId) {
            $order = Mage::getModel('sales/order');
            $order->loadByIncrementId($realOrderId);

            if (!$order->getId()) {
                // Debug data when order can not be loaded
                $this->getMethodInstance()->debugData($realOrderIds);
                $this->getMethodInstance()->debugData($session->getData());

                $this->norouteAction();
                return;
            }

            $order->addStatusHistoryComment(Mage::helper('blackbird_monetico')->__('Customer was redirected to Monetico'));
            $order->save();
        }

        $this->getResponse()
            ->setBody($this->getLayout()
                ->createBlock('blackbird_monetico/redirect')
                ->setOrder($order)
                ->setMethodInstance($this->getMethodInstance())
                ->toHtml());

        $session->unsQuoteId();
    }

    /**
     *  Monetico response router
     *
     *  @param    none
     *  @return	  void
     */
    public function notifyAction()
    {
        $model = $this->getMethodInstance();

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            $method = 'post';
        } else if ($this->getRequest()->isGet()) {
            $postData = $this->getRequest()->getQuery();
            $method = 'get';
        } else {
            $model->generateErrorResponse();
        }

        $this->setMoneticoResponse($postData);

        if ($model->getDebugFlag()) {
            Mage::getModel('monetico/api_debug')
                ->setResponseBody(print_r($postData, 1))
                ->save();
        }

        $returnedMAC = $postData['MAC'];
        $correctMAC = $model->getResponseMAC($postData);

        foreach ($this->getRealOrderIds() as $realOrderId) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($realOrderId);

            if (!$order->getId()) {
                $model->generateErrorResponse();
            }
        }

        if ($returnedMAC == $correctMAC) {
            if ($model->isSuccessfulPayment($postData['code-retour'])) {
                foreach ($this->getRealOrderIds() as $realOrderId) {
                    $order = Mage::getModel('sales/order')->loadByIncrementId($realOrderId);

                    // Déblocage de la commande si nécessaire
                    if ($order->getState() == Mage_Sales_Model_Order::STATE_HOLDED) {
                        $order->unhold();
                    }

                    if (!$status = $model->getConfigData('order_status_payment_accepted')) {
                        $status = $order->getStatus();
                    }

                    $message = $model->getSuccessfulPaymentMessage($postData);

                    if ($status == Mage_Sales_Model_Order::STATE_PROCESSING) {
                        if ($model->getConfigData('create_invoice')) {
                            $this->saveInvoice($order);
                        }
                        $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, $status, $message);
                    } else if ($status == Mage_Sales_Model_Order::STATE_COMPLETE) {
                        $this->saveInvoice($order);
                        if ($order->canShip()) {
                            $itemQty = $order->getItemsCollection()->count();
                            $shipment = Mage::getModel('sales/service_order', $order)->prepareShipment($itemQty);
                            $shipment = new Mage_Sales_Model_Order_Shipment_Api();
                            $shipment->create($order->getIncrementId());
                        }
                    } else {
                        if ($model->getConfigData('create_invoice')) {
                            $this->saveInvoice($order);
                        }
                        $order->addStatusToHistory($status, $message, true);
                    }
                    $order->save();

                    if (!$order->getEmailSent()) {
                        $order->sendNewOrderEmail();
                    }
                }
            } else {
                foreach ($this->getRealOrderIds() as $realOrderId) {
                    $order = Mage::getModel('sales/order')->loadByIncrementId($realOrderId);

                    $messageError = $model->getRefusedPaymentMessage($postData);

                    if ($order->getState() == Mage_Sales_Model_Order::STATE_HOLDED) {
                        $order->unhold();
                    }

                    if (!$status = $model->getConfigData('order_status_payment_refused')) {
                        $status = $order->getStatus();
                    }

                    if ($status == Mage_Sales_Model_Order::STATE_HOLDED && $order->canHold()) {
                        $order->hold();
                    } elseif ($status == Mage_Sales_Model_Order::STATE_CANCELED && $order->canCancel()) {
                        $order->cancel();
                    }
                    $order->addStatusHistoryComment($messageError);
                    $order->save();
                }
            }

            if ($method == 'post') {
                $model->generateSuccessResponse();
            } else if ($method == 'get') {
                return;
            }
        } else {
            foreach ($this->getRealOrderIds() as $realOrderId) {
                $order = Mage::getModel('sales/order')->loadByIncrementId($realOrderId);
                if ($order->canCancel())
                    $order->cancel();
                $order->addStatusHistoryComment(Mage::helper('blackbird_monetico')->__('Returned MAC is invalid. Order cancelled.'));
                $order->save();
            }
            $model->generateErrorResponse();
        }
    }

    /**
     *  Save invoice for order
     *
     *  @param    Mage_Sales_Model_Order $order
     *  @return	  boolean Can save invoice or not
     */
    protected function saveInvoice(Mage_Sales_Model_Order $order, $ship = false)
    {
        if ($order->canInvoice()) {
            $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
            $invoice->register()->capture();

            $transactionSave = Mage::getModel('core/resource_transaction')
                ->addObject($invoice)
                ->addObject($invoice->getOrder());

            if ($ship && $order->canShip()) {
                $shipment = Mage::getModel('sales/service_order', $order)->prepareShipment();
                $shipment->register();
                $transactionSave->addObject($shipment);
            }

            $transactionSave->save();
            return true;
        }

        return false;
    }

    /**
     *  Success payment page
     *
     *  @param    none
     *  @return	  void
     */
    public function successAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getMoneticoPaymentQuoteId());
        $session->unsMoneticoPaymentQuoteId();
        $session->setCanRedirect(false);
        $session->setIsMultishipping(false);

        if ($this->getQuote()->getIsMultiShipping())
            $orderIds = array();

        foreach ($this->getRealOrderIds() as $realOrderId) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($realOrderId);

            if (!$order->getId()) {
                $this->norouteAction();
                return;
            }

            $order->addStatusHistoryComment(Mage::helper('blackbird_monetico')->__('Customer successfully returned from Monetico'));
            $order->save();

            if ($this->getQuote()->getIsMultiShipping())
                $orderIds[$order->getId()] = $realOrderId;
        }

        if ($this->getQuote()->getIsMultiShipping()) {
            Mage::getSingleton('checkout/type_multishipping')
                ->getCheckoutSession()
                ->setDisplaySuccess(true)
                ->setPayboxResponseCode('success');

            Mage::getSingleton('core/session')->setOrderIds($orderIds);
            Mage::getSingleton('checkout/session')->setIsMultishipping(true);
        }

        $this->_redirect($this->_getSuccessRedirect());
    }

    /**
     *  Failure payment page
     *
     *  @param    none
     *  @return	  void
     */
    public function errorAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $model = $this->getMethodInstance();

        $session->setIsMultishipping(false);

        foreach ($this->getRealOrderIds() as $realOrderId) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($realOrderId);

            if (!$order->getId()) {
                continue;
            } else if ($order instanceof Mage_Sales_Model_Order && $order->getId()) {
                if (!$status = $model->getConfigData('order_status_payment_canceled')) {
                    $status = $order->getStatus();
                }

                if ($status == Mage_Sales_Model_Order::STATE_HOLDED && $order->canHold()) {
                    $order->hold();
                } elseif ($status == Mage_Sales_Model_Order::STATE_CANCELED && $order->canCancel()) {
                    $order->cancel();
                }
                $order->addStatusHistoryComment($this->__('Order was canceled by customer'));
                $order->save();
            }
        }

        if (!$model->getConfigData('empty_cart')) {
            $this->_reorder();
        }

        $this->_redirect($this->_getErrorRedirect());
    }

    protected function _reorder()
    {
        $cart = Mage::getSingleton('checkout/cart');
        $cartTruncated = false;
        /* @var $cart Mage_Checkout_Model_Cart */

        foreach ($this->getRealOrderIds() as $realOrderId) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($realOrderId);

            if ($order->getId()) {
                $items = $order->getItemsCollection();
                foreach ($items as $item) {
                    try {
                        $cart->addOrderItem($item);
                    } catch (Mage_Core_Exception $e) {
                        if (Mage::getSingleton('checkout/session')->getUseNotice(true)) {
                            Mage::getSingleton('checkout/session')->addNotice($e->getMessage());
                        } else {
                            Mage::getSingleton('checkout/session')->addError($e->getMessage());
                        }
                    } catch (Exception $e) {
                        Mage::getSingleton('checkout/session')->addException($e, Mage::helper('checkout')->__('Cannot add the item to shopping cart.')
                        );
                    }
                }
            }
        }

        $cart->save();
    }

    protected function _getSuccessRedirect()
    {
        if ($this->getQuote()->getIsMultiShipping())
            return 'checkout/multishipping/success';
        else
            return 'checkout/onepage/success';
    }

    protected function _getErrorRedirect()
    {
        if ($this->getQuote()->getIsMultiShipping()) {
            return 'checkout/cart';
        } else {
            return 'checkout/onepage/failure';
        }
    }

    /**
     * When a customer chooses Monetico on Checkout/Payment page and Iframe is enabled
     *
     */
    public function iframeAction() {
        $this->loadLayout();
        $session = Mage::getSingleton('checkout/session');
        $session->setMoneticoPaymentQuoteId($session->getLastQuoteId());

        if ($this->getQuote()->getIsMultiShipping()) {
            $realOrderIds = explode(',', $session->getRealOrderIds());
            $session->setMoneticoRealOrderIds($session->getRealOrderIds());
        } else {
            $realOrderIds = array($session->getLastRealOrderId());
            $session->setMoneticoRealOrderIds($session->getLastRealOrderId());
        }

        foreach ($realOrderIds as $realOrderId) {
            $order = Mage::getModel('sales/order');
            $order->loadByIncrementId($realOrderId);

            if (!$order->getId()) {
                // Debug data when order can not be loaded
                $this->getMethodInstance()->debugData($realOrderIds);
                $this->getMethodInstance()->debugData($session->getData());

                $this->norouteAction();
                return;
            }

            $order->addStatusHistoryComment(Mage::helper('blackbird_monetico')->__('Customer was redirected to Monetico'));
            $order->save();
        }

        $this->getLayout()
                ->getBlock('monetico_iframe')
                ->setOrder($order)
                ->setMethodInstance($this->getMethodInstance());

        $this->renderLayout();

        $session->unsQuoteId();
    }
}