<?php

namespace AD\CouchPHP\Views;

use \AD\CouchPHP\ODM\Document;

class Collection extends Document {

	public function __construct()
	{
		$this->setLanguage('javascript');
	}

	public function addView(View $view)
	{
		if ( ! $view->getName())
			throw new \Exception('View must have a name');

		if ( ! isset($this->views))
			$this->views = array();

		$views = $this->views;
		$views[$view->getName()] = $view->getContents();

		$this->views = $views;
	}

	public function setName($name)
	{
		$this->_id = '_design/'.$name;
	}

	public function setLanguage($language)
	{
		if ( ! in_array($language, array('javascript', 'coffeescript')))
			throw new \Exception("Invalid language $language");

		$this->language = $language;
	}

}
