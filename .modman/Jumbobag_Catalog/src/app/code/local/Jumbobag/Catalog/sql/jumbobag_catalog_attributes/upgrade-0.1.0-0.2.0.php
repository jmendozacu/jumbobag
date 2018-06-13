<?php
Mage::log('-- Start Jumbobag_Core upgrade 1.2.2 --');
$installer = $this;
$installer->startSetup();

$installer->removeAttribute('catalog_product', 'texte_perso_dispo');
$installer->addAttribute('catalog_product', 'texte_perso_dispo', array(
    'type' => 'varchar',
    'label' => 'Texte personnalisé de disponibilité',
    'group' => 'Textes disponibilitées',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible' => 1,
    'required' => 0,
    'visible_on_front' => 0,
    'is_html_allowed_on_front' => 0,
    'is_configurable' => 0,
    'searchable' => 0,
    'filterable' => 0,
    'comparable' => 0,
    'unique' => false,
    'user_defined' => false,
    'default' => 0,
    'is_user_defined' => false,
    'used_in_product_listing' => true
));

$installer->removeAttribute('catalog_product', 'texte_perso_rupture');
$installer->addAttribute('catalog_product', 'texte_perso_rupture', array(
    'type' => 'varchar',
    'label' => 'Alerte de réapprovisionnement',
    'group' => 'Textes disponibilitées',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible' => 1,
    'required' => 0,
    'visible_on_front' => 0,
    'is_html_allowed_on_front' => 0,
    'is_configurable' => 0,
    'searchable' => 0,
    'filterable' => 0,
    'comparable' => 0,
    'unique' => false,
    'user_defined' => false,
    'default' => 0,
    'is_user_defined' => false,
    'used_in_product_listing' => true
));

$entityTypeId = Mage::getModel('catalog/product')
->getResource()
->getEntityType()
->getId();

$installer->endSetup();
Mage::log('-- End Jumbobag_Core upgrade 1.2.2 --');
