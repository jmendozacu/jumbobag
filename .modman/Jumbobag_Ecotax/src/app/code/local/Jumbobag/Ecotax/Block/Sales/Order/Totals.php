<?php

class Jumbobag_Ecotax_Block_Sales_Order_Totals extends Mage_Sales_Block_Order_Totals
{
    protected function _initTotals()
    {
        parent::_initTotals();

        $ecotaxTotalQuote = new Jumbobag_Ecotax_Model_Total_Ecotax_Quote();

        $ecotaxTotal = $this->getEcotaxTotal($this->getOrder()->getAllVisibleItems());

        if ($ecotaxTotal > 0) {
            $this->addTotalBefore(new Varien_Object([
                'code' => $ecotaxTotalQuote->getCode(),
                'value' => $ecotaxTotal,
                'base_value' => $ecotaxTotal,
                'label' => $ecotaxTotalQuote->getLabel()
            ], 'shipping'));
        }

        return $this;
    }

    public function getEcotaxTotal($items)
    {
        $ecotaxTotal = 0;
        foreach ($items as $item) {
            /**
             * @var $item Mage_Sales_Model_Order_Item
             */
            $ecotax = $item->getEcotax();
            $qty = $item->getQtyOrdered();
            $ecotaxTotal += $ecotax * $qty;
        }
        return $ecotaxTotal;
    }
}
