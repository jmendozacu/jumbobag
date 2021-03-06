<?php
/**
 * Blackbird Agency
 *
 * @category    Blackbird
 * Date: 09/06/17
 * Time: 14:34
 * @copyright   Copyright (c) 2017 Blackbird Agency. (http://black.bird.eu)
 * @author Jérémie Poisson (hello@bird.eu)
 */

class Blackbird_Monetico_Model_Method_Paypal extends Blackbird_Monetico_Model_Method_Abstract
{
    /**
     * @var string
     */
    protected $_code = 'monetico_paypal';


    /**
     *  Return URL for Monetico success response
     *
     *  @return	  string URL
     */
    protected function getSuccessURL()
    {
        return Mage::getUrl('monetico/paypal/success', array('_secure' => true));
    }

    /**
     *  Return URL for Monetico failure response
     *
     *  @return	  string URL
     */
    protected function getErrorURL()
    {
        return Mage::getUrl('monetico/paypal/error', array('_secure' => true));
    }

    /**
     *  Return URL for Monetico notify response
     *
     *  @return	  string URL
     */
    protected function getNotifyURL()
    {
        return Mage::getUrl('monetico/paypal/notify', array('_secure' => true));
    }

    /**
     *  Return Order Place Redirect URL
     *
     *  @return	  string Order Redirect URL
     */
    public function getOrderPlaceRedirectUrl()
    {
        Mage::getSingleton('checkout/session')->setIsMultishipping(false);
        return Mage::getUrl('monetico/paypal/redirect');
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

        $fields['MAC'] = $this->_getMAC($fields);

        $fields['protocole'] = 'paypal';
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
            $string = sprintf('%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*', $data['TPE'], $data['date'], $data['montant'], $data['reference'], $data['texte-libre'], $data['version'], $data['lgue'], $data['societe'], $data['mail'], "", "", "", "", "", "", "", "", "");
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
}