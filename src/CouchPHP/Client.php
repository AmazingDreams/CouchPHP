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
		$response = $this->query('PUT', $this->getDBName());

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
		$response = $this->query('GET', $this->getDBName());

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
		return $this->getDSN().'/'.$this->getDBName();
	}

	/**
	 * Get the database name
	 *
	 * @return  String  Database name
	 */
	public function getDBName()
	{
		return $this->_config['dbname'];
	}

	/**
	 * Get a UUID
	 *
	 * @param   $num  Number of UUIDS to return, defaults to 1
	 * @return  Single UUID or array of UUIDs
	 */
	public function getUUID($num = 1)
	{
		$response = $this->query('GET', '_uuids', array(), array('count' => $num));

		if ( $response->getStatusCode() != 200)
			return FALSE;

		$json = $response->getContent();

		if($num == 1)
			return $json->uuids[0];

		return $json->uuids;
	}


	/**
	 * Removes the database
	 *
	 * @return  boolean  Succesful or not
	 */
	public function removeDatabase()
	{
		$response = $this->query('DELETE', $this->getDBName());

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

	/**
	 * Run a query against the DB
	 *
	 * @param  $method  HTTP Method
	 * @param  $path    Path to query
	 * @param  $data    Optional data
	 * @param  $data    Optional url parameters
	 */
	public function query($method, $path, $data = array(), array $urlParams = array())
	{
		// Check if path starts with '/' and add it if it doesn't
		if($path[0] != '/')
			$path = '/'.$path;

		return Request::factory($method, $this->getDSN().$path)
			->setData($data)
			->setUrlParams($urlParams)
			->send();
	}

}
