<?php
Mage::log('-- Start Jumbobag_Core upgrade 1.2.2 --');
$installer = $this;
$installer->startSetup();

$installer->removeAttribute('catalog_product', 'fixbandebleu');
$installer->addAttribute('catalog_product', 'fixbandebleu', array(
        'group'                     => 'General',
        'input'                     => 'select',
        'type'                      => 'int',
        'label'                     => 'Fix bande bleues',
        'source'                    => 'eav/entity_attribute_source_boolean',
        'global'                    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'                   => 1,
        'required'                  => 0,
        'visible_on_front'          => 0,
        'is_html_allowed_on_front'  => 0,
        'is_configurable'           => 0,
        'searchable'                => 0,
        'filterable'                => 0,
        'comparable'                => 0,
        'unique'                    => false,
        'user_defined'              => false,
        'default'                   => 0,
        'is_user_defined'           => false,
        'used_in_product_listing'   => true
));

$installer->endSetup();
Mage::log('-- End Jumbobag_Core upgrade 1.2.2 --');
