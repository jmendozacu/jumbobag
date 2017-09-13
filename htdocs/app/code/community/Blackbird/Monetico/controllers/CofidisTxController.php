<?php
/**
 * Blackbird Agency
 *
 * @category    Blackbird
 * Date: 09/06/17
 * Time: 16:10
 * @copyright   Copyright (c) 2017 Blackbird Agency. (http://black.bird.eu)
 * @author Jérémie Poisson (hello@bird.eu)
 */

class Blackbird_Monetico_CofidisTxController extends Blackbird_Monetico_Controller_Action
{
    /**
     * Get current Monetico Method Instance
     *
     * @return Blackbird_Monetico_Model_Method_CofidisTxcb
     */
    public function getMethodInstance()
    {
        return Mage::getSingleton('blackbird_monetico/method_cofidisTxcb');
    }
}