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

class Blackbird_Monetico_Block_Redirect extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        $methodInstance = $this->getMethodInstance();

        $form = new Varien_Data_Form();
        $form->setAction($methodInstance->getMoneticoUrl())
            ->setId('monetico_payment_checkout')
            ->setName('monetico_payment_checkout')
            ->setMethod('POST')
            ->setUseContainer(true);
        foreach ($methodInstance->setOrder($this->getOrder())->getFormFields() as $field => $value) {
            $form->addField($field, 'hidden', array('name' => $field, 'value' => $value));
        }

        $formHTML = $form->toHtml();

        $html = '<html><body>';
        $html.= $this->__('You will be redirected to Monetico in a few seconds.');
        $html.= $formHTML;
        $html.= '<script type="text/javascript">document.getElementById("monetico_payment_checkout").submit();</script>';
        $html.= '</body></html>';

        if ($methodInstance->getConfigData('debug_flag')) {
            Mage::getModel('monetico/api_debug')
                ->setRequestBody($formHTML)
                ->save();
        }

        return $html;
    }
}