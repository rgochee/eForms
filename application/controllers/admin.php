<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
	}
	
	public function index()
	{
		$this->load->view('header');
		$this->load->view('footer');
	}
	
	public function create()
	{
		$this->load->view('header');
		$this->load->view('create_form');
		$this->load->view('footer');
	}
	
	public function data($id)
	{
		$this->load->view('header');
		$this->load->view('footer');
	}
	
	public function changeMode($admin)
	{
		if($admin === "admin")
			$this->session->set_userdata('admin', true);
		else if($admin === "user")
			$this->session->unset_userdata('admin');
		redirect(base_url() . $this->input->get('next', TRUE));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/admin.php */