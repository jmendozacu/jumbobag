<?php
namespace HapiClient\Http;

interface MessageBody {
	/**
	 * @return	string	The Content-Type header.
	 */
	public function getContentType();
	
	/**
	 * @return	string	The Content-Length header.
	 */
	public function getContentLength();
	
	/**
	 * @return	string	The content.
	 */
	public function getContent();
}
