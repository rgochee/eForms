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
