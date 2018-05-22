<?php
/**
 * Blackbird Agency
 *
 * @category    Blackbird
 * Date: 09/06/17
 * Time: 00:00
 * @copyright   Copyright (c) 2017 Blackbird Agency. (http://black.bird.eu)
 * @author Jérémie Poisson (hello@bird.eu)
 */

class Blackbird_Monetico_Model_Method_Onetime extends Blackbird_Monetico_Model_Method_Abstract
{
    /**
     * @var string
     */
    protected $_code = 'monetico_onetime';

    /**
     *  Return URL for Monetico success response
     *
     *  @return	  string URL
     */
    protected function getSuccessURL()
    {
        return Mage::getUrl('monetico/payment/success', array('_secure' => true));
    }

    /**
     *  Return URL for Monetico failure response
     *
     *  @return	  string URL
     */
    protected function getErrorURL()
    {
        return Mage::getUrl('monetico/payment/error', array('_secure' => true));
    }

    /**
     *  Return URL for Monetico notify response
     *
     *  @return	  string URL
     */
    protected function getNotifyURL()
    {
        return Mage::getUrl('monetico/payment/notify', array('_secure' => true));
    }

    /**
     *  Return Order Place Redirect URL
     *
     *  @return	  string Order Redirect URL
     */
    public function getOrderPlaceRedirectUrl()
    {
        Mage::getSingleton('checkout/session')->setIsMultishipping(false);

        if(Mage::getStoreConfig('payment/monetico_onetime/use_iframe')) {
            return Mage::getUrl('monetico/payment/iframe');
        }
        else {
            return Mage::getUrl('monetico/payment/redirect');
        }
    }

    /**
     *  Return Form Fields for request to Monetico
     *
     *  @return	  array Array of hidden form fields
     */
    public function getFormFields()
    {
        $fields = parent::getFormFields();
        $fields = $this->fillDataOptions($fields);
        $fields['MAC'] = $this->_getMAC($fields);
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
        if(!isset($data['options']))
        {
            $data['options'] = '';
        }

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
        return Mage::getStoreConfig('payment/monetico_onetime/private_key')
            ? $this->_CMCIC_hmac_KeyEncrypted($string)
            : $this->_CMCIC_hmac_KeyPassphrase($string);
    }

    /**
     * Add the param for the options
     */
    protected function fillDataOptions($fields)
    {
        // Disable payment methods
        if ($this->getConfigData('allow_options') == 0) {
            $fields['desactivemoyenpaiement'] = $this->getConfigData('disabled_options');
            if(empty($fields['options'])) {
                $fields['options'] = 'desactivemoyenpaiement=' . $this->getConfigData('disabled_options');
            }
            else {
                $fields['options'] .= '&desactivemoyenpaiement=' . $this->getConfigData('disabled_options');
            }
        }

        return $fields;
    }

    /**
     *  Returns Target URL
     *
     *  @return	  string Target URL
     */
    public function getMoneticoUrl()
    {
        return Mage::getStoreConfig('payment/monetico_onetime/environment') == 'sandbox'
            ? 'https://p.monetico-services.com/test/paiement.cgi'
            : 'https://p.monetico-services.com/paiement.cgi';
    }
}