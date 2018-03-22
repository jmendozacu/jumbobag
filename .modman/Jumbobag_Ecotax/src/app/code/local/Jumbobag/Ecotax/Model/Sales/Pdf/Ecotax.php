<?php
class Jumbobag_Ecotax_Model_Sales_Pdf_Ecotax extends Mage_Sales_Model_Order_Pdf_Total_Default
{
    /**
     * Get array of arrays with totals information for display in PDF
     * array(
     *  $index => array(
     *      'amount'   => $amount,
     *      'label'    => $label,
     *      'font_size'=> $font_size
     *  )
     * )
     * @return array
     */
    public function getTotalsForDisplay()
    {
        /**
         * @var $order Mage_Sales_Model_Order
         */
        $order = $this->getOrder();
        $items = $order->getAllVisibleItems();

        $ecotax = $this->getEcotaxTotal($items);
        if ($ecotax > 0) {
            $amount = $this->getOrder()->formatPriceTxt($ecotax);
            $totals = array(array(
                'amount'    => $amount,
                'label'     => 'dont éco-participation:',
            ));
        } else {
            $totals = [];
        }

        return $totals;
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
