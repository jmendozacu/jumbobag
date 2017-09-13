<?php
/**
 * Blackbird Agency
 *
 * @category    Blackbird
 * Date: 09/06/17
 * Time: 17:26
 * @copyright   Copyright (c) 2017 Blackbird Agency. (http://black.bird.eu)
 * @author Jérémie Poisson (hello@bird.eu)
 */

class Blackbird_Monetico_PaypalController extends Blackbird_Monetico_Controller_Action
{
    /**
     * Get current Monetico Method Instance
     *
     * @return Blackbird_Monetico_Model_Method_Paypal
     */
    public function getMethodInstance()
    {
        return Mage::getSingleton('blackbird_monetico/method_paypal');
    }
}