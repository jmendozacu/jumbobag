<?php
Mage::log('-- Start Jumbobag_Core upgrade 1.0.2 --');

try {
    /* @var $installer Mage_Core_Model_Resource_Setup */
    $installer = $this;
    $installer->startSetup();
    $installer->run("
  DELETE FROM `core_config_data` WHERE path='design/theme/default' AND value='jumbobag';
");
    $installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
}

Mage::log('-- End Jumbobag_Core data upgrade 1.0.2 --');
