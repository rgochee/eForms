<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends EF_Controller {

	const CREATE = "Create";
	const EDIT = "Edit";

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('text');
		$this->load->helper('url');
		$this->load->model('form');
	}

	public function index()
	{
		redirect('forms/index');
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
		$this->setTitle('Create Form');
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$data = array('action' => Admin::CREATE, 'numFields' => 1);

		$requestType = $this->input->server('REQUEST_METHOD');
		if ($requestType == 'GET') //If get request, creating new form
		{
			$this->load->view('header');
			$this->load->view('create_form', $data);
		}
		else if ($requestType == 'POST') //Else if post request, adding newly created form to database
		{
			//Form attribute validation
			$this->form_validation->set_rules('name', 'Form name', 'required|trim|callback__unavailable_name');
			$this->form_validation->set_rules('description', 'Form description', 'trim');
			
			$data['numFields'] = count($this->input->post('fields'));
			
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
				
				$options = new FieldOptions();
				$options->setValueOptions($fieldAttributes['options']);
				$options->setOptions($fieldAttributes['validation']);
				$field->options = $options;
				$field->required = isset($fieldAttributes['required']);
				$field->description = $fieldAttributes['description'];
				$fields[] = $field;
			}
			
			if ($this->form_validation->run() == FALSE)	// If some inputs are invalid
			{
				$this->load->view('header');
				$this->load->view('create_form', $data);
				$this->load->view('footer');
				return;
			}
			
			//Use createForm to add the form to the database
			$this->load->library('FormsDB');
			$form_id = $this->formsdb->createForm($name, $description, $user, $fields);
			
			if (!$form_id)	// If the insert failed
			{
				$this->load->view('header');
				$this->load->view('create_form', $data);
				$this->load->view('footer');
				return;
			}
			
			//If there are no errors
			$this->setTitle('Success!');
			$this->load->helper('text_helper');
			$this->load->view('header');
			$this->load->view('create_success', array('form_name'=>$name, 'form_id'=>$form_id));
		}

		$this->load->view('footer');
	}
	
	private function _setFieldEditValidation($index)
	{
		$this->form_validation->set_rules('fields['.$index.'][id]', 'field id', 'integer');
		$this->form_validation->set_rules('fields['.$index.'][name]', 'field name', 'trim|required');
		$this->form_validation->set_rules('fields['.$index.'][description]', 'help text', 'trim');
		$this->form_validation->set_rules('fields['.$index.'][type]', 'type', 'callback__fieldTypeCheck');
		$this->form_validation->set_rules('fields['.$index.'][required]', 'field requirement', '');
		$this->form_validation->set_rules('fields['.$index.'][options][]', 'field option', 'trim|callback__separatorCheck');
	}
	
	public function deleteField($form_id, $field_id)
	{
		$this->load->library('FormsDB');
		$field_name = $this->formsdb->disableField($form_id, $field_id);
		echo $field_name . " deleted.";
	}
	
	private function loadFormFromDatabase($form_id, $form)
	{
		$_POST['name'] = $form->name;
		$_POST['description'] = $form->description;
		
		foreach ($form->fields as $i=>$field) 
		{
			// fill with db values
			$_POST['fields'][$i]['id'] = $field->id;
			$_POST['fields'][$i]['name'] = $field->name;
			$_POST['fields'][$i]['description'] = $field->description;
			$_POST['fields'][$i]['type'] = $field->type;
			$_POST['fields'][$i]['required'] = $field->required;
			$_POST['fields'][$i]['options'] = $field->options->getValueOptions();
			$_POST['fields'][$i]['validation'] = $field->options->getRulesString();
		}
	}
	
	// assumes libraries are loaded
	private function processEdits($form_id, $form)
	{
		$fieldsPost = $this->input->post('fields');
		// $i is not necessarily the field id (for new fields)
		foreach ($fieldsPost as $i=>$fieldAttrs)
		{
			$field_id = $fieldAttrs['id'];
			$field = new Field();
			$field->name = $fieldAttrs['name'];
			$field->description = $fieldAttrs['description'];
			$field->type = $fieldAttrs['type'];
			$field->required = isset($fieldAttrs['required']);
			
			$options = new FieldOptions();
			$options->setOptions($fieldAttrs['validation']);
			if ($field->type == "checkbox" ||  $field->type == "dropdown" ||
				$field->type == "radio")
			{
				$options->setValueOptions($fieldAttrs['options']);
			}
			$field->options = $options;
			
			if (empty($field_id))
			{
				$this->formsdb->addField($form_id, $field);
			}
			else
			{
				$this->formsdb->editField($form_id, $field_id, $field);
			}
		}
	}
	
	public function edit($form_id)
	{
		$this->load->library('FormsDB');
		$this->load->library('form_validation');
		$this->load->helper('form');
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$data =  array('action' => Admin::EDIT);
		
		$form = $this->formsdb->getForm($form_id);
		$_POST['validate'] = TRUE; // set_rules do not work without initial POST data
		
		$data['numFields'] = max(count($form->fields), count($this->input->post('fields')));
		$data['form_id'] = $form->id;
		$data['form_name'] = $form->name;
		
		$this->form_validation->set_rules('name', 'Form name', 'required|trim');
		$this->form_validation->set_rules('description', 'Form description', 'trim');
		for ($i=0; $i<$data['numFields']; $i++)
		{
			$this->_setFieldEditValidation($i);
		}
		
		$requestType = $this->input->server('REQUEST_METHOD');
		if ($requestType == "GET")
		{
			// simulates a POST request in order to load data from the database
			$this->loadFormFromDatabase($form_id, $form);
			$this->form_validation->run();
		}
		else if ($this->form_validation->run() && $requestType == "POST")
		{
			// add more sophisticated error handling?
			$this->formsdb->editForm($form_id, $this->input->post('name'), $this->input->post('description'));
			$this->processEdits($form_id, $form);
			$data['success'] = TRUE;
		}
		
		$this->setTitle('Edit Form');
		$this->load->view('header');
		$this->load->view('create_form', $data);
		$this->load->view('footer');
	}
	
	public function _collapseParamedRules($ruleNames)
	{
		$subRules = array();
		foreach ($ruleNames as $ruleName)
		{
			$value = $this->input->post($ruleName);
			if ($value != "")
			{
				$subRules[] = $ruleName . '[' . $value . ']';
			}
		}
		return implode(RULE_SEPARATOR, $subRules);
	}
	
	public function validation() {
		$phoneFormats = array('1 (123) 456-7890', '1 123 456 7890', '1-123-456-7890', '11234567890');
		$charVType = 'char';
		$validationTypes = array(
			$charVType => 'Specific characters',
			'alpha' => 'Alphabet',
			'alpha_numeric' => 'Alphanumeric',
			'valid_email' => 'E-mail',
			'integer' => 'Number',
			'phone_format' => 'Phone Number (US)'
		);
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('formsdb');
		
		// set defaults if unset
		$defaults = array('vtype' => $charVType, 'chars' => 'disallow', 'phone_format' => 0);
		foreach ($defaults as $fname=>$value)
		{
			if (!isset($_POST[$fname])) 
			{
				$_POST[$fname] = $value;
			}
		}
		
		// rules with 1 parameter
		$paramedRules = array('min_length', 'max_length', 'less_than', 'greater_than', 'phone_format');
		
		// set rules
		foreach ($paramedRules as $ruleName)
		{
			$this->form_validation->set_rules($ruleName, $ruleName, '');
		}
		$this->form_validation->set_rules('vtype', 'validation type', '');
		$this->form_validation->set_rules('phone_format', 'character allowance/disallowance', '');
		$this->form_validation->set_rules('chars', 'character allowance/disallowance', '');
		$this->form_validation->set_rules('chars_spec', 'character allowance/disallowance', '');
		
		// parse validation rules
		if ($this->input->post('rules') !== FALSE) 
		{
			$rulesStr = $this->input->post('rules');
			foreach ($paramedRules as $ruleName)
			{
				if (preg_match('/'.$ruleName.'\[([^\]]*)\]/', $rulesStr, $match)) 
				{
					$_POST[$ruleName] = $match[1];
				}
			}
			
			$rules = explode(RULE_SEPARATOR, $rulesStr);
			foreach ($validationTypes as $ruleName=>$niceName)
			{
				if ($ruleName != $charVType && array_search($ruleName, $rules) !== FALSE) {
					$_POST['vtype'] = $ruleName;
					break;
				}
			}
		}
		
		if ($this->form_validation->run() !== FALSE && $this->input->post('rules') === FALSE) 
		{
			$newRuleSet = array();
			$prettyRuleString = "";
			
			$lengthValidation = $this->_collapseParamedRules(array('min_length', 'max_length'));
			if ($lengthValidation !== "") 
			{
				$newRuleSet[] = $lengthValidation;
				if ($this->input->post('min_length') != "")
				{
					$prettyRuleString .= "Must be at least " . $this->input->post('min_length') . " characters. ";
				}
				if ($this->input->post('max_length') != "")
				{
					$prettyRuleString .= "Must be at most " . $this->input->post('max_length') . " characters. ";
				}
			}
			
			switch ($this->input->post('vtype'))
			{
			case $charVType:
				$charList = str_replace(' ', OPT_SEPARATOR, $this->input->post('char_specs'));
				$niceList = str_replace(' ', '", "', $this->input->post('char_specs'));
				switch ($this->input->post('chars'))
				{
				case 'allow':
					$subRules[] = 'contains_only' . '[' . $this->input->post($ruleName) . ']';
					if ($niceList != "")
					{
						$prettyRuleString .= 'Can only contain "' . $niceList . '". ';
					}
					break;
				case 'disallow':
					$subRules[] = 'restricted_chars' . '[' . $this->input->post($ruleName) . ']';
					if ($niceList != "")
					{
						$prettyRuleString .= 'Cannot contain ' . $niceList . '. ';
					}
					else
					{
						$prettyRuleString .= 'Can contain anything. ';
					}
					break;
				}
				break;
			case 'integer':
				$newRuleSet[] = 'integer';
				$newRuleSet[] = $this->_collapseParamedRules(array('greater_than', 'less_than'));
				$prettyRuleString .= 'Must be a number';
				if ($this->input->post('greater_than') != "")
				{
					$prettyRuleString .= ' greater than ' . $this->input->post('greater_than');
				}
				if ($this->input->post('less_than') != "")
				{
					$prettyRuleString .= ' less than ' . $this->input->post('less_than');
				}
				$prettyRuleString .= '. ';
				break;
			case 'phone_format':
				$newRuleSet[] = $this->_collapseParamedRules(array('phone_format'));
				$prettyRuleString .= 'Must be a phone number (normalized as ' . $phoneFormats . '). ';
				break;
			default:
				$asIsRules = array(
					'alpha' => 'Can only contain the alphabet. ', 
					'alpha_numeric' => 'Can only contain alphanumeric characters. ', 
					'valid_email' => 'Must be a valid email. ');
				
				if (array_search($this->input->post('vtype'), array_keys($asIsRules)) !== FALSE)
				{
					$newRuleSet[] = $this->input->post('vtype');
					$prettyRuleString .= $asIsRules[$this->input->post('vtype')];
				}
			}
			
			$uglyRuleString = str_replace('"', "\\\"", implode('|', $newRuleSet));
			$prettyRuleString = str_replace('"', "\\\"", $prettyRuleString);
			printf('{"rules": "%s", "pretty": "%s"}', $uglyRuleString, $prettyRuleString);
			return;
		}
		
		$this->load->view('validation_form', array(
			'phoneFormats' => $phoneFormats,
			'validationTypes' => $validationTypes
		));
	}

	public function data($form_id)
	{
		$this->load->view('header');
		
		$this->load->database();

		$this->db->from('Forms')->where('form_id',$form_id);
		$form = $this->db->get()->row();
		$form_name = $form->form_name;

		$this->db->from('Fields')->where(array('form_id'=>$form_id,'field_order >'=>-1))->order_by('field_order','asc');
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
			$response['time_submitted'] = $row->time;
			array_push($responses, $response);
		}

		$this->load->helper('text_helper');
		$this->load->view('form_data', array('form_id'=>$form_id, 'form_name'=>$form_name, 'fields'=>$fields, 'data'=>$responses));
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