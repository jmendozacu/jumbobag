<?php
namespace HapiClient\Http;

class UrlEncodedBody implements MessageBody {
	private $query;
	
	/**
	 * @param $query	mixed	A query string or an associative
	 *							array representing a query string.
	 */
	public function __construct($query) {
		if (!is_array($query) && !is_string($query))
			throw new \Exception("URL encoded body must be a query string or an associative
								array representing a query string ('" . gettype($query) . "' provided).");
		
		$this->query = $query;
	}
	
	/**
	 * @return	mixed	A query string or an associative
	 *					array representing a query string.
	 */
	public function getQuery() {
		return $this->query;
	}
	
	/**
	 * The magic setter is overridden to insure immutability.
	 */
    final public function __set($name, $value) { }
	
	/**
	 * @return	string	The Content-Type header.
						(application/x-www-form-urlencoded)
	 */
	public function getContentType() {
		return 'application/x-www-form-urlencoded';
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
		static $query = null;
		
		if ($query)
			return $query;
		
		if (is_array($this->query))
			return $query = http_build_query($this->query);
		else
			return $query = $this->query;
	}
}
