<?php

namespace AD\CouchPHP\Utils\API;

class Response {

	protected $_content;

	protected $_contentType;

	protected $_statusCode;

	protected $_request;

	public function getContent($convert = TRUE)
	{
		if($convert AND $this->_contentType = 'application/json')
			return json_decode($this->_content);

		return $this->_content;
	}

	public function getContentType()
	{
		return $this->_contentType;
	}

	public function getStatusCode()
	{
		return $this->_statusCode;
	}

	public function getRequest()
	{
		return $this->_request;
	}

	public function setContent($content)
	{
		$this->_content = $content;
	}

	public function setContentType($contentType)
	{
		$this->_contentType = $contentType;
	}

	public function setStatusCode($statusCode)
	{
		$this->_statusCode = $statusCode;
	}

	public function setRequest($request)
	{
		$this->_request = $request;
	}

}
