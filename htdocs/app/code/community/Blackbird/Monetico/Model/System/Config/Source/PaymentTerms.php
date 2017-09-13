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

class Blackbird_Monetico_Model_System_Config_source_PaymentTerms
{
    const TWO_TERMS = '2';
    const THREE_TERMS = '3';
    const FOUR_TERMS = '4';

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::TWO_TERMS,
                'label' => __('%s', self::TWO_TERMS),
            ],
            [
                'value' => self::THREE_TERMS,
                'label' => __('%s', self::THREE_TERMS),
            ],
            [
                'value' => self::FOUR_TERMS,
                'label' => __('%s', self::FOUR_TERMS),
            ],
        ];
    }
}