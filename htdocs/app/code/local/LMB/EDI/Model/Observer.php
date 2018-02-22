<?php
class LMB_EDI_Model_Observer {

    public function __construct() {
    }

    public function newOrder(Varien_Event_Observer $observer) {
        $order = $observer->getEvent()->getOrder();
        //$items = $order->getAllItems();
        LMB_EDI_Model_Liaison_Event::create("create_order", $order->getRealOrderId());
        return $this;
    }

    public function newPayment(Varien_Event_Observer $observer) {
		$order = $observer->getEvent()->getInvoice()->getOrder();
        LMB_EDI_Model_Liaison_Event::create("create_payment", $order->getRealOrderId());
		//LMB_EDI_Model_EDI::trace("debug_event", print_r($observer->getEvent(), true));
		//LMB_EDI_Model_EDI::trace("debug_payment", print_r($observer->getEvent()->getPayment(), true));
        return $this;
    }
	
	public function newPaymentFianet(Varien_Event_Observer $observer) {
		$evt = $observer->getEvent();
		$order = $evt['order_increment_id'];
        LMB_EDI_Model_Liaison_Event::create("create_payment", $order->getRealOrderId());
		//LMB_EDI_Model_EDI::trace("debug_event", print_r($observer->getEvent(), true));
		//LMB_EDI_Model_EDI::trace("debug_payment", print_r($observer->getEvent()->getPayment(), true));
        return $this;
    }

    public function userUpdateAccount(Varien_Event_Observer $observer) {
        //Mage::log(var_dump($observer));
        //LMB_EDI_Model_Liaison_Event::create(0,0);
        return $this;
    }
	
	public function saveProduct(Varien_Event_Observer $observer) {
        $product = $observer->getProduct();
		LMB_EDI_Model_EDI::trace("import", "Observer - Produit ID: ". $product->getId());
        LMB_EDI_Model_Liaison_Event::create("create_article", $product->getId());
        return $this;
    }
	
}
?>
