<?php
Mage::log('-- Start Jumbobag_Core upgrade 1.0.4 --');
$this->startSetup();

try {
    // Activate cookie_restriction
    $item = [
        'path' => 'web/cookie/cookie_restriction',
        'value' => 1,
        'scope' => 'default',
        'scope_id' => 0,
    ];

    $setup = new Mage_Core_Model_Config();
    $setup->saveConfig($item['path'], $item['value'], $item['scope'], $item['scope_id']);

    // Add translation to cookie_restriction_notice_block
    $block = Mage::getModel('cms/block')->load(2)->addData([
        'content' => '{{block type="core/template" template="cms/cookie.phtml"}}'
    ]);
    $block->save();
} catch (Exception $e) {
    Mage::logException($e);
}

$this->endSetup();
Mage::log('-- End Jumbobag_Core upgrade 1.0.4 --');