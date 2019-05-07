<?php
Mage::log('-- Start Jumbobag_Catalog upgrade 0.3.0 --');

$installer = $this;
$installer->startSetup();

$installer->updateAttribute('catalog_product', 'texte_perso_dispo', array(
    'is_global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));

$installer->updateAttribute('catalog_product', 'texte_perso_rupture', array(
    'is_global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));

$installer->endSetup();
Mage::log('-- End Jumbobag_Catalog upgrade 0.3.0 --');

