<?php

define('RULE_SEPARATOR', '|');
define('OPT_SEPARATOR', '``');
define('VALIDATION_FN', 'valid_values');

class Form {
	var $id;
	var $name;
	var $description;
	var $user;
	var $time_created;
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
	var $order;
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
	
	public function addRule($rule)
	{
		if (!in_array($rule, $this->rules))
		{
			$this->rules[] = $rule;
		}
	}
	
	// generally used when retrieving options from the database
	// but can also be used to set rules in bulk
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
			if (preg_match_all('/'.VALIDATION_FN.'\[(.*?)\]/', $rule, $matches))
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
	
	public function getRulesString()
	{
		$last = array_pop($this->rules);
		if (!empty($last))
		{
			$this->rules[] = $last;
		}
		return implode(RULE_SEPARATOR, $this->rules);
	}
	
	private function getValuesRule()
	{
		$valuesStr = Field::serializeValueArray($this->values);
		if (empty($valuesStr))
		{
			return "";
		}
		else
		{
			return VALIDATION_FN.'['.$valuesStr.']';
		}
	}
	
	public function getPrettyRules()
	{ 
		// TODO: Add handler for "specific characters"
		
		$result = "";
		foreach ($this->rules as $rule)
		{
			switch ($rule)
			{
			case 'alpha':
				$result .= "Can only contain the alphabet. ";
				break;
			case 'alpha_numeric':
				$result .= "Can only contain alphanumeric characters. ";
				break;
			case 'valid_email':
				$result .= "Must be a valid email. ";
				break;
			case 'integer':
				$result .= "Must be a number. ";
				break;
			}
			
			$paramedRules = array(
				'min_length' => "Must be at least %s characters. ", 
				'max_length' => "Must be at most %s characters. ", 
				'greater_than' => "Number must be greater than %s. ", 
				'less_than' => "Number must be less than %s. ", 
				'phone_format' => "Must be a phone number. "
			);
			
			foreach ($paramedRules as $ruleName=>$prettyStr)
			{
				if (preg_match('/'.$ruleName.'\[([^\]]*)\]/', $rule, $match)) 
				{
					$arg = $match[1];
					$result .= sprintf($prettyStr, $arg);
				}
			}
		}
		return $result;
	}
	
	public function getSerialized()
	{
		$rulesStr = $this->getRulesString();
		$valuesStr = $this->getValuesRule();
		
		if (empty($valuesStr))
		{
			return $rulesStr;
		}
		else if (empty($rulesStr))
		{
			return $valuesStr;
		}
		else
		{
			return $rulesStr.RULE_SEPARATOR.$valuesStr;
		}
	}
}

class FieldTypes  {

	const TEXT = 'textbox';
	const TEXTAREA = 'textarea';
	const CHECKBOX = 'checkbox';
	const RADIO = 'radio';
	const DROPDOWN = 'dropdown';
	const DATE = 'date';
	
	private static $types = array(
		TEXT => 'Text', 
		TEXTAREA => 'Textarea',
		CHECKBOX => 'Checkbox', 
		RADIO => 'Radio', 
		DROPDOWN => 'Dropdown list',
		DATE => 'Date'
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