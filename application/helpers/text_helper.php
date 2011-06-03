<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('set_text'))
{
	function set_text(&$obj, $default="") 
	{
		if (isset($obj)) 
		{
			return $obj;
		}
		else
		{
			return $default;
		}
	}
}

if ( ! function_exists('nice_form_uri'))
{
	function nice_form_uri($uri, $form_id, $form_name)
	{
		$urlName = strtolower($form_name);
		$urlName = str_replace(' ', '-', $urlName);
		$urlName = preg_replace('/[^A-Za-z0-9\-]*/', '', $urlName);
		return $uri . $form_id . '/' . $urlName;
	}
}

if ( ! function_exists('get_title'))
{
	function get_title() {
		$CI =& get_instance();
		return $CI->getTitle();
	}
}

/* End of file text_helper.php */
/* Location: ./application/helpers/text_helper.php */