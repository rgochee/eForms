<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('array_count'))
{
	function array_count($fieldname)
	{
		$OBJ =& _get_validation_object();
		return $OBJ->array_count($fieldname);
	}
}

/**
 * Drop-down Menu (form_dropdown with array as 4th argument)
 *
 * @access	public
 * @param	string
 * @param	array
 * @param	string
 * @param	array
 * @return	string
 */
if ( ! function_exists('form_select'))
{
	function form_select($name = '', $options = array(), $selected = array(), $extra = array())
	{
		if (isset($extra['name'])) 
		{
			unset($extra['name']);
		}
		return form_dropdown($name, $options, $selected, _parse_form_attributes($extra, array()));
	}
}