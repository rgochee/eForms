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
		$this->load->view('header', array('title'=>'- Index'));
		$this->load->helper('file');
		$newsFile = read_file('NEWS');
		$newsItems = explode("\n* ", $newsFile);
		$this->load->view('index', array('NEWS'=>$newsItems[1]));
		$this->load->view('footer');
	}
	
	public function browse()
	{
		$this->load->library('FormsDB');
		$data = array('forms' => $this->formsdb->getForms());
		$this->load->view('header', array('title'=>'- Browse Forms'));
		$this->load->view('forms_list', $data);
		$this->load->view('footer');
	}
	
	public function fill($form_id)
	{
		$this->load->library('FormsDB');
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$requestType = $this->input->server('REQUEST_METHOD');
		if ($requestType == 'GET') //If get request, ready to fill out the form
		{
			$form = $this->formsdb->getForm($form_id);
			$this->load->view('header', array('title'=>'- '.$form->name));
			$this->load->view('fill_form', array('form'=>$form));
			$this->load->view('footer');
		}
		else if ($requestType == 'POST') //Else if post request, adding filled form data
		{
			$form = $this->formsdb->getForm($form_id);
			$user = $this->input->post('user');
			$fields = $this->input->post('fields');
			
			foreach($form->fields as $field)
			{
				//Adding rules for validation based on field attributes
				$rules = array();
				if ($field->required)
				{
					$rules[] = 'required';
				}
				$this->form_validation->set_rules('fields['.$field->id.']', $field->name, implode('|', $rules));
			}
			if ($this->form_validation->run() == FALSE)	//If validation rules have been violated
			{
				$this->load->view('header', array('title'=>'- '.$form->name));
				$this->load->view('fill_form', array('form'=>$form));
				$this->load->view('footer');
				return;
			}
			
			$datas = array();
			foreach($form->fields as $field)
			{
				if (!isset($fields[$field->id]))	//If it wasn't required and isn't filled in
				{
					$fields[$field->id] = '';	//Make the value empty
				}
				$value = $fields[$field->id];
				
				if (is_array($value))	//If the answer is an array of options
				{
					$fOptions = new FieldOptions($value);
					$fields[$field->id] = $fOptions->getSerialized();
				}
				$datas[$field->id] = $fields[$field->id];
			}
			//No errors have occured, data can be inserted successfully
			$instanceid = $this->formsdb->addFilledForm($form->id, $user, $datas);
			
			$this->load->view('header', array('title'=>'- Success!'));
			$this->load->view('fill_success');
			$this->load->view('footer');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/forms.php */