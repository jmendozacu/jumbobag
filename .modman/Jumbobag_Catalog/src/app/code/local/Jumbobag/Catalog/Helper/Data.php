<?php

class Jumbobag_Catalog_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getDataCategotyIds($product)
    {
        $categoriesId = $product->getCategoryIds();
        return (is_array($categoriesId)) ? implode(',', $categoriesId) : '';
    }

    public function getDataProductSwatches(array $productIds)
    {
        $data = [];

        foreach ($productIds as $productId) {
            $product = Mage::getModel('catalog/product')->load($productId);

            if (!$product) {
                continue;
            }

            $cover = (string)  Mage::helper('catalog/image')->init($product, 'thumbnail')->resize(500, 500);
            $_gallery = $product->getMediaGalleryImages()->getItems();
            $gallery = array_reduce($_gallery, function ($carry, $item) {
                $carry[] = [
                    'title' => $item->getLabel(),
                    'href' => $item->getUrl(),
                ];
                return $carry;
            }, []);

            $data[$product->getId()] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'cover' => $cover,
                'gallery' => $gallery,
            ];
        }

        return $data;
    }
}
