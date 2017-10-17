<?php
Mage::log('-- Start Jumbobag_Core upgrade 1.0.5 --');

try {
    /* @var $installer Mage_Core_Model_Resource_Setup */
    $installer = $this;
    $installer->startSetup();
    $installer->run("
UPDATE catalog_product_entity_text SET value = REPLACE(value, '/new_jumbobag/', '/jumbobag/');
UPDATE sg_exit_intent_popup SET content = REPLACE(content, '/new_jumbobag/', '/jumbobag/');
");
    $installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
}

Mage::log('-- End Jumbobag_Core data upgrade 1.0.5 --');
