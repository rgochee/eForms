<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('text');
		$this->load->helper('url');
		$this->load->model('form');
	}
	
	public function index()
	{
		$this->load->view('header');
		$this->load->view('footer');
	}
	
	public function create()
	{
		$requestType = $this->input->server('REQUEST_METHOD');
		if ($requestType == 'GET')	//If get request, creating new form
		{
			$this->load->view('header', array('title'=>'- Create Form'));
			$this->load->view('create_form');
			$this->load->view('footer');
		}
		else if ($requestType == 'POST')	//Else if post request, adding newly created form to database
		{
			//Insert form attribute validation here?
			$name = $this->input->post('name');
			$description = $this->input->post('description');
			$user = $this->input->post('user');
			
			$fields = array();
			foreach ($this->input->post('fields') as $fieldAttributes)	//for each array of field info in the array of fields
			{
				$field = new Field();
				
				//Some field attribute validation should be inserted for each attribute
				$field->name = $fieldAttributes['name'];
				$field->type = $fieldAttributes['type'];
				foreach ($fieldAttributes['options'] as $options)
				{
					if (strstr($options, OPT_SEPARATOR))
					{
						//Reject option as invalid
					}
				}
				$field->options = implode(OPT_SEPARATOR, $fieldAttributes['options']);
				$field->required = isset($fieldAttributes['required']);
				$field->description = $fieldAttributes['description'];
				$fields[] = $field;
			}
			//Use createForm to add the form to the database
			$this->load->library('FormsDB');
			$form_id = $this->formsdb->createForm($name, $description, $user, $fields);
			
			//what view to show after form is added?
			$this->load->view('header', array('title'=>'- Create Success!'));
			$this->load->view('create_success', array('form_name'=>$name, 'form_id'=>$form_id));
			$this->load->view('footer');
		}
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
	
	public function dbcheck() 
	{
		$this->load->library('FormsDB');
		echo "Connected to db!" . PHP_EOL;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/admin.php */