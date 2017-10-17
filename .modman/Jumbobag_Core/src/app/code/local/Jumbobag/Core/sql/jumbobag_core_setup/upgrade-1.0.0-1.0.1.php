<?php
$this->startSetup();

try {
    $item = [
        'path' => 'newsletter/subscription/allow_guest_subscribe',
        'value' => 1,
        'scope' => 'default',
        'scope_id' => 0,
    ];

    $setup = new Mage_Core_Model_Config();
    $setup->saveConfig($item['path'], $item['value'], $item['scope'], $item['scope_id']);
} catch (Exception $e) {
    Mage::logException($e);
}

$this->endSetup();