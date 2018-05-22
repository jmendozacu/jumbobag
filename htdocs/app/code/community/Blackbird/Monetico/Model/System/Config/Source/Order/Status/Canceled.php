<?php
/**
 * Blackbird Agency
 *
 * @category    Blackbird
 * Date: 09/06/17
 * Time: 00:00
 * @copyright   Copyright (c) 2017 Blackbird Agency. (http://black.bird.eu)
 * @author Jérémie Poisson (hello@bird.eu)
 */

/**
 * Order Statuses source model
 */
class Blackbird_Monetico_Model_System_Config_Source_Order_Status_Canceled extends Blackbird_Monetico_Model_System_Config_Source_Order_Status
{
    /**
     * @var string
     */
    protected $_stateStatuses = array(
        Mage_Sales_Model_Order::STATE_CANCELED,
        Mage_Sales_Model_Order::STATE_HOLDED
    );
}