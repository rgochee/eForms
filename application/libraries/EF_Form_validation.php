<?php


class EF_Form_validation extends CI_Form_validation {

	function array_count($field)
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
	
	function set_values($field)
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
	
	function valid_values($str, $val)
	{
		$this->set_message('valid_values', 'Invalid value.');
		return TRUE;
	}

}