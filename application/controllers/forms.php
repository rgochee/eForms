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
<<<<<<< HEAD
			$this->load->view('header', array('-'.'title'=>$form->name));
=======
			$this->load->view('header', array('title'=>$form->name));
			//Load the view which Avi creates
>>>>>>> 14251996e6b56f9461b10dad990537ba1a17f695
			$this->load->view('fill_form', array('form'=>$form));
			$this->load->view('footer');
		}
		else if ($requestType == 'POST') //Else if post request, adding filled form data
		{
			$form = $this->formsdb->getForm($form_id);
			$user = $this->input->post('user');
			$values = $this->input->post('fields');
			foreach($values as $fid => $value)
			{
				//Do answer validation here
			}
			$instanceid = $this->formsdb->addFilledForm($form->id, $user, $values);
			
			$this->load->view('header', array('title'=>'- Success!'));
			$this->load->view('fill_success');
			$this->load->view('footer');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/forms.php */