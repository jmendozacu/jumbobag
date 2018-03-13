<?php
class Jumbobag_CustomerGroup_Model_Sync_Customer_Customer extends Lengow_Sync_Model_Customer_Customer {
    public function setFromNode($order_data, $shipping_address, $config)
    {
        $id_store = $config->getStore()->getStoreId();
        $id_website = Mage::getModel('core/store')->load($id_store)->getWebsiteId();
        $array = array(
            'billing_address' => Mage::helper('lensync')->xmlToAssoc($order_data->billing_address),
            'delivery_address' => Mage::helper('lensync')->xmlToAssoc($shipping_address)
        );
        if (empty($array['billing_address']['email']) || $config->isDebugMode() || $config->get('orders/fake_email')) {
            $array['billing_address']['email'] = $order_data->marketplace_order_id . '-' . $order_data->marketplace . '@lengow.com';
        }

        // first get by email
        $this->setWebsiteId($id_website)
            ->loadByEmail($array['billing_address']['email']);

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
            'firstname' => $array['billing_address']['first_name'],
            'lastname'  => $array['billing_address']['last_name'],
            'fullname'  => $array['billing_address']['full_name']
        );
        $billing_names = self::getNames($temp_names, $config->get('orders/split_name'));
        $array['billing_address']['first_name'] = $billing_names['firstname'];
        $array['billing_address']['last_name'] = $billing_names['lastname'];
        $billing_address = $this->convertAddress($array['billing_address']);
        $this->addAddress($billing_address);

        // Shipping address
        $temp_names = array(
            'firstname' => $array['delivery_address']['first_name'],
            'lastname'  => $array['delivery_address']['last_name'],
            'fullname'  => $array['delivery_address']['full_name']
        );
        $shipping_names = self::getNames($temp_names, $config->get('orders/split_name'));
        $array['delivery_address']['first_name'] = $shipping_names['firstname'];
        $array['delivery_address']['last_name'] = $shipping_names['lastname'];

        if (count($shipping_address->trackings) > 0 && !is_null($shipping_address->trackings[0]->relay->id)) {
            $array['delivery_address']['tracking_relay'] = $shipping_address->trackings[0]->relay->id;
        }
        $shipping_address = $this->convertAddress($array['delivery_address'], 'shipping');
        $this->addAddress($shipping_address);
        Mage::helper('core')->copyFieldset('lengow_convert_address', 'to_customer', $array['billing_address'], $this);

        // set group
        $this->setGroupId(
            $this->getGroupFromMarketplace($config, $order_data->marketplace)
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
