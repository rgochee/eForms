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
	
	public function _unavailable_name($form_name)
	{
		$this->load->library('FormsDB');
		if ($this->formsdb->formExists($form_name))
		{
			$this->form_validation->set_message('_unavailable_name', 'The form name already exists.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function _fieldTypeCheck($type) {
		$this->load->model('form');
		$this->form_validation->set_message('_fieldTypeCheck', 'Invalid field type.');
		return FieldTypes::isValid($type);
	}
	
	public function _separatorCheck($option)
	{
		$this->form_validation->set_message('_separatorCheck', 'Options must not contain "' . OPT_SEPARATOR . '".');
		return strpos($option, OPT_SEPARATOR) === FALSE;
	}

	public function create()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		$requestType = $this->input->server('REQUEST_METHOD');
		if ($requestType == 'GET') //If get request, creating new form
		{
			$this->load->view('header', array('title'=>'- Create Form'));
			$this->load->view('create_form', array('numFields'=>1));
		}
		else if ($requestType == 'POST') //Else if post request, adding newly created form to database
		{
			//Form attribute validation
			$this->form_validation->set_rules('name', 'Form name', 'required|trim|callback__unavailable_name');
			$this->form_validation->set_rules('description', 'Form description', 'trim');

			$numFields = count($this->input->post('fields'));
			
			$name = $this->input->post('name');
			$description = $this->input->post('description');
			$user = $this->input->post('user');

			$fields = array();
			foreach ($this->input->post('fields') as $i=>$fieldAttributes) //for each array of field info in the array of fields
			{
				//Field attribute validation for each field attribute input
				$this->form_validation->set_rules('fields['.$i.'][name]', 'field name', 'trim|required');
				$this->form_validation->set_rules('fields['.$i.'][description]', 'help text', 'trim');
				$this->form_validation->set_rules('fields['.$i.'][type]', 'type', 'callback__fieldTypeCheck');
				$this->form_validation->set_rules('fields['.$i.'][required]', 'field requirement', '');
				$this->form_validation->set_rules('fields['.$i.'][options][]', 'field option', 'trim|callback__separatorCheck');
				
				$field = new Field();
				$field->name = $fieldAttributes['name'];
				$field->type = $fieldAttributes['type'];
				
				$fOptions = new ValueOptions($fieldAttributes['options']);
				$field->options = $fOptions->getSerialized();
				$field->required = isset($fieldAttributes['required']);
				$field->description = $fieldAttributes['description'];
				$fields[] = $field;
			}
			
			if ($this->form_validation->run() == FALSE)	// If some inputs are invalid
			{
				$this->load->view('header', array('title'=>'- Create Form'));
				$this->load->view('create_form', array('numFields'=>$numFields));
				$this->load->view('footer');
				return;
			}
			
			//Use createForm to add the form to the database
			$this->load->library('FormsDB');
			$form_id = $this->formsdb->createForm($name, $description, $user, $fields);
			
			$this->form_validation->set_message('name', 'BLAH');
			if (!$form_id)	// If the insert failed
			{
				$this->load->view('header', array('title'=>'- Create Form'));
				$this->load->view('create_form', array('numFields'=>$numFields));
				$this->load->view('footer');
				return;
			}
			
			//If there are no errors
			$this->load->view('header', array('title'=>'- Success!'));
			$this->load->view('create_success', array('form_name'=>$name, 'form_id'=>$form_id));
		}

		$this->load->view('footer');
	}

	public function data($form_id)
	{
		$this->load->view('header');

		$this->load->database();

		$this->db->from('Forms')->where('form_id',$form_id);
		$form = $this->db->get()->row();
		$form_name = $form->form_name;


		$this->db->from('Fields')->where('form_id',$form_id)->order_by('field_order','asc');
		$query = $this->db->get();
		$fields = array();
		foreach ($query->result() as $field)
		{
			$fields[$field->field_id] = $field->field_name;
		}

		$this->db->from('Filled_Forms')->where('form_id',$form_id);
		$query = $this->db->get();
		$responses = array();
		foreach ($query->result() as $row)
		{
			$this->db->from('Filled_Values')->where('instance_id', $row->instance_id);
			$subquery = $this->db->get();
			$response = array();
			foreach ($subquery->result() as $filled_field)
			{
				$field_name = $fields[$filled_field->field_id];
				$response[$field_name] = str_replace(OPT_SEPARATOR, ', ', $filled_field->value);
			}
			array_push($responses, $response);
		}

		$this->load->view('form_data', array('form_name'=>$form_name, 'fields'=>$fields, 'data'=>$responses));
		$this->load->view('footer');
	}

	public function changeMode($admin)
	{
		if ($admin === "admin")
		$this->session->set_userdata('admin', true);
		else if ($admin === "user")
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