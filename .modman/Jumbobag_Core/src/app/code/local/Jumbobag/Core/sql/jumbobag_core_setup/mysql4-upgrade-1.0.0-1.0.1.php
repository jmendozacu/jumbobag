<?php
Mage::log('-- Start Jumbobag_Core upgrade 1.0.1 --');

try {
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$installer->run("
  UPDATE `catalog_product_entity_varchar` SET value='jumbobag/modern' WHERE value='new_jumbobag/default';
");
$installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
}

Mage::log('-- End Jumbobag_Core data upgrade 1.0.1 --');