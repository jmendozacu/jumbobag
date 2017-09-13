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

class Blackbird_Monetico_Block_Form extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        $this->setTemplate('monetico/form.phtml');
        parent::_construct();
    }
}