<?php

class FormsDB {
	var $CI;
	
	const SORT_TIME = 0x1;
	const SORT_NAME = 0x2;
	const SHOW_DISABLED = 0x100;
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->model('form');
	}
	
	function createForm($name, $description, $user, $fields)
	{
		// insert form
		$formData = array(
					'form_name' => $name,
					'form_description' => $description,
					'user' => $user,
					'time_created' => time()
					);
		$this->CI->db->insert('Forms', $formData);
		$form_id = $this->CI->db->insert_id();
		
		if ($this->CI->db->affected_rows() == 0)
		{
			$error = 'Insert to Forms failed: ' . $this->CI->db->_error_message();
			log_message('error', $error);
			return FALSE;
		}
		
		// insert $field info
		$db_fields = array();
		$order = 0;
		foreach ($fields as $field)
		{
			$db_fields[] = array(
						'form_id' => $form_id,
						'field_name' => $field->name,
						'field_type' => $field->type,
						'field_options' => $field->options->getSerialized(),
						'field_required' => $field->required,
						'field_description' => $field->description,
						'field_order' => $order
						);
			++$order;
		}
		
		$this->CI->db->insert_batch('Fields', $db_fields);
		// if there's an error, undo all inserts
		if ($this->CI->db->affected_rows() < count($fields))
		{
			$error = 'Insert to Fields failed: ' . $this->CI->db->_error_message();
			log_message('error', $error);
			$this->deleteForm($form_id);
			return FALSE;
		}
		
		return $form_id;
	}
	
	function deleteForm($form_id)
	{
		$this->CI->db->delete('Forms', array('form_id' => $form_id));
		$this->CI->db->delete('Fields', array('form_id' => $form_id));
	}
	
	
	function formExists($name)
	{
		$this->CI->db->from('Forms')->where('form_name',$name)->limit(1);
		$query = $this->CI->db->get();
		return $query->num_rows() != 0;
	}
	
	function getNumForms() 
	{
		$query = $this->CI->db->get('Forms');
		return $query->num_rows(); 
	}
	
	// return value: Form object with form structure info
	function getForm($form_id)
	{
		// get form info
		$this->CI->db->from('Forms')->where('form_id',$form_id)->limit(1);
		$query = $this->CI->db->get();
		
		if ($query->num_rows() == 0)
		{
			return false;
		}
		
		$row = $query->row();
		
		$form = new Form();
		$form->id = $form_id;
		$form->name = $row->form_name;
		$form->description = $row->form_description;
		$form->user = $row->user;
		$form->disabled = $row->form_disabled;
		$form->time = $row->time_created;

		// get fields info
		$this->CI->db->from('Fields')->where('form_id',$form_id)->order_by('field_order','asc');
		$query = $this->CI->db->get();
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{	
				$field = new Field();
				
				$field->id = $row->field_id;
				$field->name = $row->field_name;
				$field->type = $row->field_type;
				$field->options = new FieldOptions($row->field_options);
				$field->required = $row->field_required;
				$field->description = $row->field_description;
				
				// add to form result
				$form->fields[] = $field;
			}
		}
		
		return $form;
	}
	
	// return value: array of Form objects without form structure info
	function getForms($limit = 20, $start = 0, $options = 0)
	{
		// get form info
		$this->CI->db->from('Forms')->limit($limit, $start);
		
		// handle options
		if ($options & FormsDB::SORT_TIME)
		{
			$this->CI->db->order_by('time_created', 'desc');
		}
		elseif ($options & FormsDB::SORT_NAME)
		{
			$this->CI->db->order_by('form_name', 'asc');
		}
		
		if ($options & FormsDB::SHOW_DISABLED == 0)
		{
			$this->CI->db->where('form_disabled', 0);
		}
		
		// note: may want to use `form_disabled` as applicable
		$query = $this->CI->db->get();
		
		if ($query->num_rows() === 0)
		{
			return false;
		}
		
		$return = array();

		foreach ($query->result() as $row)
		{
			$form = new Form();
			$form->id = $row->form_id;
			$form->name = $row->form_name;
			$form->description = $row->form_description;
			$form->user = $row->user;
			$form->disabled = $row->form_disabled;
			$form->fields = NULL;
			$form->time_created = $row->time_created;
			
			$return[] = $form;
		}
		
		return $return;
	}
	
	// precondition: form_id = form id
	//               values = array of field_name => value pairs
	// postcontidion: form instance added to database
	// return value: instance_id
	function addFilledForm($form_id, $user, $values)
	{
		// insert form instance
		$instanceData = array(
					'form_id' => $form_id,
					'user' => $user,
					'time' => time()
					);
		$this->CI->db->insert('Filled_Forms', $instanceData);
		
		$instance_id = $this->CI->db->insert_id();

		foreach ($values as $field_id => $value)
		{
			$fieldData = array(
						'field_id' => $field_id,
						'instance_id' => $instance_id,
						'value' => $value
						);
			$this->CI->db->insert('Filled_Values', $fieldData);
		}
		
		return $instance_id;
	}
	
	function getFilledForm($instance_id)
	{
		/*
		$this->db->form('Filled_Forms')->join('Forms', 'Forms.form_id = Filled_Forms.form_id')->join('Fields', 'Fields.form_id = Forms.form_id')->join('Filled_Values')->where('Fields.field_id = Filled_Values.field_id AND Filled_Forms.instance_id = Filled_Values.instance_id');
		*/
		return false;
	}
	
}

?>