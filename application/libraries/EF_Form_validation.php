<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class EF_Form_validation extends CI_Form_validation {

	public function array_count($field)
	{
		if (isset($this->_field_data[$field]) && is_array($this->_field_data[$field]['postdata']))
		{
			return count($this->_field_data[$field]['postdata']);
		}
		else
		{
			return 1;
		}
	}
	
	public function set_values($field)
	{
		if (isset($this->_field_data[$field]) && is_array($this->_field_data[$field]['postdata']))
		{
			return $this->_field_data[$field]['postdata'];
		}
		else
		{
			return array();
		}
	}
	
	public function valid_values($val, $validValues)
	{
		$this->set_message('valid_values', $val . ' is an invalid value for %s.');
		$validValuesArray = Field::deserializeValueString($validValues);
		return array_search($val, $validValuesArray) !== FALSE;
	}
	
	public function phone_format($val, $format)
	{
		$phoneFormats = array('1 (123) 456-7890', '1 123 456 7890', '1-123-456-7890', '11234567890');
		
		// remove all formatting except the number
		$stripped = preg_replace('/[^0-9]+/', '', $val);
		$length = strlen($stripped);
		if ($length != 7 && $length != 10 && $length != 11)
		{
			$this->set_message('phone_format', 'Invalid US phone number.');
			return FALSE;
		}
		
		if ($format == 3)	// 11234567890
		{
			return $stripped;
		}
		
		// split into parts
		$parts = array();
		$parts['back'] = substr($stripped, -4);
		$parts['front'] = substr($stripped, -7, 3);
		if ($length > 7)
		{
			$parts['area'] = substr($stripped, -10, 3);
		}
		if ($length > 10)
		{
			$parts['intl'] = $stripped[0];
		}
		
		// rebuild string
		if ($format == 2)	// 1-123-456-7890
		{
			return implode('-', array_reverse($parts));
		}
		else if ($format == 1)	// 1 123 456 7890
		{
			return implode(' ', array_reverse($parts));
		}
		else // 1 (123) 456-7890
		{
			$formatted = $parts['front'] . '-' . $parts['back'];
			if (isset($parts['area'])) 
			{
				$formatted = '(' . $parts['area'] . ') ' . $formatted;
			}
			if (isset($parts['intl'])) 
			{
				$formatted = $parts['intl'] . ' ' . $formatted;
			}
			return $formatted;
		}
	}

}

/* End of file EF_Form_validation.php */
/* Location: ./application/libraries/EF_Form_validation.php */