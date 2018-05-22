<?php
/**
 * Blackbird Agency
 *
 * @category    Blackbird
 * Date: 09/06/17
 * Time: 14:18
 * @copyright   Copyright (c) 2017 Blackbird Agency. (http://black.bird.eu)
 * @author Jérémie Poisson (hello@bird.eu)
 */

class Blackbird_Monetico_Model_Method_CofidisTxcb extends Blackbird_Monetico_Model_Method_Abstract
{
    /**
     * Checkout session
     *
     * @var Mage_Checkout_Model_Session
     */
    protected $checkoutSession;

    /**
     * @var string
     */
    protected $_code = 'monetico_3xcb';

    /**
     *  Return URL for Monetico success response
     *
     *  @return	  string URL
     */
    protected function getSuccessURL()
    {
        return Mage::getUrl('monetico/cofidisTx/success', array('_secure' => true));
    }

    /**
     *  Return URL for Monetico failure response
     *
     *  @return	  string URL
     */
    protected function getErrorURL()
    {
        return Mage::getUrl('monetico/cofidisTx/error', array('_secure' => true));
    }

    /**
     *  Return URL for Monetico notify response
     *
     *  @return	  string URL
     */
    protected function getNotifyURL()
    {
        return Mage::getUrl('monetico/cofidisTx/notify', array('_secure' => true));
    }

    /**
     *  Return Order Place Redirect URL
     *
     *  @return	  string Order Redirect URL
     */
    public function getOrderPlaceRedirectUrl()
    {
        Mage::getSingleton('checkout/session')->setIsMultishipping(false);
        return Mage::getUrl('monetico/cofidisTx/redirect');
    }


    /**
     *  Return Form Fields for request to Monetico
     *
     *  @return	  array Array of hidden form fields
     */
    public function getFormFields()
    {
        $session = Mage::getSingleton('checkout/session');

        $order = $this->getOrder();

        if (!($order instanceof Mage_Sales_Model_Order)) {
            Mage::throwException($this->_getHelper()->__('Cannot retrieve order object'));
        }

        $description = $this->getConfigData('description') ? $this->getConfigData('description') : Mage::helper('blackbird_monetico')->__('Order %s', $this->getOrderList());

        $fields = array(
            'version' => Mage::getStoreConfig('payment/monetico_onetime/version'),
            'TPE' => Mage::getStoreConfig('payment/monetico_onetime/tpe_number'),
            'date' => date('d/m/Y:H:i:s'),
            'date_commande' => date('d/m/Y'),
            'montant' => $this->getAmount() . $order->getBaseCurrencyCode(),
            'montant_a_capturer' => $this->getAmount() . $order->getBaseCurrencyCode(),
            'montant_deja_capture' => 0 . $order->getBaseCurrencyCode(),
            'montant_restant' => 0 . $order->getBaseCurrencyCode(),
            'reference' => $this->getOrderList(),
            'texte-libre' => $description,
            'lgue' => $this->_getLanguageCode(),
            'societe' => Mage::getStoreConfig('payment/monetico_onetime/site_code'),
            'url_retour' => $this->getNotifyURL(),
            'url_retour_ok' => $this->getSuccessURL(),
            'url_retour_err' => $this->getErrorURL(),
            'bouton' => 'ButtonLabel'
        );

        if (((int)Mage::getStoreConfig('payment/monetico_onetime/version')) >= 3) {
            $fields['mail'] = $order->getCustomerEmail();
        }

        $fields = parent::getCofidisOptionsData($fields);

        $fields['MAC'] = $this->_getMAC($fields);

        $fields['protocole'] = '3xcb';
        return $fields;
    }

    /**
     *  Prepare string for MAC generation
     *
     *  @param    none
     *  @return	  string MAC string
     */
    protected function _getMAC($data)
    {
        if (((int)Mage::getStoreConfig('payment/monetico_onetime/version')) >= 3) {
            $string = sprintf('%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s', $data['TPE'], $data['date'], $data['montant'], $data['reference'], $data['texte-libre'], $data['version'], $data['lgue'], $data['societe'], $data['mail'], "", "", "", "", "", "", "", "", "", $data['options']);
        } else {
            $string = sprintf('%s*%s*%s*%s*%s*%s*%s*%s*', $data['TPE'], $data['date'], $data['montant'], $data['reference'], $data['texte-libre'], $data['version'], $data['lgue'], $data['societe']);
        }

        return $this->_CMCIC_hmac($string);
    }

    /**
     *  Select old and new system
     *
     *  @param    string
     *  @return	  string encrypted key
     */
    protected function _CMCIC_hmac($string)
    {
        if (Mage::getStoreConfig('payment/monetico_onetime/private_key')) {
            return $this->_CMCIC_hmac_KeyEncrypted($string);
        } else {
            return $this->_CMCIC_hmac_KeyPassphrase($string);
        }
    }

    public function isAvailable($quote = null)
    {
        $isAvailable = parent::isAvailable($quote);

        if($isAvailable) {
            if (is_null($quote)) {
                $quote = $this->checkoutSession->getQuote();
            }

            $minAmount = $this->getConfigData('amount_min');
            $maxAmount = $this->getConfigData('amount_max');
            $isAvailable = ($minAmount >= 0 && $maxAmount > 0 && $minAmount < $maxAmount);

            if($isAvailable) {
                $isAvailable = ($quote->getGrandTotal() >= $minAmount && $quote->getGrandTotal() <= $maxAmount);
            }
        }

        return $isAvailable;
    }
}