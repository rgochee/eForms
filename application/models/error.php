<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('ERROR_UNKNOWN', -1);
define('ERROR_DUPLICATE', -2);
define('ERROR_INVALID_INPUT', -3);

class Error {
	var $type;
	var $message;
}

/* End of file error.php */
/* Location: ./application/models/error.php */