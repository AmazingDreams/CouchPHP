<?php

namespace AD\CouchPHP\ODM;

use \AD\CouchPHP\Client;

class Manager {

	/**
	 * Holds the default Client
	 */
	protected static $defaultClient;

	/**
	 * Set the default client *
	 * @param  $client  Client to set default
	 */
	public static function setDefaultClient(Client $client)
	{
		self::$defaultClient = $client;
	}

	/**
	 * Get the default client
	 *
	 * @return  \AD\CouchPHP\Client
	 */
	public static function getDefaultClient()
	{
		return self::$defaultClient;
	}

	/**
	 * The couch client this document has
	 */
	protected $_client;

	/**
	 * Initializes the manager
	 */
	public function __construct(Client $client = NULL)
	{
		if($client === NULL)
			$client = self::getDefaultClient();

		$this->setCouchClient($client);
	}

	/**
	 * Get a specific document by ID
	 */
	public function findById($id)
	{
		$document = $this->getNewDocument();
		$response = $this->_client->query('GET', $this->_client->getDBName().'/'.$id);

		if($response->getStatusCode() != 200)
			return $document;

		$document->readJSON($response->getContent());
		$document->setLoaded(TRUE);

		return $document;
	}

	/**
	 * Returns a new document with manager set to this manager
	 *
	 * @return  Document
	 */
	public function getNewDocument()
	{
		$document = new Document();
		$document->setManager($this);

		return $document;
	}

	/**
	 * Set the couch client
	 *
	 * @chainable
	 * @param  $client  Client to set to
	 * @return this
	 */
	public function setCouchClient(Client $client)
	{
		$this->_client = $client;
		return $this;
	}

	/**
	 * Stores the given document in the database
	 *
	 * @return  TRUE on succes or FALSE on failure
	 */
	public function store(Document $document)
	{
		if($document->_id === NULL)
		{
			$document->_id = $this->_client->getUUID(1);
		}

		$response = $this->_client->query('PUT', $this->_client->getDBName().'/'.$document->_id, $document);

		return $response->getStatusCode() == 201 OR $response->getStatusCode() == 202;
	}

}
