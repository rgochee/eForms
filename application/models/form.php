<?php

define('OPT_SEPARATOR', '``');

class Form {
	var $id;
	var $name;
	var $description;
	var $user;
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

	public function __constuct($data = NULL)
	{
		if ($data)
		{
			setOptions($data);
		}
		else
		{
			$options = array();
		}
	}
	public function __toString()
	{
		return getSerialized();
	}
	
	public function addOption($opt)
	{
		if (!in_array($opt, $options))
		{
			$options[] = $opt;
		}
	}
	public function removeOption($opt)
	{
		$key = array_search($opt, $options);
		if ($key !== NULL)
		{
			unset($options[$key]);
		}
	}
	public function setOptions($data)
	{
		if (is_array($data))
		{
			// NOTE: may want to validate data format?
			$options = $data;
		}
		else
		{
			// assume it's a string
			$data = (string) $data;
			
			// split by separator
			$options = explode(OPT_SEPARATOR, $data);
		}
		$options = array_unique($options);
	}
	public function getOptions()
	{
		return $options;
	}
	public function getSerialized()
	{
		return implode(OPT_SEPARATOR, $options);
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