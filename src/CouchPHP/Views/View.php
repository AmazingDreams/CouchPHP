<?php

namespace AD\CouchPHP\Views;

class View {

	protected $_name;

	protected $_map;

	protected $_reduce;

	public function getContents()
	{
		$tmp = array();

		if($this->_map)
			$tmp['map'] = $this->_map;

		if($this->_reduce)
			$tmp['reduce'] = $this->_reduce;

		return $tmp;
	}

	public function getName()
	{
		return $this->_name;
	}

	public function getMap()
	{
		return $this->_map;
	}

	public function setName($name)
	{
		$this->_name = $name;
	}

	public function setMap($mapFunc)
	{
		$includesFunc = (strpos($mapFunc, 'function') !== FALSE);

		if( ! $includesFunc)
			$mapFunc = "function(doc) { $mapFunc }";

		$this->_map = $mapFunc;
	}

	public function setReduce($reduceFunc)
	{

	}

}

