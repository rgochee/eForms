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
		$this->load->library('formsdb');
		$data = array('forms' => $this->formsdb->getForms());
		$this->load->view('header', array('title'=>'- Browse Forms'));
		$this->load->view('forms_list', $data);
		$this->load->view('footer');
	}
	
	public function fill($form_id)
	{
		$this->load->library('formsdb');
		
		$requestType = $this->input->server('REQUEST_METHOD');
		if ($requestType == 'GET') //If get request, ready to fill out the form
		{
			$form = $this->formsdb->getForm($form_id);
			$this->load->view('header', array('title'=>$form->name));
			//Load the view which Avi creates
			$this->load->view('footer');
		}
		else if ($requestType == 'POST') //Else if post request, adding filled form data
		{
			$form = $this->formsdb->getForm($form_id);
			$values = $this->input->post('fields');
			foreach($values as $name => $value)
			{
				//Do answer validation here
			}
			$this->formsdb->addFilledForm($form->id, $form->user, $values);
			
			$data = array('forms' => $this->formsdb->getForms());
			$this->load->view('header');
			$this->load->view('forms_list', $data);
			$this->load->view('footer');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/forms.php */