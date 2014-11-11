<?php

use \AD\CouchPHP\Utils\API\Request;
use \AD\CouchPHP\Utils\API\Response;

class APITest extends PHPUnit_Framework_Testcase {

	public function testUrl()
	{
		$request = new MockRequest();
		$request->setUrl('http://somehost.com/somepath');

		$this->assertEquals('http://somehost.com/somepath', $request->getUrl());

		$request->setUrlParams(array('a' => 'b', 'c' => 'd'));
		$this->assertEquals('http://somehost.com/somepath?a=b&c=d', $request->getUrl());
	}

	public function testResponse()
	{
		$request = new MockRequest();
		$request->setUrl('http://somehost.com/somepath');

		$response = $request->send();

		// Test assert with raw content
		$this->assertEquals('{ mock : \'content\' }', $response->getContent(FALSE));

		// Test assert with processed content
		$this->assertEquals(json_decode('{ mock : \'content\' }'), $response->getContent());

		$this->assertEquals('application/json', $response->getContentType());
		$this->assertEquals(200, $response->getStatusCode());
		$this->assertEquals($request, $response->getRequest());
	}

	public function testAutoConvertData()
	{
		$request = new MockRequest();

		// Test with array
		$request->setData(array('test' => 'test'));
		$this->assertEquals(json_encode(array('test' => 'test')), $request->getData());

		// Test with an object
		$stdClass = new stdClass;
		$stdClass->test = 'test';
		$request->setData($stdClass);
		$this->assertEquals(json_encode($stdClass), $request->getData());

		// Test with a string, strings should not be converted
		$request->setData('some-string-data');
		$this->assertEquals('some-string-data', $request->getData());
	}

}

class MockRequest extends \AD\CouchPHP\Utils\API\Request {

	protected function _send()
	{
		$response = new Response();
		$response->setContentType('application/json');
		$response->setContent('{ mock : \'content\' }');
		$response->setStatusCode(200);
		$response->setRequest($this);

		return $response;
	}

}
