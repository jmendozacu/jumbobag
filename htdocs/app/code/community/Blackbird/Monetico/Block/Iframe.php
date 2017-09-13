<?php
/**
 * Blackbird Agency
 *
 * @category    Blackbird
 * Date: 08/06/17
 * Time: 15:36
 * @copyright   Copyright (c) 2017 Blackbird Agency. (http://black.bird.eu)
 * @author Jérémie Poisson (hello@bird.eu)
 */

class Blackbird_Monetico_Block_Iframe extends Mage_Core_Block_Template
{
    /**
     * @return string
     */
    public function getPaymentFormAction()
    {
        return Mage::helper('blackbird_monetico')->getPaymentFormAction($this->getMethodInstance()->getConfigData('environment'));
    }

    /**
     * @return string
     */
    public function getPaymentFormActionUrlEncoded()
    {
        $url = '?';

        $this->getMethodInstance()->setOrder($this->getOrder());

        foreach ($this->getMethodInstance()->getFormFields() as $key => $value) {
            $url .= urlencode($key) . '=' . urlencode($value) . '&';
        }

        $url .= 'mode_affichage=iframe';

        return $this->getPaymentFormAction() . $url;
    }
}