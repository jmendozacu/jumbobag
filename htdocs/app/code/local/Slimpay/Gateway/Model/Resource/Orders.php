<?php

class Slimpay_Gateway_Model_Resource_Orders extends Mage_Core_Model_Resource_Db_Abstract {
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct() {
        $this->_init('gateway/orders', 'id');
    }
}

?>
