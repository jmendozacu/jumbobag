<?php
class Jumbobag_Ecotax_Model_EmailAttachments_Order_Pdf_Order extends Fooman_EmailAttachments_Model_Order_Pdf_Order {
    protected function _printTableHead($page)
    {
        /* Add table head */
        $page->setFillColor(new Zend_Pdf_Color_RGB(0.4, 0.4, 0.4));
        $page->drawText(Mage::helper('sales')->__('Products'), 35, $this->y, 'UTF-8');
        $page->drawText(Mage::helper('sales')->__('SKU'), 255, $this->y, 'UTF-8');
        $page->drawText(Mage::helper('sales')->__('Price'), 380, $this->y, 'UTF-8');
        $page->drawText(Mage::helper('sales')->__('Qty'), 430, $this->y, 'UTF-8');
        $page->drawText(Mage::helper('sales')->__('Tax'), 520, $this->y, 'UTF-8');
        $page->drawText(Mage::helper('sales')->__('Subtotal'), 535, $this->y, 'UTF-8');

        $this->y -= 20;
        return $page;
    }
}
