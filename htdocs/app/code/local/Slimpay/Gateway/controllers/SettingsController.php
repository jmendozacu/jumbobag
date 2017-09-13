<?php

class Slimpay_Gateway_SettingsController extends Mage_Core_Controller_Front_Action {
	
	public function indexAction() {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            $this->_redirect('customer/account/login');
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }
}