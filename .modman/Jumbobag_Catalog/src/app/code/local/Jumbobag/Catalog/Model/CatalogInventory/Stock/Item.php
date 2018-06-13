<?php

class Jumbobag_Catalog_Model_CatalogInventory_Stock_Item extends Mage_CatalogInventory_Model_Stock_Item {

    // FIXME: Change this with the real attribute
    const BACKORDER_CUSTOM_HINT_TEXT = "texte_perso_rupture";

    public function checkQuoteItemQty($qty, $summaryQty, $origQty = 0)
    {
        // First we get original Magento Core result
        $result = parent::checkQuoteItemQty($qty, $summaryQty, $origQty);

        // Then we check only our conditions
        if (($this->checkQty($summaryQty) || $this->checkQty($qty))
            && $this->getBackorders() == Mage_CatalogInventory_Model_Stock::BACKORDERS_YES_NOTIFY
        ) {

            // Get the original error message            
            $backOrdersQty = $result->getItemBackorders();
            $catalogHelper = Mage::helper('jumbobag_core');

            // Some new feature : different message when 1 or more articles are missing
            $message = $backOrdersQty === 1
                ? $catalogHelper->__(
                    'This product is not available in the requested quantity. It will be backordered.'
                )
                : $catalogHelper->__(
                    'This product is not available in the requested quantity. %s of the items will be backordered.', 
                    $backOrdersQty
                );

            // Load the product
            $product = Mage::getModel('catalog/product')->load(
                $this->getProductId()
            );

            // And get the availability message
            $dispo_msg = $product->getData(self::BACKORDER_CUSTOM_HINT_TEXT);

            // Check if a parent item exists
            $parentItem = $this->getParentItem();
            
            // If the parent item exists, and the child item availability message is empty
            if ($this->getIsChildItem() && !empty($parentItem) && empty($dispo_msg)) {
                $parentProduct = Mage::getModel('catalog/product')->load(
                    $parentItem->getProductId()
                );
                // Get the parent availability message
                $dispo_msg = $parentProduct->getData(self::BACKORDER_CUSTOM_HINT_TEXT);
            }

            // If we have an availability message, append it to standard message
            if (!empty($dispo_msg)) {
                $message .= "<br>".$dispo_msg;
            }

            $result->setMessage($message);
        }

        return $result;
    }
}
