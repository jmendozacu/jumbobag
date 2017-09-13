<?php
namespace HapiClient\Http;

class JsonBody implements MessageBody {
	private $json;

	/**
	 * @param $json	mixed	A string, an array or an object representing the JSON body.
	 */
	public function __construct($json) {
		if (!is_array($json) && !is_object($json) && !is_string($query))
			throw new \Exception("JSON body must be a string, an array or an object representing the JSON body ('" . gettype($json) . "' provided).");

		$this->json = $json;
	}

	/**
	 * @return	mixed	A string, an array or an object representing the JSON body.
	 */
	public function getJson() {
		return $this->json;
	}

	/**
	 * The magic setter is overridden to insure immutability.
	 */
    final public function __set($name, $value) { }

	/**
	 * @return	string	The Content-Type header.
						(application/json)
	 */
	public function getContentType() {
		return 'application/json';
	}

	/**
	 * @return	string	The Content-Length header.
	 */
	public function getContentLength() {
		return strlen($this->getContent());
	}

	/**
	 * @return	string	The content.
	 */
	public function getContent() {
		static $json = null;
		if ($json)
			return $json;

		if (is_array($this->json) || is_object($this->json))
			return $json = json_encode($this->json, JSON_UNESCAPED_UNICODE);
		else
			return $json = $this->json;
	}
}
