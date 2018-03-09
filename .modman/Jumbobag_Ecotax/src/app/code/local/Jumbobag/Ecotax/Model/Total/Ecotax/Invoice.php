<?php

class Jumbobag_Ecotax_Model_Total_Ecotax_Invoice extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    public function __construct()
    {
        $this->setCode('jumbobag_ecotax');
    }

    public function getLabel()
    {
        return Mage::helper('jumbobag_ecotax')->__('dont éco-participation');
    }



    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        if ($address->getAddressType() == 'billing') {
            return;
        }

        $ecotaxTotal = 0;
        $items = $address->getQuote()->getAllVisibleItems();
        foreach ($items as $item) {
            /**
             * @var $item Mage_Sales_Model_Quote_Item
             */
            $ecotax = $item->getEcotax();
            $qty = $item->getQty();
            $ecotaxTotal += $ecotax * $qty;
        }

        Mage::log("total" . $ecotaxTotal);

        if ($ecotaxTotal > 0) {
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => $this->getLabel(),
                'value' => $ecotaxTotal
            ));
        }

        return $this;
    }
}
