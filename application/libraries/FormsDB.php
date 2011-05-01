<?php


class FormsDB {
	var $CI;
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
	}
	
	function createForm($name, $description, $user, $fields)
	{
		// insert form
		$formData = array(
					'form_name' => $name,
					'form_description' => $description,
					'user' => $user
					);
		$this->CI->db->insert('Forms', $formData);
		$form_id = $this->CI->db->insert_id();
		
		// insert $field info
		$order = 0;
		foreach ($fields as $field)
		{
			$fieldData = array(
						'form_id' => $form_id,
						'field_name' => $field->name,
						'field_type' => $field->type,
						'field_options' => $field->options,
						'field_required' => $field->required,
						'field_description' => $field->description,
						'field_order' => $order
						);
			$this->CI->db->insert('Fields', $fieldData);
			++$order;
		}
		
		return $form_id;
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
		$form->disabled = $row->disabled;

		// get fields info
		$this->CI->db->from('Fields')->where('form_id',$form_id)->order_by('order','asc');
		$query = $this->CI->db->get();
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{	
				$field = new Field();
				
				$field->id = $row->field_id;
				$field->name = $row->field_name;
				$field->type = $row->field_type;
				$field->options->setOptions($row->field_options);
				$field->required = $row->field_required;
				$field->description = $row->field_description;
				
				// add to form result
				$form->fields[] = $field;
			}
		}
		
		return $form;
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
		$this->CI->db->insert('Fields', $instanceData);
		
		$instance_id = $this->CI->db->insert_id();
		
		// get field information from form
		$this->CI->db->from('Fields')->where('form_id',$form_id);
		$query = $this->CI->db->get();
		$fieldIDMap = array();
		foreach ($query->result() as $row)
		{
			// NOTE: reverse array?
			$fieldIDMap[$row->field_name] = $row->field_id;
		}
	
		foreach ($values as $field => $value)
		{
			if (!array_key_exists($field, $fieldIDMap))
			{
				continue;
			}
		
			$fieldData = array(
						'field_id' => $fieldIDMap[$field],
						'instance_id' => $instance_id,
						'value' => $value
						);
			$this->CI->db->insert('Fields', $fieldData);
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