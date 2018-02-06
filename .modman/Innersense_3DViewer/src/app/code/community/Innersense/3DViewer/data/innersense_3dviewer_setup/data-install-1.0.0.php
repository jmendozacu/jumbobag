<?php
Mage::log('-- Start Innersense 3D Viewer data setup 1.0.0 --');

try {
    /* @var $installer Mage_Core_Model_Resource_Setup */
    $installer = $this;
    $installer->startSetup();

    $eavSetup = Mage::getModel('eav/entity_setup', 'core_setup');
    $eavSetup->addAttribute(Mage_Catalog_Model_Product::ENTITY,  'innersense_id', array(
        'type' => 'varchar',
        'input' => 'text',
        'label' => 'Innersense ID',
        'required' => false,
        'user_defined' => true,
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'group' => 'General',
    ));

    $installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
}

Mage::log('-- End Innersense 3D Viewer data setup 1.0.0 --');
