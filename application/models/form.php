<?php

define('OPT_SEPARATOR', '``');

class Form {
	var $id;
	var $name;
	var $description;
	var $user;
	var $time;
	var $disabled;
	var $fields;
}

class Field {
	var $id;
	var $name;
	var $type;
	var $options;
	var $required;
	var $description;
	var $value;
}

class FieldOptions {
	private $options;

	public function __construct($data = NULL)
	{
		if ($data)
		{
			$this->setOptions($data);
		}
		else
		{
			$this->options = array();
		}
	}
	public function __toString()
	{
		return $this->getSerialized();
	}
	
	public function addOption($opt)
	{
		if (!in_array($opt, $this->options))
		{
			$this->options[] = $opt;
		}
	}
	public function removeOption($opt)
	{
		$key = array_search($opt, $this->options);
		if ($key !== NULL)
		{
			unset($this->options[$key]);
		}
	}
	public function setOptions($data)
	{
		if (is_array($data))
		{
			// NOTE: may want to validate data format?
			$this->options = $data;
		}
		else
		{
			// assume it's a string
			$data = (string) $data;
			
			// split by separator
			$this->options = explode(OPT_SEPARATOR, $data);
		}
		$this->options = array_unique($this->options);
	}
	public function getOptions()
	{
		return $this->options;
	}
	public function getSerialized()
	{
		return implode(OPT_SEPARATOR, $this->options);
	}
	
	public static function serialize($data)
	{
		return implode(OPT_SEPARATOR, $data);
	}
	public static function deserialize($data)
	{
		return explode(OPT_SEPARATOR, $data);	
	}
}

?>