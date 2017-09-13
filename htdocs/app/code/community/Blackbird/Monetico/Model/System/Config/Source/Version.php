<?php
/**
 * Blackbird Agency
 *
 * @category    Blackbird
 * Date: 09/06/17
 * Time: 14:40
 * @copyright   Copyright (c) 2017 Blackbird Agency. (http://black.bird.eu)
 * @author Jérémie Poisson (hello@bird.eu)
 */

class Blackbird_Monetico_Model_System_Config_Source_Version
{
    const V_1_2 = '1.2';
    const V_3_0 = '3.0';

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::V_3_0,
                'label' => __('Version %s', self::V_3_0),
            ],
            [
                'value' => self::V_1_2,
                'label' => __('Version %s', self::V_1_2),
            ],
        ];
    }
}