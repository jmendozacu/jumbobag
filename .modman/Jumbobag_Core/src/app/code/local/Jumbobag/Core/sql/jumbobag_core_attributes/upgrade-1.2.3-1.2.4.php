<?php
Mage::log('-- Start Jumbobag_Core upgrade 1.2.4 --');
$installer = $this;
$installer->startSetup();

$installer->removeAttribute('catalog_product', 'jbag_theme_sublayout');
$installer->addAttribute(
    'catalog_product',
    'jbag_theme_sublayout',
    array(
        'group'                     => 'General',
        'input'                     => 'select',
        'type'                      => 'varchar',
        'label'                     => 'Agencement',
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
        'used_in_product_listing'   => true,
        'option' => [
            'value' => [
                "original" => ["Original"],
                "original-printed" => ["Original Printed"],
                "mini-swimming-bag" => ["Mini Swimming Bag"],
                "swimming-bag" => ["Swimming Bag"],
                "sunbrella" => ["Sunbrella"],
                "cruiseline" => ["Cruiseline"],
                "swimbrella" => ["Swimbrella"],
                "scuba" => ["Scuba"],
                "scuba-xxl" => ["Scuba XXL"],
                "bowly" => ["Bowly"],
                "chilly-bean" => ["Chilly Bean"],
                "lazy-swimming-bag" => ["Lazy Swimming Bag"],
                "donut-swimming-bag" => ["Donut Swimming Bag"],
                "sublayout-cube-xtrem" => ["Sublayout Cube Xtrem"],
                "housse-swimming-bag" => ["Housse Swimming Bag"],
                "housse-original" => ["Housse Original"],
                "housse-printed" => ["Housse Printed"],
                "softy" => ["Softy"]
            ]
        ]
    )
);

$installer->endSetup();
Mage::log('-- End Jumbobag_Core upgrade 1.2.4 --');
