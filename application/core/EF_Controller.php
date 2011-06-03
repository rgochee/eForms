<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class EF_Controller extends CI_Controller {

	var $title;

	public function __construct() 
	{
		parent::__construct();
		$this->title = "eForms";
	}
	
	public function setTitle($subtitle) 
	{
		$this->title = "eForms - " . $subtitle;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
}

/* End of file EF_Controller.php */
/* Location: ./application/core/EF_Controller.php */