<?php

use \AD\CouchPHP\Client as CouchClient;

class ClientTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var  Array  Config
	 */
	private $_config = array(
		'host'     => 'localhost',
		'port'     => 5984,
		'protocol' => 'http',
		'dbname'   => 'test',
	);

	public function testDSN()
	{
		// Client has default values
		$client = new CouchClient(array('dbname' => 'test'));
		$this->assertEquals('http://localhost:5984', $client->getDSN());

		// Set some config values
		$client = new CouchClient(array('protocol' => 'https', 'host' => 'example.com', 'dbname' => 'test'));
		$this->assertEquals('https://example.com:5984', $client->getDSN());
		// Test with host to something
		$client->setConfig(array('host' => 'example.com', 'dbname' => 'test'));
		$this->assertEquals('http://example.com:5984', $client->getDSN());

		// Set all variables to something
		$client->setConfig(array('protocol' => 'something', 'host' => 'example.com', 'port' => 10, 'dbname' => 'test'));
		$this->assertEquals('something://example.com:10', $client->getDSN());
	}

	public function testFullUrl()
	{
		$client = new CouchClient($this->_config);

		$this->assertEquals('http://localhost:5984/test', $client->getFullUrl());
	}

	public function testCreateDatabase()
	{
		$client = new CouchClient($this->_config);

		$this->assertTrue($client->createDatabase());
	}

	public function testDatabaseExists()
	{
		$client = new CouchClient($this->_config);
		$client->createDatabase();

		$this->assertTrue($client->databaseExists());

		$client = new CouchClient(array('dbname' => 'nonexistent') + $this->_config);
		$this->assertFalse($client->databaseExists());
	}

	public function testRemoveDatabase()
	{
		$client = new CouchClient($this->_config);
		$client->createDatabase();

		$this->assertTrue($client->removeDatabase());
	}

	public function tearDown()
	{
		$client = new CouchClient($this->_config);
		$client->removeDatabase();
	}

}
