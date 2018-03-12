<?php

class Jumbobag_Ecotax_Model_Total_Ecotax_Quote extends Mage_Sales_Model_Quote_Address_Total_Abstract
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

        $ecotaxTotal = $this->getEcotaxTotal($address->getQuote()->getAllVisibleItems());

        if ($ecotaxTotal > 0) {
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => $this->getLabel(),
                'value' => $ecotaxTotal
            ));
        }

        return $this;
    }

    public function getEcotaxTotal($items)
    {
        $ecotaxTotal = 0;
        foreach ($items as $item) {
            /**
             * @var $item Mage_Sales_Model_Quote_Item
             */
            $ecotax = $item->getEcotax();
            $qty = $item->getQty();
            $ecotaxTotal += $ecotax * $qty;
        }
        return $ecotaxTotal;
    }
}
