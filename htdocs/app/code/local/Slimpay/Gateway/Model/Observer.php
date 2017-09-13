<?php

require_once __DIR__ . './../Helper/SlimPayHelper.php';

class Slimpay_Gateway_Model_Observer {

	public function updateOrderStatus($observer) {
		$event = $observer->getEvent();

        if(!Mage::getSingleton('core/session')->getSlimpayCheckoutDone() ||
				!Mage::getSingleton('core/session')->hasSlimpayCheckoutDone()) {
			return;
		}

		if($event->getOrder() == null) // Order is null if it is a subscription
			return;

		Mage::getSingleton('core/session')->setSlimpayPendingOrderId($event->getOrder()->getIncrementId());

		$order = Mage::getModel('gateway/orders')->load(Mage::getSingleton('core/session')->getSlimpayOrderReference(), "order_reference");

		if($order->getOrderReference() == null || $order->getOrderReference() == "")
			return;
		else
			$order->setBoOrderReference($event->getOrder()->getIncrementId())->save();
	}
}
