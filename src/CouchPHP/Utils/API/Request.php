<?php

namespace AD\CouchPHP\Utils\API;

class Request {

	/**
	 * Convenience method for creating a request
	 *
	 * @param  $method   HTTP Method to use
	 * @param  $url      URL to call
	 * @param  $params   URL Parameters to use
	 *
	 * @return  Request  A new request
	 */
	public static function factory($method, $url, array $params = array())
	{
		$request = new self();
		$request->setMethod($method);
		$request->setUrl($url);
		$request->setUrlParams($params);

		return $request;
	}

	/**
	 * The HTTP Method to use
	 */
	protected $_method = 'GET';

	/**
	 * The URL to call
	 */
	protected $_url;

	/**
	 * The data to include in the request
	 */
	protected $_data;

	/**
	 * The content type to request
	 */
	protected $_contentType = 'application/json';

	/**
	 * The params to add to the request
	 */
	protected $_urlParams = array();

	/**
	 * Get the HTTP method
	 *
	 * @return  String  Method
	 */
	public function getMethod()
	{
		return $this->_method;
	}

	/**
	 * Get the url to call
	 *
	 * @return  String  Url to call
	 */
	public function getUrl()
	{
		$url = $this->_url;

		if(count($this->getUrlParams()))
			$url .= '?'.http_build_query($this->getUrlParams());

		return $url;
	}

	/**
	 * Get the data
	 *
	 * @return  Mixed  Data
	 */
	public function getData()
	{
		return $this->_data;
	}

	/**
	 * Get the content type
	 *
	 * @return  String  Content type
	 */
	public function getContentType()
	{
		return $this->_contentType;
	}

	/**
	 * Get the url parameters
	 *
	 * @return  Array  Url parameters
	 */
	public function getUrlParams()
	{
		return $this->_urlParams;
	}

	/**
	 * Send the request
	 *
	 * @return  Response  Response object
	 */
	public function send()
	{
		return $this->_send();
	}

	/**
	 * Set the data
	 *
	 * @chainable
	 * @param  $data  Mixed value
	 * @return this
	 */
	public function setData($data)
	{
		if(is_object($data) OR is_array($data))
			$data = json_encode($data);

		$this->_data = $data;
		return $this;
	}

	/**
	 * Set the HTTP Method
	 *
	 * @chainable
	 * @param  $method  HTTP Method to use
	 * @return this
	 */
	public function setMethod($method)
	{
		if( ! $this->_isValidMethod($method))
			throw new \Exception("Invalid method '$method'");

		$this->_method = $method;
		return $this;
	}

	/**
	 * Set the URL to call
	 *
	 * @chainable
	 * @param  $url  URL to use
	 * @return this
	 */
	public function setUrl($url)
	{
		$this->_url = $url;
		return $this;
	}

	/**
	 * Set the Content Type
	 *
	 * @chainable
	 * @param  $contentType  Content type to use
	 * @return this
	 */
	public function setContentType($contentType)
	{
		$this->_contentType = $contentType;
		return $this;
	}

	/**
	 * Set the url parameters
	 *
	 * @chainable
	 * @param  $params  The url parameters
	 * @return this
	 */
	public function setUrlParams(array $params)
	{
		$this->_urlParams = $params;
	}

	/**
	 * Check whether given method is valid
	 *
	 * @param   $method  HTTP Method
	 * @return  boolean  Valid method or not
	 */
	protected function _isValidMethod($method)
	{
		return in_array($method, array('GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'COPY'));
	}

	/**
	 * Send a request and returns the Response
	 *
	 * @return  Response
	 */
	protected function _send()
	{
		$curl      = curl_init($this->getUrl());
		$headers   = array();
		$headers[] = 'Accept: application/json, text/html, text/plain, */*';
		$headers[] = 'Content-Type: '.$this->getContentType();

		if($this->getMethod() == 'COPY')
			$headers[] = 'Destination: '.$this->getData();
		else
			curl_setopt($curl, CURLOPT_POSTFIELDS, $this->getData());

		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->getMethod());
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_HEADER, TRUE);

		$content     = curl_exec($curl);
		$statusCode  = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
		curl_close($curl);

		$response = new Response;
		$response->setRequest($this);
		$response->setContent($content);
		$response->setStatusCode($statusCode);
		$response->setContentType($contentType);

		return $response;
	}

}
