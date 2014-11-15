<?php

namespace AD\CouchPHP\ODM;

class Document {

	/**
	 * Flag whether we loaded the document or not
	 */
	protected $_loaded = FALSE;

	/**
	 * Array of values
	 */
	protected $_values;

	/**
	 * Holds the manager
	 */
	protected $_manager;

	/**
	 * Initializes a new instance of document
	 *
	 * @param  $manager  Optional ODM manager
	 */
	public function __construct($manager = NULL)
	{
		$this->_manager = $manager;
	}

	/**
	 * Convert this object to a json string
	 *
	 * @return  String  JSON representation
	 */
	public function toJSON()
	{
		return json_encode($this->_values);
	}

	/**
	 * Reads a json object or a json string
	 *
	 * @chainable
	 * @param   $json  String or object
	 * @return  this
	 */
	public function readJSON($json)
	{
		if(is_string($json))
			$json = json_decode($json);

		// Check if parsing worked
		if($json === NULL)
			throw new \Exception('Invalid JSON');

		foreach($json as $key => $value)
		{
			$this->_values[$key] = $value;
		}
	}

	/**
	 * Get this documents manager
	 *
	 * @return  The manager
	 */
	public function getManager()
	{
		return $this->_manager;
	}

	/**
	 * Check whether this document is loaded
	 *
	 * @return  Loaded or not
	 */
	public function isLoaded()
	{
		return $this->_loaded;
	}

	/**
	 * Save this document to the database
	 *
	 * @param   $location  Can be an instance of a Manager
	 * @return  boolean    Stored succesfully or not
	 */
	public function save(Manager $manager = NULL)
	{
		if($manager === NULL)
			$manager = $this->_manager;

		if(isset($manager))
			return $manager->store($this);

		throw new \Exception("I don't know where to store myself");
	}

	/**
	 * Set the manager
	 *
	 * @param  $manager  Manager
	 */
	public function setManager(Manager $manager)
	{
		$this->_manager = $manager;
	}

	/**
	 * Set the loaded state
	 *
	 * @chainable
	 * @param  $value  TRUE or FALSE
	 * @return this
	 */
	public function setLoaded($value)
	{
		$this->_loaded = $value;
		return $this;
	}

	/**
	 * Get value
	 *
	 * @param   $key   Key to get
	 * @return  Mixed
	 */
	public function __get($key)
	{
		if (isset($this->_values[$key]))
			return $this->_values[$key];

		return NULL;
	}

	/**
	 * Set value
	 *
	 * @param  $key    Key to set
	 * @param  $value  Value to set
	 */
	public function __set($key, $value)
	{
		$this->_values[$key] = $value;
	}

	/**
	 * Uses $this->toJSON();
	 *
	 * @return  String  JSON representation
	 */
	public function __toString()
	{
		return $this->toJSON();
	}

}
