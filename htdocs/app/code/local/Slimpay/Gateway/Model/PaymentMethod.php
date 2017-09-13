<?php

require_once __DIR__ . './../hapiclient/autoload.php';

require_once __DIR__ . './../Helper/SlimPayHelper.php';

use HapiClient\Http;
use HapiClient\Hal;

class Slimpay_Gateway_Model_PaymentMethod extends Mage_Payment_Model_Method_Abstract {

    protected $_code = 'gateway';

    public function getInformation()  {
        return $this->getConfigData('information');
    }

    public function getTransactionType() {
        return $this->getConfigData('transactiontype');
    }

    public function getIsWithRum() {
        return !$this->getConfigData('slimpay_allow_mandate_without_rum');
    }

    public function canManageRecurringProfiles() {
        return false;
    }

    private function getSlimpayHelper() {
        return new SlimPayHelper(
            Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->getConfigData("api_url"),
            Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->getConfigData("app_name"),
            Mage::getModel('Slimpay_Gateway_Model_Encrypt')->decrypt(Mage::getModel('Slimpay_Gateway_Model_PaymentMethod')->getConfigData("app_secret")),
            $this->getConfigData('creditor_ref')
        );
    }

    // Try a login to check if the client is authentified, if not disable the payment method
    public function canUseCheckout() {
        try {
            $this->getSlimpayHelper()->getHapiClient()->getEntryPointResource();
            return true;
        } catch(Exception $e) {
            return false;
        }
    }


    public function getTitle() {

        // First function called in the payment workflow. We must clean the session variables
        $this->clearSession();


        $clientReference = Mage::getSingleton('customer/session')->getCustomer()->getId();
        // Checking if a mandate already exists :
        $storedMandate = Mage::getModel('gateway/mandates')->load($clientReference, "reference");
        if($storedMandate->getRum() != "" || $storedMandate->getMid() != "") {

            if($this->getIsWithRum())
                $mandate = $this->getSlimpayHelper()->getMandate(['rum', $storedMandate->getRum()]);
            else
                $mandate = $this->getSlimpayHelper()->getMandate(['id', $storedMandate->getMid()]);

            if($mandate === false)
                return $this->getConfigData('title');
            $state = $mandate->getState();
            if($state["state"] == 'active' || $state["state"] == 'waitingForReference') {
                $date = substr($state["dateSigned"], 0, 10);
                return $this->getConfigData('title')." (You will use the mandate signed the ".$date.")";
            } else {
                // Mandate is expired
                $storedMandate->delete();
            }
        }
        return $this->getConfigData('title');
    }

    public function assignData($data) {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();
        return $this;
    }

    public function getOrderPlaceRedirectUrl() {
        $slimpayHelper = $this->getSlimpayHelper();

        $requestType = $this->getTransactionType();
        $price       = Mage::getSingleton('checkout/cart')->getQuote()->getGrandTotal();

        $customer  = Mage::getSingleton('customer/session')->getCustomer();

        $title     = Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getPrefix() ?: "Mr";
        $email     = Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getEmail();
        $firstname = Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getFirstname();
        $lastname  = Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getLastname();
        $telephone = Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getTelephone();

        $timestamp       = Mage::getModel('core/date')->timestamp(time());
        $clientReference = $customer->getId();
        if(!$clientReference) {
            $clientReference = "guest_" . Mage::getSingleton('checkout/cart')->getQuote()->getReservedOrderId();
        }
        $transactionId   = $clientReference . '_' . $timestamp;

        $billingAddress = Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress();
        $line1          = $billingAddress->getStreet(1);
        $line2          = $billingAddress->getStreet(2);
        $country        = $billingAddress->getCountryId();
        $city           = $billingAddress->getCity();
        $zipcode        = $billingAddress->getPostcode();

        $company = $billingAddress->getCompany();

        // Checking if a mandate already exists :
        if($this->checkActiveMandate($clientReference, $price, $email)) {
            Mage::getSingleton('core/session')->setSlimpayCheckoutDone(true);
            Mage::getSingleton('core/session')->setSlimpayClientReference($clientReference);
            return Mage::getSingleton('core/session')->getSlimpayRedirectUrl();
        }

        $clientData = [
            'clientReference'   => $clientReference,
            'honorificPrefix'   => $title,
            'familyName'        => $lastname,
            'givenName'         => $firstname,
            'email'             => $email,
            'telephone'         => $telephone,
            'companyName'       => $company ?: null,
            'street1'           => $line1,
            'street2'           => $line2,
            'city'              => $city,
            'postalCode'        => $zipcode,
            'country'           => $country
        ];

        try {
            $res = $slimpayHelper->createMandate($clientData, $this->getIsWithRum());
            if(!$res) {
                Mage::getSingleton('core/session')->addError($slimpayHelper->getLastErrorMessage());
                return Mage::getUrl('gateway/payment/return');
            }
            $redirectUrl = $res->getLink("https://api.slimpay.net/alps#user-approval")->getHref();
            $orderReference = $res->getState()["reference"];
            Mage::getSingleton('core/session')->setSlimpayOrderReference($orderReference);
            Mage::getSingleton('core/session')->setSlimpayOrderAmount($price);
            Mage::getSingleton('core/session')->setSlimpayClientReference($clientReference);
            Mage::getSingleton('core/session')->setSlimpayCustomerEmail($email); // To retrieve order in return page
            Mage::getSingleton('core/session')->setSlimpayCheckoutDone(true);

            Mage::getModel('gateway/orders')
                ->setOrderReference($orderReference)
                ->setOrderAmount($price)
                ->setClientReference($clientReference)
                ->setClientEmail($email)
                ->save();

            return $redirectUrl;
        } catch(Exception $e) {
            return Mage::getUrl('gateway/payment/return');
        }
    }


    public function clearSession() {
		// deleting session  vars
		Mage::getSingleton('core/session')->unsSlimpayRecurringData();
		Mage::getSingleton('core/session')->unsSlimpayNoMandate();
		Mage::getSingleton('core/session')->unsSlimpayCustomerEmail();
		Mage::getSingleton('core/session')->unsSlimpayClientReference();
		Mage::getSingleton('core/session')->unsSlimpayOrderAmount();
		Mage::getSingleton('core/session')->unsSlimpayOrderReference();
		Mage::getSingleton('core/session')->unsSlimpayCustomerEmail();
		Mage::getSingleton('core/session')->unsSlimpayOrderAmount();
		Mage::getSingleton('core/session')->unsSlimpayMandateReused();
		Mage::getSingleton('core/session')->unsSlimpayMandateReusedUid();
        Mage::getSingleton('core/session')->unsSlimpayCheckoutDone();
        Mage::getSingleton('core/session')->unsSlimpayPendingOrderId();
	}

    private function checkActiveMandate($clientReference, $price, $email) {
        $slimpayHelper = $this->getSlimpayHelper();
        $mandate = Mage::getModel('gateway/mandates')->load($clientReference, "reference");
        if($mandate->getRum() != "" || $mandate->getMid() != "") {
            if($this->getIsWithRum())
                $slmpMandate = $slimpayHelper->getMandate(['rum', $mandate->getRum()]);
            else
                $slmpMandate = $slimpayHelper->getMandate(['id', $mandate->getMid()]);
            if($slmpMandate === false) {
                $mandate->delete();
                return false;
            }
            $state = $slmpMandate->getState();
            if($state["state"] == 'active' || $state["state"] == 'waitingForReference') {
                // Send to return action the mandate's RUM
                if($this->getIsWithRum())
                    Mage::getSingleton('core/session')->setSlimpayMandateReusedUid($state["rum"]);
                else
                    Mage::getSingleton('core/session')->setSlimpayMandateReusedUid($state["id"]);
                Mage::getSingleton('core/session')->setSlimpayRedirectUrl(Mage::getUrl('gateway/payment/return'));
                Mage::getSingleton('core/session')->setSlimpayMandateReused(true);
                Mage::getSingleton('core/session')->setSlimpayOrderAmount($price);
                Mage::getSingleton('core/session')->setSlimpayCustomerEmail($email); // To retrieve order in return page
                return true;
            } else {
                $mandate->delete();
                return false;
            }
        }
    }

}
