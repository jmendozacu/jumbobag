<?php
$instlog = function ($data) {
    Mage::Log($data, null, 'system.log', true);
};

$mapping = array(
    "original" => "Original",
    "original-printed" => "Original Printed",
    "mini-swimming-bag" => "Mini Swimming Bag",
    "swimming-bag" => "Swimming Bag",
    "pouf-poire-scuba-xtrem-sunbrella1" => "Sunbrella",
    "pouf-coussin-geant-jumbobag-xtrem-cruiseline" => "Cruiseline",
    "pouf-poire-scuba-xtrem-swimbrella" => "Swimbrella",
    "pouf-poire-scuba-xtrem-scuba" => "Scuba",
    "scuba-xxl" => "Scuba XXL",
    "bowly" => "Bowly",
    "chilly-bean" => "Chilly Bean",
    "lazy-swimming-bag" => "Lazy Swimming Bag",
    "donut-swimming-bag" => "Donut Swimming Bag",
    "cube-xtrem" => "Sublayout Cube Xtrem",
    "housse-coussin-geant-swimming-bag" => "Housse Swimming Bag",
    "housse-coussin-geant-original-jumbobag" => "Housse Original",
    "housse-coussin-geant-jumbobag-original-printed" => "Housse Printed",
    "pouf-mini-coussin-softy-jumbobag" => "Softy"
);


$instlog('-- Start Jumbobag_Core upgrade 1.2.5 --');
$installer = $this;
$installer->startSetup();

$attributeCode = "jbag_theme_sublayout";
$attribute = Mage::getModel('eav/config')
                    ->getAttribute(
                        Mage_Catalog_Model_Product::ENTITY,
                        $attributeCode
                    );

foreach ($mapping as $key => $attrVal) {
    $instlog('Looking for product with url key: '.$key);

    $model = Mage::getModel('catalog/product');
    $products = $model
                ->getCollection()
                ->addAttributeToFilter('url_key', $key)
                ->setPageSize(5000) // Normalement y'en a que 1 de toutes façons
                ->setCurPage(1);

    if (count($products) < 1) {
        $instlog("not found :'(");
    }

    foreach ($products as $product) {
        $optId = $attribute->getSource()->getOptionId(str_replace("-", " ", $attrVal));
        $instlog("Setting $attributeCode = $optId ($attrVal) for product with ID: ".$product->getId());
        Mage::getSingleton('catalog/product_action')
            ->updateAttributes(
                [$product->getId()],
                [$attributeCode => $optId],
                Mage_Core_Model_App::ADMIN_STORE_ID
            );
    }
}

$installer->endSetup();
$instlog('-- End Jumbobag_Core upgrade 1.2.5 --');
