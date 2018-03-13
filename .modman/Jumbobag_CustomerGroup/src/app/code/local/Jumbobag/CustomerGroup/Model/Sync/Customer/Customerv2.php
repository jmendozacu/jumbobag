<?php
class Jumbobag_CustomerGroup_Model_Sync_Customer_Customerv2 extends Lengow_Sync_Model_Customer_Customerv2 {
    public function setFromNode(SimpleXMLElement $xml_node, $config)
    {
        $id_store = $config->getStore()->getStoreId();
        $id_website = Mage::getModel('core/store')->load($id_store)->getWebsiteId();
        $array = Mage::helper('lensync')->xmlToAssoc($xml_node);
        if (empty($array['billing_address']['billing_email']) || $config->isDebugMode() || $config->get('orders/fake_email')) {
            $array['billing_address']['billing_email'] = $array['order_id'] . '-' . $array['marketplace'] . '@lengow.com';
        }

        // first get by email
        $this->setWebsiteId($id_website)
            ->loadByEmail($array['billing_address']['billing_email']);

        if (!$this->getId()) {
            $this->setImportMode(true);
            $this->setWebsiteId($id_website);
            $this->setConfirmation(null);
            $this->setForceConfirmed(true);
            $this->setPasswordHash($this->hashPassword($this->generatePassword(8)));
            $this->setFromLengow(1);
        }

        // Billing address
        $temp_names = array(
            'firstname' => $array['billing_address']['billing_firstname'],
            'lastname' => $array['billing_address']['billing_lastname'],
        );
        $billing_names = self::getNames($temp_names, $config->get('orders/split_name'));
        $array['billing_address']['billing_firstname'] = $billing_names['firstname'];
        $array['billing_address']['billing_lastname'] = $billing_names['lastname'];
        $billing_address = $this->convertAddress($array['billing_address']);
        $this->addAddress($billing_address);

        // Shipping address
        $temp_names = array(
            'firstname' => $array['delivery_address']['delivery_firstname'],
            'lastname' => $array['delivery_address']['delivery_lastname'],
        );
        $billing_names = self::getNames($temp_names, $config->get('orders/split_name'));
        $array['delivery_address']['delivery_firstname'] = $billing_names['firstname'];
        $array['delivery_address']['delivery_lastname'] = $billing_names['lastname'];

        if ($array['tracking_informations']['tracking_relay'] != '') {
            $array['delivery_address']['tracking_relay'] = $array['tracking_informations']['tracking_relay'];
        }
        $shipping_address = $this->convertAddress($array['delivery_address'], 'shipping');
        $this->addAddress($shipping_address);
        Mage::helper('core')->copyFieldset('lengow_convert_billing_address', 'to_customer', $array['billing_address'],
            $this);

        // set group
        $this->setGroupId(
            $this->getGroupFromMarketplace($config, $array['marketplace'])
        );

        $this->save();
        return $this;
    }

    /**
     * @param $config Lengow_Sync_Model_Config
     * @param $marketplace string
     */
    private function getGroupFromMarketplace($config, $marketplace)
    {
        $customerGroupId = $config->get("orders/customer_group_" . $marketplace);
        if (empty($customerGroupId)) {
            $customerGroupId = $config->get("orders/customer_group");
        }
        return $customerGroupId;
    }
}
