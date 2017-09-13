<?php

class Slimpay_Gateway_Model_Encrypt extends Mage_Core_Model_Config_Data {

	private function encrypt($value) {
		return Mage::helper('core')->encrypt(base64_encode($value));
	}

	public function decrypt($hash) {
		return base64_decode(Mage::helper('core')->decrypt($hash));
	}

    protected function _afterLoad() {
        $value = (string)$this->getValue();
        if (!empty($value) && ($decrypted = $this->decrypt($value))) {
            $this->setValue($decrypted);
        }
    }

    /**
     * Encrypt value before saving
     *
     */
    protected function _beforeSave() {
        $value = (string)$this->getValue();
        // don't change value, if an obscured value came
        if (preg_match('/^\*+$/', $this->getValue())) {
            $value = $this->getOldValue();
        }
        if (!empty($value) && ($encrypted = $this->encrypt($value))) {
            $this->setValue($encrypted);
        }
    }

    /**
     * Get & decrypt old value from configuration
     *
     * @return string
     */
    public function getOldValue() {
        return $this->decrypt(parent::getOldValue());
    }
}
