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

class Blackbird_Monetico_Model_System_Config_Source_Environment
{
    const ENVIRONMENT_PRODUCTION    = 'production';
    const ENVIRONMENT_SANDBOX       = 'sandbox';

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => self::ENVIRONMENT_SANDBOX,
                'label' => 'Sandbox',
            ),
            array(
                'value' => self::ENVIRONMENT_PRODUCTION,
                'label' => 'Production'
            )
        );
    }
}