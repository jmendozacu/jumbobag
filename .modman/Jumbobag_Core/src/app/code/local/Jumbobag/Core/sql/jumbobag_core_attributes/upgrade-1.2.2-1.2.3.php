<?php
$instlog = function ($data) {
    Mage::Log($data, null, 'system.log', true);
};

$instlog('-- Start Jumbobag_Core upgrade 1.2.3 --');
$installer = $this;
$installer->startSetup();

$keys = [
    "original",
    "original-printed",
    "cube",
    "mini-swimming-bag",
    "swimming-bag",
    "scuba-xxl",
    "bowly",
    "chilly-bean",
    "lazy-swimming-bag",
    "donut-swimming-bag",
    "cube-xtrem"
];

foreach ($keys as $key) {
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
        $instlog('Fixing product with ID: '.$product->getId());
        Mage::getSingleton('catalog/product_action')
            ->updateAttributes(
                [$product->getId()],
                ["fixbandebleu" => 1],
                Mage_Core_Model_App::ADMIN_STORE_ID
            );
    }
}

$installer->endSetup();
$instlog('-- End Jumbobag_Core upgrade 1.2.3 --');
