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

class Blackbird_Monetico_Model_System_Config_source_OptionPayment
{
    const COFIDIS_TXCB = '3xcb';
    const COFIDIS_FXCB = '4xcb';
    const PAYPAL = 'paypal';

    /**
     * @var array
     */
    protected $options;

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $options = array();
            $paymentMethods = $this->getOptions();

            foreach ($paymentMethods as $value => $label) {
                $options[] = array(
                    'value' => $value,
                    'label' => $label,
                );
            }

            $this->options = $options;
        }

        return $this->options;
    }

    /**
     * Get the list of the available payment methods
     *
     * @return array
     */
    public function getOptions()
    {
        return array(
            self::COFIDIS_TXCB => __('Cofidis 3xCB Payment'),
            self::COFIDIS_FXCB => __('Cofidis 4xCB Payment'),
            self::PAYPAL => __('PayPal Payment'),
        );
    }
}