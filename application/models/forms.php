<?php

class Form {
	var $id;
	var $name;
	var $description;
	var $user;
	var $disabled;
	var $fields;
}

class Field {
	var $id;
	var $name;
	var $type;
	var $options;
	var $required;
	var $description;
	var $value;
}

class FormModel extends CI_Model {
	var $a;
	
	function __constuct()
	{
		parent::__construct();
	}
	
	function createForm($name, $description, $user, $fields)
	{
		// insert form
		$formData = array(
					'form_name' => $name,
					'form_description' => $description,
					'user' => $user
					);
		$this->db->insert('Forms', $formData);
		$form_id = insert_id();
		
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
			$this->db->insert('Field', $fieldData);
			++$order;
		}
		
		return $form_id;
	}
	
	function getForm($id)
	{
		// get form info
		$this->db->from('Forms')->where('form_id',$id)->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 0)
		{
			return false;
		}
		
		$row = $query->row();
		
		$form = new Form();
		$form->id = $id;
		$form->name = $row->form_name;
		$form->description = $row->form_description;
		$form->user = $row->user;
		$form->disabled = $row->disabled;

		// get fields info
		$this->db->form('Fields')->where('form_id',$id)->order_by('order','asc');
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{	
				$field = new Field();
				
				$field->id = $row->field_id;
				$field->name = $row->field_name;
				$field->type = $row->field_type;
				$field->options = $row->field_options;
				$field->required = $row->field_required;
				$field->description = $row->field_description;
				
				// add to form result
				$form->fields[] = $field;
			{
		}
		
		return $form;
	}
	
	function addFilledForm($id, $values)
	{
		// insert form
		$instance_id = insert_id();
		
		return $instance_id;
	}
	
}

?>