<?php
/**
 * Blackbird Agency
 *
 * @category    Blackbird
 * Date: 02/07/17
 * Time: 12:00
 * @copyright   Copyright (c) 2017 Blackbird Agency. (http://black.bird.eu)
 * @author Jérémie Poisson (hello@bird.eu)
 */

abstract class Blackbird_Monetico_Model_Method_Abstract extends Mage_Payment_Model_Method_Abstract
{

    protected $_formBlockType = 'blackbird_monetico/form';

    // Monetico return codes of payment
    const RETURN_CODE_ACCEPTED = 'paiement';
    const RETURN_CODE_TEST_ACCEPTED = 'payetest';
    const RETURN_CODE_ERROR = 'Annulation';

    /**
     * Payment Method features
     * @var bool
     */
    protected $_canAuthorize                = true;
    protected $_canCapture                  = true;

    // Order instance
    protected $_order = null;

    /**
     *  Return Monetico protocol version
     *
     *  @param    none
     *  @return	  string Protocol version
     */
    protected function getVersion()
    {
        if (!$version = $this->getConfigData('version')) {
            $version = '1.2open';
        }

        return $version;
    }

    /**
     *  Returns Target URL
     *
     *  @return	  string Target URL
     */
    public function getMoneticoUrl()
    {
        return Mage::getStoreConfig('payment/monetico_onetime/environment') == 'sandbox'
            ? 'https://p.monetico-services.com/test/paiement.cgi'
            : 'https://p.monetico-services.com/paiement.cgi';
    }

    /**
     *  Return back URL
     *
     *  @return	  string URL
     */
    protected function getReturnURL()
    {
        return $this->getErrorURL();
    }

    /**
     *  Return URL for Monetico success response
     *
     *  @return	  string URL
     */
    abstract protected function getSuccessURL();

    /**
     *  Return URL for Monetico failure response
     *
     *  @return	  string URL
     */
    abstract protected function getErrorURL();

    /**
     *  Return URL for Monetico notify response
     *
     *  @return	  string URL
     */
    abstract protected function getNotifyURL();

    /**
     * Get quote model
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        if (!$this->_quote) {
            $quoteId = Mage::getSingleton('checkout/session')->getLastQuoteId();
            $this->_quote = Mage::getModel('sales/quote')->load($quoteId);
        }
        return $this->_quote;
    }

    /**
     * Get real order ids
     *
     * @return string
     */
    public function getOrderList()
    {
        if ($this->getQuote()->getIsMultiShipping()) {
            return Mage::getSingleton('checkout/session')->getRealOrderIds();
        } else {
            return $this->getOrder()->getRealOrderId();
        }
    }

    /**
     *  Return Order Place Redirect URL
     *
     *  @return	  string Order Redirect URL
     */
    abstract public function getOrderPlaceRedirectUrl();

    public function getAmount()
    {
        if ($this->getQuote()->getIsMultiShipping()) {
            $amount = $this->getQuote()->getBaseGrandTotal();
        } else {
            $amount = $this->getOrder()->getBaseGrandTotal();
        }

        return sprintf('%.2f', $amount);
    }

    /**
     *  Return Form Fields for request to Monetico
     *
     *  @return	  array Array of hidden form fields
     */
    public function getFormFields()
    {
        $session = Mage::getSingleton('checkout/session');

        $order = $this->getOrder();

        if (!($order instanceof Mage_Sales_Model_Order)) {
            Mage::throwException($this->_getHelper()->__('Cannot retrieve order object'));
        }

        $description = $this->getConfigData('description') ? $this->getConfigData('description') : Mage::helper('blackbird_monetico')->__('Order %s', $this->getOrderList());

        $fields = array(
            'version' => $this->getVersion(),
            'TPE' => $this->getConfigData('tpe_number'),
            'date' => date('d/m/Y:H:i:s'),
            //'date_commande' => date('d/m/Y'),
            'montant' => $this->getAmount() . $order->getBaseCurrencyCode(),
            //'montant_a_capturer' => $this->getAmount() . $order->getBaseCurrencyCode(),
            //'montant_deja_capture' => 0 . $order->getBaseCurrencyCode(),
            //'montant_restant' => 0 . $order->getBaseCurrencyCode(),
            'reference' => $this->getOrderList(),
            'texte-libre' => $description,
            'lgue' => $this->_getLanguageCode(),
            'societe' => $this->getConfigData('site_code'),
            'url_retour' => $this->getNotifyURL(),
            'url_retour_ok' => $this->getSuccessURL(),
            'url_retour_err' => $this->getErrorURL(),
            'bouton' => 'ButtonLabel'
        );

        if (((int)$this->getVersion()) >= 3) {
            $fields['mail'] = $order->getCustomerEmail();
        }

        $fields['MAC'] = $this->_getMAC($fields);

        return $fields;
    }

    /**
     *  Prepare string for MAC generation
     *
     *  @param    none
     *  @return	  string MAC string
     */
    protected function _getMAC($data)
    {
        if (((int)$this->getVersion()) >= 3) {
            $string = sprintf('%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*', $data['TPE'], $data['date'], $data['montant'], $data['reference'], $data['texte-libre'], $data['version'], $data['lgue'], $data['societe'], $data['mail'], "", "", "", "", "", "", "", "", "");
        } else {
            $string = sprintf('%s*%s*%s*%s*%s*%s*%s*%s*', $data['TPE'], $data['date'], $data['montant'], $data['reference'], $data['texte-libre'], $data['version'], $data['lgue'], $data['societe']);
        }

        return $this->_CMCIC_hmac($string);
    }

    /**
     *  Return MAC string on basis of Monetico response data
     *
     *  @param    none
     *  @return	  string MAC
     */
    public function getResponseMAC($data)
    {
        if (((int)$this->getVersion()) >= 3) {
            if (!array_key_exists('numauto', $data)) {
                $data['numauto'] = "";
            }
            if (!array_key_exists('motifrefus', $data)) {
                $data['motifrefus'] = "";
            }
            if (!array_key_exists('originecb', $data)) {
                $data['originecb'] = "";
            }
            if (!array_key_exists('bincb', $data)) {
                $data['bincb'] = "";
            }
            if (!array_key_exists('hpancb', $data)) {
                $data['hpancb'] = "";
            }
            if (!array_key_exists('ipclient', $data)) {
                $data['ipclient'] = "";
            }
            if (!array_key_exists('originetr', $data)) {
                $data['originetr'] = "";
            }
            if (!array_key_exists('veres', $data)) {
                $data['veres'] = "";
            }
            if (!array_key_exists('pares', $data)) {
                $data['pares'] = "";
            }

            $string = sprintf('%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*', $data['TPE'], $data['date'], $data['montant'], $data['reference'], $data['texte-libre'], '3.0', $data['code-retour'], $data['cvx'], $data['vld'], $data['brand'], $data['status3ds'], $data['numauto'], $data['motifrefus'], $data['originecb'], $data['bincb'], $data['hpancb'], $data['ipclient'], $data['originetr'], $data['veres'], $data['pares']);
        } else {
            $string = sprintf('%s%s+%s+%s+%s+%s+%s+%s+', $data['retourPLUS'], $data['TPE'], $data['date'], $data['montant'], $data['reference'], $data['texte-libre'], $this->getVersion(), $data['code-retour']);
        }
        return strtoupper($this->_CMCIC_hmac($string));
    }

    /**
     *  Return SHA key
     *
     *  @param    none
     *  @return	  string SHA key
     */
    protected function _getSHAKey()
    {
        return $this->getConfigData('sha_key');
    }

    /**
     *  Return merchant key
     *
     *  @param    none
     *  @return	  string Merchant key
     */
    protected function _getKey()
    {
        return $this->getConfigData('key');
    }

    /**
     *  Return encrypted key
     *
     *  @param    none
     *  @return	  string encrypted key
     */
    protected function _getKeyEncrypted()
    {
        $key = $this->getConfigData('private_key');

        if($key == "")
        {
            $key = Mage::getStoreConfig('payment/monetico_onetime/private_key');
        }

        $key = Mage::helper('core')->decrypt($key);

        $hexStrKey = substr($key, 0, 38);
        $hexFinal = "" . substr($key, 38, 2) . "00";

        $cca0 = ord($hexFinal);

        if ($cca0 > 70 && $cca0 < 97) {
            $hexStrKey .= chr($cca0 - 23) . substr($hexFinal, 1, 1);
        } else {
            if (substr($hexFinal, 1, 1) == "M") {
                $hexStrKey .= substr($hexFinal, 0, 1) . "0";
            } else {
                $hexStrKey .= substr($hexFinal, 0, 2);
            }
        }

        return pack("H*", $hexStrKey);
    }

    /**
     *  Select old and new system
     *
     *  @param    string
     *  @return	  string encrypted key
     */
    protected function _CMCIC_hmac($string)
    {
        if ($this->getConfigData('private_key')) {
            return $this->_CMCIC_hmac_KeyEncrypted($string);
        } else {
            return $this->_CMCIC_hmac_KeyPassphrase($string);
        }
    }

    /**
     * Return MAC string for payment authentification
     * new system
     *
     *  @param    string
     *  @return	  string encrypted key
     */
    protected function _CMCIC_hmac_KeyEncrypted($string)
    {
        $key = $this->_getKeyEncrypted();

        $length = 64; // block length for SHA1
        if (strlen($key) > $length) {
            $key = pack("H*", sha1($key));
        }

        $key = str_pad($key, $length, chr(0x00));
        $ipad = str_pad('', $length, chr(0x36));
        $opad = str_pad('', $length, chr(0x5c));
        $k_ipad = $key ^ $ipad;
        $k_opad = $key ^ $opad;

        return sha1($k_opad . pack("H*", sha1($k_ipad . $string)));
    }

    /**
     * Return MAC string for payment authentification
     * old HMAC system
     *
     *  @param    string
     *  @return	  string encrypted key
     */
    protected function _CMCIC_hmac_KeyPassphrase($string)
    {
        $pass = $this->_getSHAKey();
        $k1 = pack("H*", sha1($this->_getSHAKey()));
        $l1 = strlen($k1);
        $k2 = pack("H*", $this->_getKey());
        $l2 = strlen($k2);
        if ($l1 > $l2) {
            $k2 = str_pad($k2, $l1, chr(0x00));
        } elseif ($l2 > $l1) {
            $k1 = str_pad($k1, $l2, chr(0x00));
        }

        return strtolower($this->_hmacSHA1($k1 ^ $k2, $string));
    }

    /**
     *  Old HMAC SHA system
     *
     *  @param    string
     *  @return	  string encrypted key
     */
    protected function _hmacSHA1($key, $string)
    {
        $length = 64; // block length for SHA1
        if (strlen($key) > $length) {
            $key = pack("H*", sha1($key));
        }

        $key = str_pad($key, $length, chr(0x00));
        $ipad = str_pad('', $length, chr(0x36));
        $opad = str_pad('', $length, chr(0x5c));
        $k_ipad = $key ^ $ipad;
        $k_opad = $key ^ $opad;

        return sha1($k_opad . pack("H*", sha1($k_ipad . $string)));
    }

    /**
     * Return authorized languages by Monetico
     *
     * @param	none
     * @return	array
     */
    protected function _getAuthorizedLanguages()
    {
        $languages = array();

        foreach (Mage::getConfig()->getNode('global/payment/monetico_payment/languages')->asArray() as $data) {
            $languages[$data['code']] = $data['name'];
        }

        return $languages;
    }

    /**
     * Return language code to send to Monetico
     *
     * @param	none
     * @return	String
     */
    protected function _getLanguageCode()
    {
        // Store language
        $language = strtoupper(substr(Mage::getStoreConfig('general/locale/code'), 0, 2));

        // Authorized Languages
        $authorized_languages = $this->_getAuthorizedLanguages();

        if (count($authorized_languages) === 1) {
            $codes = array_keys($authorized_languages);
            return $codes[0];
        }

        if (array_key_exists($language, $authorized_languages)) {
            return $language;
        }

        // By default we use language selected in store admin
        return $this->getConfigData('language');
    }

    /**
     *  Transaction successful or not
     *
     *  @param    none
     *  @return	  boolean
     */
    public function isSuccessfulPayment($returnCode)
    {
        return in_array($returnCode, array(self::RETURN_CODE_ACCEPTED, self::RETURN_CODE_TEST_ACCEPTED));
    }

    /**
     *  Output success response and stop the script
     *
     *  @param    none
     *  @return	  void
     */
    public function generateSuccessResponse()
    {
        return $this->getSuccessResponse();
    }

    /**
     *  Output failure response and stop the script
     *
     *  @param    none
     *  @return	  void
     */
    public function generateErrorResponse()
    {
        return $this->getErrorResponse();
    }

    /**
     *  Return response for Monetico success payment
     *
     *  @param    none
     *  @return	  string Success response string
     */
    public function getSuccessResponse()
    {
        if (((int)$this->getVersion()) >= 3) {
            $response = array(
                'version=2',
                'cdr=0'
            );
        } else {
            $response = array(
                'Pragma: no-cache',
                'Content-type : text/plain',
                'Version: 1',
                'OK'
            );
        }

        return implode("\n", $response) . "\n";
    }

    /**
     *  Return response for Monetico failure payment
     *
     *  @param    none
     *  @return	  string Failure response string
     */
    public function getErrorResponse()
    {
        if (((int)$this->getVersion()) >= 3) {
            $response = array(
                'version=2',
                'cdr=1'
            );
        } else {
            $response = array(
                'Pragma: no-cache',
                'Content-type : text/plain',
                'Version: 1',
                'Document falsifie'
            );
        }

        return implode("\n", $response) . "\n";
    }

    public function getSuccessfulPaymentMessage($postData)
    {
        $msg = Mage::helper('blackbird_monetico')->__('Payment accepted by Monetico');

        if (((int)$this->getVersion()) >= 3 && array_key_exists('numauto', $postData)) {
            $msg .= "<br />" . Mage::helper('blackbird_monetico')->__('Number of authorization: %s', $postData['numauto']);
            $msg .= "<br />" . Mage::helper('blackbird_monetico')->__('Was the visual cryptogram seized: %s', $postData['cvx']);
            $msg .= "<br />" . Mage::helper('blackbird_monetico')->__('Validity of the card: %s', $postData['vld']);
            $msg .= "<br />" . Mage::helper('blackbird_monetico')->__('Type of the card: %s', $postData['brand']);
            if($postData['status3ds'] >= 1) {
                $msg .= "<br />" . Mage::helper('blackbird_monetico')->__('3DSecure: active (id: %s)', $postData['status3ds']);
            }
        }

        return $msg;
    }

    public function getRefusedPaymentMessage($postData)
    {
        $msg = Mage::helper('blackbird_monetico')->__('Payment refused by Monetico');

        if (((int)$this->getVersion()) >= 3 && array_key_exists('motifrefus', $postData)) {
            $msg .= "<br />" . Mage::helper('blackbird_monetico')->__('Motive for refusal: %s', $postData['motifrefus']);
            $msg .= "<br />" . Mage::helper('blackbird_monetico')->__('Was the visual cryptogram seized: %s', $postData['cvx']);
            $msg .= "<br />" . Mage::helper('blackbird_monetico')->__('Validity of the card: %s', $postData['vld']);
            $msg .= "<br />" . Mage::helper('blackbird_monetico')->__('Type of the card: %s', $postData['brand']);
            if($postData['status3ds'] >= 1 && $postData['status3ds'] <= 4) {
                $msg .= "<br />" . Mage::helper('blackbird_monetico')->__('3DSecure: active');
            }
        }

        return $msg;
    }

    /**
     * Return every fields needed to use the Cofidis option
     */
    public function getCofidisOptionsData($fields)
    {
        // Init data
        $order = $this->getOrder();
        $options = '';
        $billingAddress = $order->getBillingAddress();
        $street = $billingAddress->getStreet();

        // Split the address in two: main and extra street
        if (isset($street[0])) {
            $firstStreet = $street[0];
            unset($street[0]);
        } else {
            $firstStreet = '';
        }
        $extraStreet = implode(' ', $street);

        // Build the Customer Data
        $customerData = array(
            'nomclient' => $billingAddress->getLastname(),
            'prenomclient' => $billingAddress->getFirstname(),
            'adresseclient' => $firstStreet,
            'codepostalclient' => $billingAddress->getPostcode(),
            'villeclient' => strtoupper($billingAddress->getCity()),
            'paysclient' => $billingAddress->getCountryId(),
        );

        if ($extraStreet) {
            $customerData['complementadresseclient'] = $extraStreet;
        }

        if ($billingAddress->getTelephone()) {
            if (preg_match('/^(\+33|0)(6|7)/', $billingAddress->getTelephone())) {
                $customerData['telephonemobileclient'] = $billingAddress->getTelephone();
            } else {
                $customerData['telephonefixeclient'] = $billingAddress->getTelephone();
            }
        }

        // If customer has extra useful data
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customer = Mage::getSingleton('customer/session')->getCustomer();

            if ($customer->getData('gender')) {
                $customerData['civiliteclient'] = $customer->getData('gender');
            }

            if ($customer->getData('dob')) {
                $dob = new DateTime($customer->getData('dob'));
                $customerData['datenaissanceclient'] = $dob->format('Ymd');
            }
        }

        // Convert all values to hex
        foreach ($customerData as $key => $value) {
            $valHex = unpack('H*', Mage::helper('blackbird_monetico')->escapeXssInUrl($value));
            $options .= $key . '=' . array_shift($valHex) . '&';
        }

        $options = substr($options, 0, -1); // Remove last character (&)

        $fields['options'] = $options; // Set options (in hex)

        return $fields;
    }

}
