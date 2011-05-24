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

class ValueOptions {
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
			$this->options = self::deserialize($data);
		}
		$this->options = array_unique($this->options);
	}
	public function getOptions()
	{
		return $this->options;
	}
	public function getSerialized()
	{
		return self::serialize($this->options);
	}
	
	public static function serialize($data)
	{
		$values = implode(OPT_SEPARATOR, $data);
		if (!empty($values))
		{
			return "values[". $values . "]";
		}
		else
		{
			return "";
		}
	}
	public static function deserialize($data)
	{
		// 2nd return for backwards compatibility (should be removed eventually)
		// Possible problems when first option starts with "values["
		if (substr($data, 0, 7) === "values[")
		{
			return explode(OPT_SEPARATOR, substr($data, 7, -1));
		}
		else
		{
			return explode(OPT_SEPARATOR, $data);
		}
	}
}

class FieldTypes  {
	
	private static $types = array(
		'textbox' => 'Text', 
		'textarea' => 'Textarea',
		'checkbox' => 'Checkbox', 
		'radio' => 'Radio', 
		'dropdown' => 'Dropdown list',
		'date' => 'Date'
		);
	
	public static function getValues()
	{
		return array_keys(self::$types);
	}
	
	public static function getArray()
	{
		return self::$types;
	}
	
	public static function isValid($type)
	{
		return array_key_exists($type, self::$types);
	}
	
	public static function asPrettyName($type)
	{
		return self::$types[$type];
	}
}

?>