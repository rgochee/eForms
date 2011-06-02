<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('array_count'))
{
	function array_count($fieldname)
	{
		$OBJ =& _get_validation_object();
		return $OBJ->array_count($fieldname);
	}
}

if ( ! function_exists('set_values'))
{
	function set_values($fieldname)
	{
		$OBJ =& _get_validation_object();
		return $OBJ->set_values($fieldname);
	}
}

