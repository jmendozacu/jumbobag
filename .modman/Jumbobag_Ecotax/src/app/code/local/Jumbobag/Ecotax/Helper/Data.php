<?php

class Jumbobag_Ecotax_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param $product Mage_Catalog_Model_Product
     * @return float|null
     */
    public function getEcotax($product)
    {
        $ecotax = $product->getEcotax();

        if ($ecotax == null && $product->getTypeId() === Mage_Catalog_Model_Product_Type::TYPE_SIMPLE) {
            $parentIds = Mage::getModel('catalog/product_type_configurable')
                ->getParentIdsByChild($product->getId());

            if (count($parentIds) > 0) {
                $product = Mage::getModel('catalog/product')->load($parentIds[0]);
                $ecotax = $product->getEcotax();
            }
        }

        return (float)$ecotax;
    }
}
