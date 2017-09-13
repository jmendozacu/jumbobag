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

class Blackbird_Monetico_Model_Observer
{
    /**
     *  Can redirect to Monetico payment
     */
    public function initRedirect(Varien_Event_Observer $observer)
    {
        Mage::getSingleton('checkout/session')->setCanRedirect(true);
    }

    /**
     *  Return Orders Redirect URL
     *
     *  @return	  string Orders Redirect URL
     */
    public function multishippingRedirectUrl(Varien_Event_Observer $observer)
    {
        if (Mage::getSingleton('checkout/session')->getCanRedirect()) {
            $orderIds = Mage::getSingleton('core/session')->getOrderIds();
            $orderIdsTmp = $orderIds;
            $key = array_pop($orderIdsTmp);
            $order = Mage::getModel('sales/order')->loadByIncrementId($key);

            if (!(strpos($order->getPayment()->getMethod(), 'monetico') === false)) {
                Mage::getSingleton('checkout/session')->setRealOrderIds(implode(',', $orderIds));
                if ($order->getPayment()->getMethod() == 'monetico_multitime') {
                    Mage::app()->getResponse()->setRedirect(Mage::getUrl('monetico/multitime/redirect'));
                } else {
                    Mage::app()->getResponse()->setRedirect(Mage::getUrl('monetico/payment/redirect'));
                }
            }
        } else {
            Mage::getSingleton('checkout/session')->unsRealOrderIds();
        }

        return $this;
    }

    /**
     *  Disables sending email after the order creation
     *
     *  return	  updated order
     */
    public function disableEmailForMultishipping(Varien_Event_Observer $observer)
    {
        $order = $observer->getOrder();

        if (!(strpos($order->getPayment()->getMethod(), 'monetico') === false)) {
            $order->setCanSendNewEmailFlag(false)->save();
        }

        return $this;
    }
}