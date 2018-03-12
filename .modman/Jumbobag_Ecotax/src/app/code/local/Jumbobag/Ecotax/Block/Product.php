<?php

class Jumbobag_Ecotax_Block_Product extends Mage_Core_Block_Template
{
    public $_template = 'jumbobag/ecotax/product.phtml';

    public function getEcotax()
    {
        $product = $this->getProduct();
        $ecotax = $product->getEcotax();
        if (empty($ecotax)) {
            return null;
        } else {
            return $this->helper('core')->formatPrice($ecotax);
        }
    }

    private function getProduct()
    {
        $product = Mage::registry('current_product');
        if (!$product) {
            Mage::log('Ecotax was loaded in a context without any product', Zend_Log::ERR);
            $product = new Mage_Catalog_Model_Product();
        }
        return $product;
    }
}
