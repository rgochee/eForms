<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forms extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('text');
		$this->load->helper('url');
	}
	
	public function index()
	{
		$this->load->view('header');
		$this->load->view('footer');
	}
	
	public function browse()
	{
		$this->load->database();
		$forms = $this->db->get('forms');
		$data['forms'] = $forms->result();
		$this->load->view('header');
		$this->load->view('forms_list', $data);
		$this->load->view('footer');
	}
	
	public function fill($form_id)
	{
		$this->load->library('FormsDB');
		$form = $this->formsdb->getForm($form_id);
		$this->load->view('header');
		$this->load->view('footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/forms.php */