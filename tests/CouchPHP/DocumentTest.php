<?php

namespace AD\CouchPHP\Tests\DocumentTest;

use \AD\CouchPHP\Client;
use \AD\CouchPHP\ODM\Document;
use \AD\CouchPHP\ODM\Manager;

class DocumentTest extends \PHPUnit_Framework_Testcase {

	public function testReadJson()
	{
		$document = new Document();
		$document->readJSON('{ "a" : "b", "c" : "d" }');
		$this->assertEquals('b', $document->a);
		$this->assertEquals('d', $document->c);

		$document = new Document();
		$document->readJSON(json_decode('{ "a" : "b", "c" : "d" }'));
		$this->assertEquals('b', $document->a);
		$this->assertEquals('d', $document->c);
	}

	public function testSave()
	{
		// Save with Manager as parameter
		$manager  = new MockManager(new MockClient());
		$document = new Document();
		$this->assertTrue($document->save($manager));
		$this->assertTrue($manager->storeWasCalled);

		// Save with setManager
		$manager  = new MockManager(new MockClient());
		$document = new Document();
		$document->setManager($manager);
		$this->assertTrue($document->save());
		$this->assertTrue($manager->storeWasCalled);

		// Save with manager in constructor
		$manager  = new MockManager(new MockClient());
		$document = new Document($manager);
		$this->assertTrue($document->save());
		$this->assertTrue($manager->storeWasCalled);
	}

}

class MockManager extends Manager {

	public $storeWasCalled = FALSE;

	public function store($document)
	{
		$this->storeWasCalled = TRUE;
		return TRUE;
	}

}

class MockClient extends Client {

	// Hide weird constructor
	public function __construct() {}

}
