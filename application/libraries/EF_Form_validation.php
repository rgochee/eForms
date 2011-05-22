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

}