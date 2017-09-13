<?php

require_once __DIR__ . './../hapiclient/autoload.php';

require_once __DIR__ . './../Helper/SlimPayHelper.php';

use HapiClient\Http;
use HapiClient\Hal;

class Slimpay_Gateway_PaymentController extends Mage_Core_Controller_Front_Action {

	private function getSlimpayHelper() {
		return new SlimPayHelper(
			Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->getConfigData("api_url"),
			Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->getConfigData("app_name"),
			Mage::getModel('Slimpay_Gateway_Model_Encrypt')->decrypt(Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->getConfigData("app_secret")),
			Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->getConfigData("creditor_ref")
		);
	}

	private function validateOrder($id = null) {
		$ref = $id == null ? Mage::getSingleton('core/session')->getSlimpayPendingOrderId() : $id;
		$order = Mage::getModel('sales/order')->load($ref, 'increment_id');
		$order->setState(Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->getConfigData('order_status_success'), true)
		 	  ->save();
	}

	private function getCanCreateDirectDebit() {
		return Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->getConfigData('slimpay_enable_direct_debits_on_validation');
	}

	public function notifAction() {

		// Get orderReference from notification body
		$body = file_get_contents('php://input');
		$body = json_decode($body, true);
		$orderReference = $body["reference"];

		$amount = 0;
		$clientReference = "0";
		$email = "";
		$boOrderId = 0;

		$slimpayHelper = $this->getSlimpayHelper();


		$order = Mage::getModel('gateway/orders')->load($orderReference, 'order_reference');
		if(!$order->getData()) {
			Mage::log("order $orderReference does not exists");
			return;
		} else {
			$amount = $order->getOrderAmount();
			$clientReference = $order->getClientReference();
			$email = $order->getClientEmail();
			$boOrderId = $order->getBoOrderReference();
			$order->delete();
		}

		$status = $slimpayHelper->getOrder($orderReference);
		if(!$status) {
			$this->cancelOrder($email);
			return;
		}
		$success = strpos($status->getState()["state"], "closed.completed") === 0;
		if ($success) {
			// Create direct debit using existing mandate
			$hapiClient = $slimpayHelper->getHapiClient();
			if(!$hapiClient) {
				$this->cancelOrder($email);
				return;
			}
			$rel = new Hal\CustomRel('https://api.slimpay.net/alps#get-mandate');
			$mandate = $hapiClient->sendFollow(new Http\Follow($rel, 'GET'), $status);
			if(!$mandate) {
				$this->cancelOrder($email);
				return;
			}
			$state = $mandate->getState();

			if(!(strpos($clientReference, 'guest') === 0)) // Don't save guest mandates
				Mage::getModel('gateway/mandates')
					->setReference($clientReference)
					->setRum($state['rum']?:"")
					->setMid($state['id'])
					->setDate($state['dateCreated'])
					->save();

			if($this->getCanCreateDirectDebit() && doubleval($amount) > 0) {
				if(Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->getIsWithRum()) {
					$dd = $slimpayHelper->createDirectDebitWithMandate([
						'mandateRum'	=> $state['rum'],
						'amount'		=> doubleval($amount)
					]);
					if(!$dd) {
						error_log($slimpayHelper->getLastErrorMessage());
						$this->cancelAction();
						return;
					}
				}
			}
			$this->validateOrder($boOrderId);

			Mage::getSingleton('checkout/session')->unsQuoteId();
		} else {
			// Something went wrong with the mandate, cancel order
			$this->cancelOrder($email);
			return;
		}
	}

	public function returnAction() {
		if(!Mage::getSingleton('core/session')->getSlimpayCheckoutDone() ||
				!Mage::getSingleton('core/session')->hasSlimpayCheckoutDone()) {
					Mage_Core_Controller_Varien_Action::_redirect('checkout/', array(
						'_secure' => true
					));
			return;
		}

		$slimpayHelper = $this->getSlimpayHelper();
		// Check if we have reused an existing mandate
		if(Mage::getSingleton('core/session')->getSlimpayMandateReused()) {
			$rum = Mage::getSingleton('core/session')->getSlimpayMandateReusedUid();

			$amount = Mage::getSingleton('core/session')->getSlimpayOrderAmount();
			if($this->getCanCreateDirectDebit() && doubleval($amount) > 0) {
				if(Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->getIsWithRum()) {
					$dd = $slimpayHelper->createDirectDebitWithMandate([
						'mandateRum'	=> $rum,
						'amount'		=> doubleval($amount)
					]);
					if(!$dd) {
						error_log($slimpayHelper->getLastErrorMessage());
						$this->cancelAction();
						return;
					}
				}
			}
			Mage::getSingleton('checkout/session')->unsQuoteId();
			$this->validateOrder();
		} else { // Coming back from slimpay, order has already been processed with the notification
				// Nothing to do here, just retrieve the order status and display the associated page
			$orderReference = Mage::getSingleton('core/session')->getSlimpayOrderReference();
			$order = $slimpayHelper->getOrder($orderReference);
			if(!$order) {
				$this->cancelAction();
				return;
			} else {
				$success = strpos($order->getState()["state"], "closed.completed") === 0;
				if(!$success) {
					$this->cancelAction();
					return;
				}
			}

		}

		Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->clearSession();
		Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array(
			'_secure' => true
		));
	}

	public function cancelAction() {

		if(!Mage::getSingleton('core/session')->getSlimpayCheckoutDone() ||
				!Mage::getSingleton('core/session')->hasSlimpayCheckoutDone()) {
					Mage_Core_Controller_Varien_Action::_redirect('checkout/', array(
						'_secure' => true
					));
			return;
		}

		$_customer = Mage::getModel('customer/customer');
		$_customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail(Mage::getSingleton('core/session')->getSlimpayCustomerEmail());

		Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->clearSession();

		$orders = Mage::getResourceModel('sales/order_collection')
			->addFieldToSelect('*')
			->addFieldToFilter('customer_id', $_customer->getId())
			->addAttributeToSort('created_at', 'DESC')
			->setPageSize(1);

		$orders->getFirstItem()->cancel()->save();
		Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/failure', array(
			'_secure' => true
		));
	}

	private function cancelOrder($customerEmail) {

		Mage::log("Cancel order");

		$_customer = Mage::getModel('customer/customer');
		$_customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($customerEmail);

		Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->clearSession();

		$orders = Mage::getResourceModel('sales/order_collection')
			->addFieldToSelect('*')
			->addFieldToFilter('customer_id', $_customer->getId())
			->addAttributeToSort('created_at', 'DESC')
			->setPageSize(1);

		$orders->getFirstItem()->cancel()->save();
	}
}
?>
