<?php

class Jumbobag_Catalog_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getProductClassFilter($product)
    {
        $categoriesId = $product->getCategoryIds();

        if (!$categoriesId) {
            return '';
        }

        $cls = array_map(function ($categoryId) {
            return "filter-{$categoryId}";
        }, $categoriesId);

        return implode(' ', $cls);
    }
}
