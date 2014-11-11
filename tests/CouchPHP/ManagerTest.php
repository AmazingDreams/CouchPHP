<?php

namespace AD\CouchPHP\Tests\ManagerTest;

use \AD\CouchPHP\ODM\Manager;
use \AD\CouchPHP\Client as CouchClient;

class ManagerTest extends \PHPUnit_Framework_Testcase {

	protected $_client;

	public function setUp()
	{
		$this->_client = new CouchClient(array(
			'host'     => 'localhost',
			'port'     => 5984,
			'protocol' => 'http',
			'dbname'   => 'test',
		));
		$this->_client->createDatabase();

		$manager = new Manager($this->_client);
		$manager->getNewDocument();
	}

	public function testCreateDocument()
	{
		$manager = new Manager(new MockClient);

		$document = $manager->getNewDocument();

		$this->assertEquals($manager, $document->getManager());
	}

	public function testStoreDocument()
	{
		$manager = new Manager($this->_client);
		$document = $manager->getNewDocument();

		$document->a = 'a';
		$document->b = 'b';

		$manager->store($document);
	}

	public function testFind()
	{

	}

	public function tearDown()
	{

	}

}

class MockClient extends CouchClient {

	// Hide weird constructor
	public function __construct() {}

}
