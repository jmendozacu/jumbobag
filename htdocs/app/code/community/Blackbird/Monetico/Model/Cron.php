<?php
/**
 * Blackbird Agency
 *
 * @category    Blackbird
 * Date: 11/01/18
 * Time: 10:35
 * @copyright   Copyright (c) 2018 Blackbird Agency. (http://black.bird.eu)
 * @author Jérémie Poisson (hello@bird.eu)
 */

class Blackbird_Monetico_Model_Cron {
    /**
     * Execute the Cancel Order Task
     *
     * @return $this
     */
    public function execute()
    {
        if (Mage::getStoreConfig('payment/monetico/use_cron_cancel') == "1") {
            $this->processCancelOrders();
        }
        return $this;
    }


    /**
     * Change status of orders that are "too old" and with the "new order" status
     *
     * @return $this
     */
    protected function processCancelOrders()
    {
        // Order collection
        $orderCollection = Mage::getModel('sales/order')->getCollection();
        $orderCollection->join(array('p' => 'sales/order_payment'), 'main_table.entity_id=p.parent_id');

        // Time limit = now - delay (configured in backend)
        if(Mage::getStoreConfig('payment/monetico/cancel_delay') != NULL) {
            $delay = intval(Mage::getStoreConfig('payment/monetico/cancel_delay'));
        }
        else {
            $delay = 5;
        }
        $timeLimit = strtotime("-".$delay." minutes");

        foreach ($orderCollection as $order) {
                $orderMethod = $order->getMethod();

                // IF: the payment method is a monetico method
                if(strpos($order->getMethod(), "monetico") !== false) {
                    $orderDate = strtotime($order->getUpdatedAt()); // Order date
                    $orderStatus = $order->getStatus(); // Order status
                    $newStatus = Mage::getStoreConfig('payment/' . $orderMethod . '/order_status'); // "new" status for this payment method
                    $cancelStatus = Mage::getStoreConfig('payment/' . $orderMethod . '/order_status_payment_canceled'); // "canceled" status for this payment method

                    /*
                     * IF: order is not "too" new
                     * AND: order has the right status (configured for new orders in backend)
                     */
                    if ($orderDate < $timeLimit &&
                        $orderStatus == $newStatus
                    ) {
                        if($cancelStatus == "canceled")
                        {
                            $order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true)->save();
                        }
                        else if($cancelStatus == "holded") {
                            $order->setState(Mage_Sales_Model_Order::STATE_HOLDED, true)->save();
                        }
                    }
                }
        }

        return $this;
    }
}