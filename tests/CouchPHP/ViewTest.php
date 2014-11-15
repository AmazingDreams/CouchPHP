<?php

use \AD\CouchPHP\ODM\Manager;
use \AD\CouchPHP\Client as CouchClient;
use \AD\CouchPHP\Views\Collection;
use \AD\CouchPHP\Views\View;

class ViewTest extends PHPUnit_Framework_Testcase {

	protected $_client;

	protected $_manager;

	public function setUp()
	{
		$client = new CouchClient(array(
			'host'     => 'localhost',
			'port'     => 5984,
			'protocol' => 'http',
			'dbname'   => 'test',
		));
		$client->createDatabase();

		$this->_manager = new Manager($client);
	}

	public function testMapFuncCreation()
	{
		$view = new View;
		$view->setMap('emit(null, doc)');

		$this->assertEquals('function(doc) { emit(null, doc) }', $view->getMap());

		$view->setMap('function(doc) { emit(null, doc) }');
		$this->assertEquals('function(doc) { emit(null, doc) }', $view->getMap());
	}

	public function testCreateView()
	{
		$collection = new Collection;
		$collection->setName('test');

		$view = new View;
		$view->setName('test');
		$view->setMap('emit(null, doc)');

		$collection->addView($view);

		$this->assertTrue($this->_manager->store($collection));
	}

}
