<?php

class Jumbobag_Catalog_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getDataCategotyIds($product)
    {
        $categoriesId = $product->getCategoryIds();
        return (is_array($categoriesId)) ? implode(',', $categoriesId) : '';
    }
}
