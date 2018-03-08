<?php
Mage::log('-- Start Innersense 3D Viewer data setup 1.0.1 --');

try {
    /* @var $installer Mage_Core_Model_Resource_Setup */
    $installer = $this;
    $installer->startSetup();

    $installer->setConfigData('viewer3d/settings/init_options', '{"hiddenMenu": true}');

    $installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
}

Mage::log('-- End Innersense 3D Viewer data setup 1.0.1 --');
