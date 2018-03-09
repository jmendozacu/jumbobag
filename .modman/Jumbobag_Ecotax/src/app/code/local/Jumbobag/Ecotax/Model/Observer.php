<?php

class Jumbobag_Ecotax_Model_Observer
{
    public function checkoutCartProductAddAfter($observer)
    {
        /**
         * @var $item Mage_Sales_Model_Quote_Item
         */
        $item = $observer->getEvent()->getQuoteItem();
        $productId = $item->getProductId();
        $product = Mage::getModel('catalog/product')->load($productId);
        $ecotax = Mage::helper('jumbobag_ecotax')->getEcotax($product);
        if (!empty($ecotax)) {
            $item->setEcotax($ecotax);
            $item->save();
        }

        return $this;
    }
}
