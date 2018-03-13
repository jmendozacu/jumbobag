<?php
Mage::log('-- Start Jumbobag Customer Group data setup 0.1.0 --');

try {
    /* @var $installer Mage_Core_Model_Resource_Setup */
    $installer = $this;
    $installer->startSetup();

    $installer->setConfigData('lensync/orders/customer_group_amazon', 6);
    $installer->setConfigData('lensync/orders/customer_group_cdiscount', 7);
    $installer->setConfigData('lensync/orders/customer_group_laredoute', 4);
    $installer->setConfigData('lensync/orders/customer_group_fnac', 5);

    $installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
}

Mage::log('-- End Jumbobag Customer Group data setup 0.1.0 --');
