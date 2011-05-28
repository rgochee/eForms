<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function tryPrint(&$obj, $default="") 
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

function returnWithDefault(&$obj, $default) 
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

function niceFormUri($uri, $form_id, $form_name)
{
	$urlName = strtolower($form_name);
	$urlName = preg_replace('/[^A-Za-z0-9]/', '', $urlName);
	$urlName = str_replace(' ', '-', $urlName);
	return $uri . $form_id . '/' . $urlName;
}

