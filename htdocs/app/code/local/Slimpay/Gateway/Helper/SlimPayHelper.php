<?php

require_once __DIR__ . './../hapiclient/autoload.php';

use HapiClient\Http;
use HapiClient\Hal;

class SlimPayHelper {

    private $_apiUrl;
    private $_apiProfileUrl = "https://api.slimpay.net/alps/v1";
    private $_appName;
    private $_appSecret;
    private $_creditorReference;

    private $_hapiClient = null;

    private $_error = "";

    function __construct($apiUrl, $appName, $appSecret, $creditorReference) {
        $this->_apiUrl = $apiUrl;
        $this->_appName = $appName;
        $this->_appSecret = $appSecret;
        $this->_creditorReference = $creditorReference;

        $this->_hapiClient = $this->getHapiClient();

    }

    public function getLastErrorMessage() {
        return $this->_error;
    }

    /**
    * This function will check if all entries of $data are conform to $params.
    * $params is an array containing all keys and value type that $data should contains
    * Example : $params = array("test1" => "integer", "test" => "string")
    * If data contains a string with the key "test" and an integer with the key "test1"
    * Then the function will not do anything, however, if one key is missing, or one
    * type is not respected then the function will throw an Exception
    **/
    private function verifyRequiredParams(array $params, array $data) {

        $msg = [
            "missing_key" => [],
            "wrong_type"  => []
        ];
        foreach($params as $key => $value) {
            if(!array_key_exists($key, $data)) {
                array_push($msg["missing_key"], $key);
                continue;
            }
            if(is_array($value)) {
                $type = $value["type"];
                $values = $value["values"];
                if(gettype($data[$key]) != $type || !in_array($data[$key], $values, true)) {
                    array_push($msg["wrong_type"], [$key => ["value" => $data[$key], "not_in" => $values]]);
                    continue;
                }
            }
            if(gettype($data[$key]) != $value && !is_array($value)) {
                array_push($msg["wrong_type"], [$key => ["type_is" => gettype($data[$key]), "should_be" => $value]]);
            }
        }
        if(count($msg["missing_key"]) > 0 || count($msg["wrong_type"]) > 0) {
            throw new Exception('Function parameters are invalid : '. json_encode($msg));
        }
    }

    public function getHapiClient() {

        if($this->_hapiClient != null) {
            return $this->_hapiClient;
        }

        try {
            return new Http\HapiClient(
                $this->_apiUrl,
                '/',
                $this->_apiProfileUrl,
                new Http\Auth\Oauth2BasicAuthentication(
                    '/oauth/token',
                    $this->_appName,
                    $this->_appSecret
                )
            );
        } catch(HapiClient\Exception\HttpException $e) {
            $this->_error = $this->process_http_exception($e);
            return false;
        } catch(Exception $e) {
            $this->_error = $e->getMessage();
        }
    }

    /**
    * Create a direct debit
    * @return \HapiClient\Hal\Resource if successful, false otherwise
    * @param $data, an array of client data, must contain the following keys :
    *               ('mandateRum', 'amount', 'label')
    *        note : the value of label may be null, amount is a float or a double
    **/
    public function createDirectDebitWithMandate(array $data) {

        $this->verifyRequiredParams([
            'mandateRum'    => "string",
            'amount'        => "double"
        ], $data);

        extract($data, EXTR_PREFIX_ALL, "slmp");

        if(!isset($slmp_label))
            $slmp_label = $this->_creditorReference;
        if(!isset($slmp_description))
            $slmp_description = '';

        $requestData = new Http\JsonBody([
            'amount' => $slmp_amount,
            'label'  => $slmp_label,
            'paymentReference' => $slmp_description,
            'creditor' => [
                'reference' => $this->_creditorReference
            ],
            'mandate' => [
                'rum' => $slmp_mandateRum,
                'standard' => 'SEPA'
            ]
        ]);

        $hapiClient = $this->getHapiClient();
        $res = $hapiClient->getEntryPointResource();

        $rel = new Hal\CustomRel('https://api.slimpay.net/alps#create-direct-debits');
        $follow = new Http\Follow($rel, 'POST', null, $requestData);
        try {
            return $hapiClient->sendFollow($follow, $res);
        } catch(HapiClient\Exception\HttpException $e) {
            $this->_error = $this->process_http_exception($e);
            return false;
        } catch(Exception $e) {
            $this->_error = $e->getMessage();
        }
    }

    /**
    * Create a recurrent direct debit
    * @return \HapiClient\Hal\Resource if successful, false otherwise
    * @param $data, an array of data, must contains the following keys :
    *                   ('clientReference', 'amount', 'label', 'reference'
    *                    'frequency', 'maxSddNumber', 'activated')
    *        note : 'frequency' is a string among 'daily|weekly|monthly|bimonthly|trimonthly|semiyearly|yearly'
    *               amount is a float/double and maxSddNumber is an integer
    *               activated is a boolean
    **/
    public function createRecurrentDirectDebit(array $data) {

        $this->verifyRequiredParams([
            "clientReference"   => "string",
            "amount"            => "double",
            "label"             => "string",
            "reference"         => "string",
            "frequency"         => [
                "type"          => "string",
                "values"        => ["daily", "weekly", "monthly", "bimonthly",
                                        "trimonthly", "semiyearly", "yearly"]
            ],
            "maxSddNumber"      => "integer",
            "activated"         => "boolean"
        ], $data);

        extract($data, EXTR_PREFIX_ALL, "slmp");

        $jsonBody = new Http\JsonBody([
                'creditor' => [
                    'reference' => $this->_creditorReference
                ],
                'subscriber' => [
                    'reference' => $slmp_clientReference
                ],
                'amount'        => $slmp_amount,
                'label'         => $slmp_label,
                'reference'     => $slmp_reference,
                'frequency'     => $slmp_frequency,
                'maxSddNumber'  => $slmp_maxSddNumber,
                'activated'     => $slmp_activated
        ]);

        $hapiClient = $this->getHapiClient();
        $res = $hapiClient->getEntryPointResource();

        $rel = new Hal\CustomRel('https://api.slimpay.net/alps#create-recurrent-direct-debits');
        $follow = new Http\Follow($rel, 'POST', null, $requestData);
        try {
            return $hapiClient->sendFollow($follow, $res);
        } catch(HapiClient\Exception\HttpException $e) {
            $this->_error = $this->process_http_exception($e);
            return false;
        } catch(Exception $e) {
            $this->_error = $e->getMessage();
        }
    }

    /**
    * Create a mandate
    * @return \HapiClient\Hal\Resource if successful, false otherwise
    * @param $data, an array of client data, must contain the following keys :
    *               ('clientReference', 'honorificPrefix', 'familyName', 'givenName',
    *                'email', 'telephone', 'companyName', 'street1', 'street2', 'city'
    *                'postalCode', 'country')
    *        note : the value of companyName may be null
    **/
    public function createMandate(array $data, $withRum = true) {

        $this->verifyRequiredParams([
            "clientReference"   => "string",
            "honorificPrefix"   => "string",
            "familyName"        => "string",
            "givenName"         => "string",
            "email"             => "string",
            "telephone"         => "string",
            "street1"           => "string",
            "street2"           => "string",
            "city"              => "string",
            "postalCode"        => "string",
            "country"           => "string"
        ], $data);

        extract($data, EXTR_PREFIX_ALL, "slmp");

        $jsonBody = new Http\JsonBody ([
            'started' => true,
            'creditor' => [
                'reference' => $this->_creditorReference
            ],
            'subscriber' => [
                'reference' => $slmp_clientReference
            ],
            'items' => [
                [
                    'type' => 'signMandate',
                    'autoGenReference' => $withRum,
                    'mandate' => [
                        'standard' => 'SEPA',
                        'signatory' => [
                            'honorificPrefix' => $slmp_honorificPrefix,
                            'familyName' => $slmp_familyName,
                            'givenName' => $slmp_givenName,
                            'email' => $slmp_email,
                            'telephone' => $slmp_telephone,
                            'companyName' => $slmp_companyName ?: null,
                            'organizationName' => null,
                            'billingAddress' => [
                                'street1' => $slmp_street1,
                                'street2' => $slmp_street2,
                                'city' => $slmp_city,
                                'postalCode' => $slmp_postalCode,
                                'country' => $slmp_country
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $hapiClient = $this->getHapiClient();
        $res = $hapiClient->getEntryPointResource();

        try {
            $follow = new Http\Follow('https://api.slimpay.net/alps#create-orders', 'POST', null, $jsonBody);
            return $hapiClient->sendFollow($follow, $res);
        } catch(HapiClient\Exception\HttpException $e) {
            $this->_error = $this->process_http_exception($e);
            return false;
        } catch(Exception $e) {
            $this->_error = $e->getMessage();
        }
    }

    /**
    * Create an order, no validation is done on $data
    * @param array $data, an array of data as specified in the documentation
    * @return \HapiClient\Hal\Resource if successful, false otherwise
    **/
    public function createOrder(array $data) {
        $hapiClient = $this->getHapiClient();
        $res = $hapiClient->getEntryPointResource();
        $body = new Http\JsonBody($data);
        try {
            $follow = new Http\Follow('https://api.slimpay.net/alps#create-orders', 'POST', null, $body);
            return $hapiClient->sendFollow($follow, $res);
        } catch(HapiClient\Exception\HttpException $e) {
            $this->_error = $this->process_http_exception($e);
            return false;
        } catch(Exception $e) {
            $this->_error = $e->getMessage();
        }
    }

    /**
    * Retrieve an order.
    * @return \HapiClient\Hal\Resource if successful, false otherwise
    * @param $ref, the order reference
    **/
    public function getOrder($ref) {

        $urlVariables = [
            'creditorReference' => $this->_creditorReference,
            'reference' => $ref
        ];

        $hapiClient = $this->getHapiClient();
        $res = $hapiClient->getEntryPointResource();
        try {
            $follow = new Http\Follow('https://api.slimpay.net/alps#get-orders', 'GET', $urlVariables);
            return $hapiClient->sendFollow($follow, $res);
        } catch(HapiClient\Exception\HttpException $e) {
            $this->_error = $this->process_http_exception($e);
            return false;
        } catch(Exception $e) {
            $this->_error = $e->getMessage();
        }
    }

    /**
    * Retrieve a Direct Debit,
    * @param $id , the ID of the Direct Debit
    * @return \HapiClient\Hal\Resource if successful, false otherwise
    **/
    public function getDirectDebit($id) {
        $hapiClient = $this->getHapiClient();
        $res = $hapiClient->getEntryPointResource();
        try {
            $follow = new Http\Follow('https://api.slimpay.net/alps#get-direct-debits', 'GET', ['id' => $id]);
            return $hapiClient->sendFollow($follow, $res);
        } catch(HapiClient\Exception\HttpException  $e) {
            $this->_error = $this->process_http_exception($e);
            return false;
        } catch(Exception $e) {
            $this->_error = $e->getMessage();
        }
    }

    /**
    * Retrieve a Recurrent Direct Debit,
    * @param $id , the ID of the Recurrent Direct Debit
    * @return \HapiClient\Hal\Resource if successful, false otherwise
    **/
    public function getRecurrentDirectDebit($id) {
        $hapiClient = $this->getHapiClient();
        $res = $hapiClient->getEntryPointResource();
        try {
            $follow = new Http\Follow('https://api.slimpay.net/alps#get-recurrent-direct-debits', 'GET', ['id' => $id]);
            return $hapiClient->sendFollow($follow, $res);
        } catch(HapiClient\Exception\HttpException  $e) {
            $this->_error = $this->process_http_exception($e);
            return false;
        } catch(Exception $e) {
            $this->_error = $e->getMessage();
        }
    }

    /**
    * Retrieve a mandate.
    * @return \HapiClient\Hal\Resource if successful, false otherwise
    * @param $data, an array that contains one of the following keys :
    *           'id' -> the mandate ID
    *           'rum'-> the mandate unique reference
    *        note : the mandate will be retrieved by the first key found in the array
    **/
    public function getMandate(array $data) {

        if($data[0] == 'id') {
            $requestData = [
                'creditorReference' => $this->_creditorReference,
                'id' => $data[1]
            ];
        } elseif($data[0] == 'rum') {
            $requestData = [
                'creditorReference' => $this->_creditorReference,
                'rum' => $data[1]
            ];
        } else {
            throw new Exception('Missing parameter : "id" or "rum"');
        }

        $hapiClient = $this->getHapiClient();
        $res = $hapiClient->getEntryPointResource();

        $rel = new Hal\CustomRel('https://api.slimpay.net/alps#get-mandates');
        $follow = new Http\Follow($rel, 'GET', $requestData);
        try {
            return $hapiClient->sendFollow($follow, $res);
        } catch (HapiClient\Exception\HttpException  $e) {
            $this->_error = $this->process_http_exception($e);
            return false;
        } catch(Exception $e) {
            $this->_error = $e->getMessage();
        }
    }

    /**
	 * Process HTTP exceptions
	 * @return 	The message from the response if existing.
	 *			The HTTP status code and reason phrase otherwise.
	 */
	private function process_http_exception(HapiClient\Exception\HttpException $e) {
		$message = null;

		try {
			$body = $e->getResponse()->json();

			if (isset($body['message']))
				$message = $body['message'] . (isset($body['code']) ? ' (' . $body['code'] . ')' : '');
		} catch (\Exception $e) { }

		if (!$message)
			$message = $e->getMessage() . '<br />' . $e->getResponse()->getBody();

		return $message;
	}
}

?>
