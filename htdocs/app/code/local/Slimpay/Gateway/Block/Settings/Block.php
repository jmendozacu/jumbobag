<?php
require_once __DIR__ . './../../hapiclient/autoload.php';
require_once __DIR__ . './../../Helper/SlimPayHelper.php';

class Slimpay_Gateway_Block_Settings_Block extends Mage_Core_Block_Template {

    public function getCustomer() {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        if ($customer->getId()):
            return $customer;
        endif;

        return false;
    }

	public function getCustomerRevokeMandateSetting() {
		return Mage::getStoreConfig("payment/gateway/slimpay_allow_mandate_revoke");
	}

    private function getSlimpayHelper() {
		return new SlimPayHelper(
			Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->getConfigData("api_url"),
			Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->getConfigData("app_name"),
			Mage::getModel('Slimpay_Gateway_Model_Encrypt')->decrypt(Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->getConfigData("app_secret")),
			Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->getConfigData("creditor_ref")
		);
	}

	public function getLink() {
        $body = [
            "creditor" => array("reference" => Mage::getStoreConfig("payment/gateway/creditor_ref")),
            "subscriber" => array("reference" => Mage::getSingleton("customer/session")->getCustomer()->getId()),
            "items" => array(array("type" => "subscriberLogin")),
            "started" => true
        ];
        
        $slimpayHelper = $this->getSlimpayHelper();
        $res = $slimpayHelper->createOrder($body);
        if(!$res) {
            return $slimpayHelper->getLastErrorMessage();
        }
        $redirectUrl = $res->getLink("https://api.slimpay.net/alps#user-approval")->getHref();
        return $redirectUrl;
    }
}
?>
