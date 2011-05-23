<?php

define('RULE_SEPARATOR', '|');
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
	
	public static function serializeValueArray($values) 
	{
		return implode(OPT_SEPARATOR, $values);
	}
	
	public static function deserializeValueString($valueStr)
	{
		return explode(OPT_SEPARATOR, $valueStr);
	}
}

class FieldOptions {
	private $rules;
	private $values;

	public function __construct($data = NULL)
	{
		$this->rules = array();
		$this->values = array();
		
		if (isset($data))
		{
			$this->setOptions($data);
		}
	}
	public function __toString()
	{
		return $this->getSerialized();
	}
	
	public function setOptions($data)
	{
		// treat all options as a rule
		if (is_array($data))
		{
			$this->rules = $data;
		}
		else
		{
			// assume it's a string
			$data = (string) $data;
			
			// split by separator
			$this->rules = explode(RULE_SEPARATOR, $data);
		}
		$this->values = array_unique($this->values);
		
		// separate value options from the other rules
		foreach ($this->rules as $index=>$rule)
		{
			if (preg_match_all('/valid_value\[(.*?)\]/', $rule, $matches))
			{
				$this->setValueOptions($matches[1][0]);
				unset($this->rules[$index]);
				break;
			}
		}
	}
	
	public function setValueOptions($data)
	{
		if (is_array($data))
		{
			// NOTE: may want to validate data format?
			$this->values = $data;
		}
		else
		{
			// assume it's a string
			$data = (string) $data;
			
			// split by separator
			$this->values = Field::deserializeValueString($data);
		}
		$this->values = array_unique($this->values);
	}
	
	public function getValueOptions()
	{
		return $this->values;
	}
	
	public function getSerialized()
	{
		$rulesStr = implode(RULE_SEPARATOR, $this->rules);
		$valuesStr = Field::serializeValueArray($this->values);
		
		if (empty($rulesStr))
		{
			return $valuesStr;
		}
		else if (empty($valuesStr))
		{
			return $rulesStr;
		}
		else
		{
			return $rulesStr . RULE_SEPARATOR . 'valid_value[' . $valuesStr . ']';
		}
	}
}

class FieldTypes  {
	
	private static $types = array(
		'textbox' => 'Text', 
		'checkbox' => 'Checkbox', 
		'radio' => 'Radio', 
		'dropdown' => 'Dropdown list'
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