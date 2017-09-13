<?php
/**
 * Blackbird Agency
 *
 * @category    Blackbird
 * Date: 08/06/17
 * Time: 15:57
 * @copyright   Copyright (c) 2017 Blackbird Agency. (http://black.bird.eu)
 * @author Jérémie Poisson (hello@bird.eu)
 */

class Blackbird_Monetico_Model_ConfigProvider
{
    /**
     * Retrieve the payment URL
     *
     * @param array $params
     * @return string
     */
    public function getPaymentFormAction($environment, array $params = [])
    {
        return sprintf(
            'https://p.monetico-services.com/%spaiement.cgi%s',
            ($environment == 'sandbox') ? 'test/' : '',
            $params ? '?' . http_build_query($params) : ''
        );
    }
}