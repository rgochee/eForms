<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forms extends EF_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('text');
		$this->load->helper('url');
	}
	
	public function index()
	{
		$this->setTitle('Index');
		$this->load->view('header');
		$this->load->helper('file');
		$newsFile = read_file('NEWS');
		$newsItems = explode("\n* ", $newsFile);
		$this->load->view('index', array('NEWS'=>$newsItems[1]));
		$this->load->view('footer');
	}
	
	public function browse($start = 0)
	{
		$per_page = 20;
		$this->load->helper('text_helper');
		$this->load->library('FormsDB');
		$this->load->library('pagination');

		$this->pagination->initialize(array(
			'base_url' => base_url() . '/forms/browse/',
			'total_rows' => $this->formsdb->getNumForms(),
			'per_page' => $per_page
		));

		$data = array('forms' => $this->formsdb->getForms($per_page, $start, FormsDB::SORT_TIME));
		$this->setTitle('Browse Forms');
		$this->load->view('header');
		$this->load->view('forms_list', $data);
		$this->load->view('footer');
	}

	public function search($start = 0)
	{
		$per_page = 20;
		$this->load->helper('text_helper');
		$this->load->library('FormsDB');
		$this->load->library('pagination');

		$this->pagination->initialize(array(
			'base_url' => base_url() . '/forms/browse/',
			'total_rows' => $this->formsdb->getNumForms(),
			'per_page' => $per_page
		));

		$data = array('forms' => $this->formsdb->searchForm($this->input->get('find'), $per_page, $start, FormsDB::SORT_TIME));
		$this->setTitle('Search Results');
		$this->load->view('header');
		$this->load->view('forms_list', $data);
		$this->load->view('footer');
	}

	public function fill($form_id)
	{
		$this->load->library('FormsDB');
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$form = $this->formsdb->getForm($form_id);
		$this->setTitle($form->name);
			
		$requestType = $this->input->server('REQUEST_METHOD');
		if ($requestType == 'GET') //If get request, ready to fill out the form
		{
			$this->load->view('header');
			$this->load->view('fill_form', array('form'=>$form));
			$this->load->view('footer');
		}
		else if ($requestType == 'POST') //Else if post request, adding filled form data
		{
			$user = $this->input->post('user');
			$fields = $this->input->post('fields');
			
			// fool CI form validation to think form has been submitted
			
			foreach($form->fields as $field)
			{
				//Adding rules for validation based on field attributes
				$options = $field->options;
				if ($field->required)
				{
					$options->addRule('required');
				}
				
				
				$input_name = 'fields['.$field->id.']';
				if ($field->type === "checkbox")
				{
					$input_name .= '[]';
				}
				
				if (isset($fields[$field->id]['value']) || $field->required)
				{
					$this->form_validation->set_rules($input_name, $field->name, $options->getSerialized());
				}
			}
			
			if ($this->form_validation->run() == FALSE)	//If validation rules have been violated
			{
				$this->load->view('header');
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
						// need to use set_values
						$value = Field::serializeValueArray($fields[$field->id]);
					}
					else
					{
						$value = set_value('fields['.$field->id.']');
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
			
			$this->setTitle('Success!');
			$this->load->view('header');
			$this->load->view('fill_success');
			$this->load->view('footer');
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/forms.php */
