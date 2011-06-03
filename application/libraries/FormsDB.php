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
			$this->_logDbError('Insert to Forms failed');
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
		if ($this->CI->db->affected_rows() < count($fields))
		{
			$this->_logDbError('Insert to Fields failed');
			$this->deleteForm($form_id); // revert changes
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
	
	function getNumSearchForms($search_terms, $options = 0)
	{
		$words = explode(' ', $search_terms);
		foreach ($words as $word)
		{
			$this->CI->db->or_like('form_name', $word); 
		}
		$this->handleOptions($options);
		return $this->getNumForms();
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
		$this->CI->db->from('Fields')->where(array('form_id'=>$form_id,'field_order >'=>-1))->order_by('field_order','asc');
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
				$field->order = $row->field_order;
				
				// add to form result
				$form->fields[] = $field;
			}
		}
		
		return $form;
	}
	
	function searchForm($search_terms, $limit = 20, $start = 0, $options = 0)
	{
		$words = explode(' ', $search_terms);
		foreach ($words as $word)
		{
			$this->CI->db->or_like('form_name', $word); 
		}
		
		return $this->getForms($limit, $start, $options);
	}
	
	function handleOptions($options = 0)
	{
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
	}
	
	// return value: array of Form objects without form structure info
	function getForms($limit = 20, $start = 0, $options = 0)
	{
		// get form info
		$this->CI->db->from('Forms')->limit($limit, $start);
		
		// handle options
		$this->handleOptions($options);
		
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
	
	function editFilledForm($instance_id)
	{
		return false;
	}
	
	function getFilledData($form_id)
	{
		$form = $this->getForm($form_id);
		if ($form === false)
		{
			return false;
		}
		
		$this->CI->db->from('Filled_Forms')->where('form_id',$form_id);
		$query = $this->CI->db->get();
		$responses = array();
		foreach ($query->result() as $row)
		{
			$this->CI->db->from('Filled_Values')
				->join('Fields','Filled_Values.field_id = Fields.field_id')
				->where('instance_id', $row->instance_id)
				->order_by('field_order');
			$subquery = $this->CI->db->get();
			$response = array();
			foreach ($subquery->result() as $filled_field)
			{
				$response[$filled_field->field_name] = str_replace(OPT_SEPARATOR, ', ', $filled_field->value);
			}
			$response['_time_submitted'] = $row->time;
			array_push($responses, $response);
		}
		return array('form' => $form, 'responses' => $responses);
	}
	
	function disableField($form_id, $field_id)
	{
		$this->CI->db->select('field_name, field_order')->where(array('form_id' => $form_id, 'field_id' => $field_id));
		$query = $this->CI->db->get('Fields');
		$field = $query->row();
		
		$this->CI->db->where(array(
			'form_id' => $form_id,
			'field_order >' => $field->field_order
		));
		$this->CI->db->set('field_order', 'field_order-1', FALSE);
		$this->CI->db->update('Fields');
		$numShifted = $this->CI->db->affected_rows();
		if ($this->CI->db->_error_message() != "")
		{
			$this->_logDbError('Shifting field_order failed');
			return FALSE;
		}
		
		// delete the actual field
		$this->CI->db->where(array('form_id' => $form_id, 'field_id' => $field_id));
		$this->CI->db->set('field_order', -1);
		$this->CI->db->update('Fields');
		$fieldDeleted = $this->CI->db->affected_rows();
		if ($fieldDeleted == 0)
		{
			$this->_logDbError('Deleting field failed');
			return FALSE;
		}
		
		return $field->field_name;
	}
	
	function editForm($form_id, $name, $description)
	{
		$update = array(
			'form_name' => $name,
			'form_description' => $description
		);
		
		$this->CI->db->where('form_id', $form_id);
		$this->CI->db->update('Forms', $update);
		
		if ($this->CI->db->_error_message() != "")
		{
			$this->_logDbError('Editing form name/description failed');
			return FALSE;
		}
		return $this->CI->db->affected_rows();
	}
	
	function editField($form_id, $field_id, $field)
	{
		$field = array(
			'field_name' => $field->name,
			'field_type' => $field->type,
			'field_options' => $field->options->getSerialized(),
			'field_required' => $field->required,
			'field_description' => $field->description,
			'field_order' => $field->order
		);
		
		$this->CI->db->where(array('form_id' => $form_id, 'field_id' => $field_id));
		$this->CI->db->update('Fields', $field);
		// can't use affected rows == 0 check
		// affected rows is 0 when the field attributes are the same
		if ($this->CI->db->_error_message() != "")
		{
			$this->_logDbError('Editing field '.$field_id.' failed');
			return FALSE;
		}
		
		return $this->CI->db->affected_rows();
	}
	
	function addField($form_id, $field, $order = -1)
	{
		// get number of fields in the form to determine order
		$this->CI->db->from('Fields')->where('form_id', $form_id);
		$numFields = $this->CI->db->count_all_results();
	
		$field = array(
			'form_id' => $form_id,
			'field_name' => $field->name,
			'field_type' => $field->type,
			'field_options' => $field->options->getSerialized(),
			'field_required' => $field->required,
			'field_description' => $field->description,
			'field_order' => $numFields // last order
		);
		$this->CI->db->insert('Fields', $field);
		if ($this->CI->db->affected_rows() == 0)
		{
			$this->_logDbError('Cannot add field: ');
			return FALSE;
		}
		
		$field_id = $this->CI->db->insert_id();
		if ($order != -1)
		{
			$this->moveFieldOrder($form_id, $field_id, $order);
		}
		
		return $field_id;
	}
	
	function moveFieldOrder($form_id, $field_id, $newOrder)
	{
		$this->CI->db->from('Fields')->where('field_id', $field_id);
		$query = $this->CI->db->get();
		if ($query->num_rows() == 0)
		{
			log_message('error', 'Field doesn\'t exist');
			return FALSE;
		}
		
		// Decide whether fields must be shifted up/down to make room
		$field = $query->row();
		if ($newOrder < $field->field_order)
		{
			$min = $newOrder;
			$max = $field->field_order;
			$shift = '+1';
		}
		else
		{
			$min = $field->field_order;
			$max = $newOrder;
			$shift = '-1';
		}
		
		// shift fields between old and new position
		$this->CI->db->where(array(
			'form_id' => $form_id,
			'field_order >=' => $min,
			'field_order <=' => $max
		));
		$this->CI->db->set('field_order', 'field_order'.$shift, FALSE);
		$this->CI->db->update('Fields');
		$numShifted = $this->CI->db->affected_rows();
		if ($numShifted == 0)
		{
			$this->_logDbError('Adding 1 to field_orders failed');
			return FALSE;
		}
		
		// move the actual field
		$this->CI->db->where(array('form_id' => $form_id, 'field_id' => $field_id));
		$this->CI->db->set('field_order', $newOrder);
		$this->CI->db->update('Fields');
		$fieldMoved = $this->CI->db->affected_rows();
		if ($fieldMoved == 0)
		{
			$this->_logDbError('Moving field failed');
			return FALSE;
		}
		return $numShifted + $fieldMoved;
	}
	
	private function _logDbError($message)
	{
		$errorMsg = $message . ' on "' . $this->CI->db->last_query() . '"';
		$errorMsg .= "\n" . $this->CI->db->_error_message();
		log_message('error', $errorMsg);
	}
}

?>