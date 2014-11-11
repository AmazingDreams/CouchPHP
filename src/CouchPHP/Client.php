<?php

namespace AD\CouchPHP;

use \AD\CouchPHP\Utils\API\Request;

class Client {

	/**
	 * Default config variables
	 */
	private static $_defaults = array(
		'protocol' => 'http',
		'host'     => 'localhost',
		'port'     => 5984,
	);

	/**
	 * The config
	 */
	protected $_config;

	/**
	 * Constructs the Client
	 *
	 * @param  $config  Array of config variables
	 */
	public function __construct(array $config)
	{
		$this->setConfig($config);
	}

	/**
	 * Creates the database
	 *
	 * @param   String   Database name
	 * @return  boolean  Creation successful or not
	 */
	public function createDatabase()
	{
		$response = Request::factory('PUT', $this->getFullUrl())
			->send();

		return $response->getStatusCode() == 201;
	}

	/**
	 * Check if a database exists
	 *
	 * @param   String   Database name
	 * @return  boolean  Database exists or not
	 */
	public function databaseExists()
	{
		// We have to use a GET request because curl hangs
		// when you use HEAD: http://sourceforge.net/p/curl/bugs/694/
		$response = Request::factory('GET', $this->getFullUrl())
			->send();

		return $response->getStatusCode() == 200;
	}

	/**
	 * Get the DSN
	 *
	 * @return  String  DSN
	 */
	public function getDSN()
	{
		$dsn = array_merge(self::$_defaults, $this->_config);

		$url = strtr('<protocol>://<host>:<port>', array(
			'<protocol>' => $dsn['protocol'],
			'<host>'     => $dsn['host'],
			'<port>'     => $dsn['port'],
		));

		return $url;
	}

	/**
	 * Get the full url including db name
	 *
	 * @return  String  Full url
	 */
	public function getFullUrl()
	{
		return $this->getDSN().'/'.$this->_config['dbname'];
	}

	/**
	 * Removes the database
	 *
	 * @return  boolean  Succesful or not
	 */
	public function removeDatabase()
	{
		$response = Request::factory('DELETE', $this->getFullUrl())
			->send();

		return $response->getStatusCode() == 200;
	}

	/**
	 * Set the configuration to use
	 *
	 * @chainable
	 * @param   $config  Array of config variables
	 * @return  this
	 */
	public function setConfig(array $config)
	{
		if( ! isset($config['dbname'])) {
			throw new \Exception('Database name MUST be present');
		}

		$this->_config = $config;

		return $this;
	}

}
