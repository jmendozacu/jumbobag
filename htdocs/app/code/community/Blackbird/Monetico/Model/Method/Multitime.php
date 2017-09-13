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

class Blackbird_Monetico_Model_Method_Multitime extends Blackbird_Monetico_Model_Method_Abstract
{
    /**
     * @var string
     */
    protected $_code = 'monetico_multitime';


    /**
     *  Return URL for Monetico success response
     *
     *  @return	  string URL
     */
    protected function getSuccessURL()
    {
        return Mage::getUrl('monetico/several/success', array('_secure' => true));
    }

    /**
     *  Return URL for Monetico failure response
     *
     *  @return	  string URL
     */
    protected function getErrorURL()
    {
        return Mage::getUrl('monetico/several/error', array('_secure' => true));
    }

    /**
     *  Return URL for Monetico notify response
     *
     *  @return	  string URL
     */
    protected function getNotifyURL()
    {
        return Mage::getUrl('monetico/several/notify', array('_secure' => true));
    }

    /**
     *  Return Order Place Redirect URL
     *
     *  @return	  string Order Redirect URL
     */
    public function getOrderPlaceRedirectUrl()
    {
        Mage::getSingleton('checkout/session')->setIsMultishipping(false);

        if(Mage::getStoreConfig('payment/monetico_multitime/use_iframe')) {
            return Mage::getUrl('monetico/several/iframe');
        }
        else {
            return Mage::getUrl('monetico/several/redirect');
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
        return $this->getSplitCheckoutFormFields($fields);
    }

    /**
     *  Return Split Checkout Form Fields for request to Monetico
     *
     *  @return	  array Array of hidden form fields
     */
    public function getSplitCheckoutFormFields($fields)
    {
        $nbTerms = $this->getQuote()->getPayment()->getNbrech();

        if ($nbTerms > 1) {
            $amount = $this->getAmount();

            $fields['nbrech'] = $nbTerms;

            $terms = array(
                'montantech1' => round($amount / $nbTerms, 2),
                'dateech1' => date('d/m/Y'));

            for ($i = 2; $i < $nbTerms + 1; $i++) {
                $terms['montantech' . $i] = $terms['montantech1'];
                $dateech = '+ ' . ($i - 1) . ' month';
                $terms['dateech' . $i] = date('d/m/Y', strtotime($dateech));
            }

            if ($terms['montantech1'] * $nbTerms != $amount) {
                $result = $terms['montantech1'] * ($nbTerms - 1);
                $terms['montantech1'] = $amount - $result;
            }

            $order = $this->getOrder();
            for ($i = 1; $i < $nbTerms + 1; $i++) {
                $terms['montantech' . $i] = sprintf('%.2f', $terms['montantech' . $i]) . $order->getBaseCurrencyCode();
            }

            $fields = array_merge($fields, $terms);
        }

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
        if (((int)$this->getVersion()) >= 3) {
            $string = sprintf('%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*', $data['TPE'], $data['date'], $data['montant'], $data['reference'], $data['texte-libre'], $data['version'], $data['lgue'], $data['societe'], $data['mail'], $data['nbrech']);
            for($i = 1; $i <= $data['nbrech']; $i++)
            {
                $string = $string . sprintf('%s*%s*', $data['dateech'.$i], $data['montantech'.$i]);
            }
        } else {
            $string = sprintf('%s*%s*%s*%s*%s*%s*%s*%s*', $data['TPE'], $data['date'], $data['montant'], $data['reference'], $data['texte-libre'], $data['version'], $data['lgue'], $data['societe']);
        }

        return $this->_CMCIC_hmac($string);
    }
}