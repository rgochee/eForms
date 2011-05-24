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
			
			// fool CI form validation to think form has been submitted
			$_POST['username'] = 'Nick';
			$this->form_validation->set_rules('username', 'Username', '');
			
			foreach($form->fields as $field)
			{
				//Adding rules for validation based on field attributes
				$rules = array();
				if ($field->required)
				{
					$rules[] = 'required';
				}
				
				$input_name = 'fields['.$field->id.']';
				if ($field->type === "checkbox")
				{
					$input_name .= '[]';
				}
				
				$this->form_validation->set_rules($input_name, $field->name, implode('|', $rules));
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
				if (isset($fields[$field->id]))
				{
					if (is_array($fields[$field->id]))
					{
						$value = Field::serializeValueArray($fields[$field->id]);
					}
					else
					{
						$value = $fields[$field->id];
					}
				}
				else
				{
					$value = '';
				}
				
				$datas[$field->id] = $value;
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