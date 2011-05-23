<?php
	$this->load->model('form');
	$types = FieldTypes::getArray();
	$fld_name = 'fields[' . $field_id . ']';
?>
<li id="field#<?php echo $field_id; ?>" class="field">
	<?php 
		echo form_label('Field Name', 'name'.$field_id, array('class' => 'field_label')); 
		echo form_error('fields['. $field_id .'][name]'); 
		echo form_input(array(
			'name' => $fld_name.'[name]',
			'id' => 'name'.$field_id,
			'value' => set_value($fld_name.'[name]'),
			'size' => '50'
			)); 
	?>
	<a class="delete_btn" href="#">Delete Field</a>
	
	<?php 
		echo form_label('Help Text', 'description'.$field_id, array('class' => 'field_label')); 
		echo form_error('fields['. $field_id .'][description]'); 
		echo form_textarea(array(
			'name' => $fld_name.'[description]',
			'id' => 'description'.$field_id,
			'value' => set_value($fld_name.'[description]'),
			'cols' => 35,
			'rows' => 3,
			)); 
	?>
	
	<?php 
		echo form_label('Field Type', 'type'.$field_id, array('class' => 'field_label')); 
		echo form_error('fields['. $field_id .'][type]'); 
		echo form_error('fields['. $field_id .'][required]'); 
		echo form_select($fld_name.'[type]', $types, set_value($fld_name.'[type]'), 
			array(
				'id' => 'type'.$field_id, 
				'class' => 'type_select'
				)); 
	?>
	
	<?php 
		echo form_checkbox(array(
			'name' => $fld_name.'[required]',
			'id' => 'required'.$field_id,
			'value' => 'true',
			'checked' => set_value($fld_name.'[required]'),
			));
		echo form_label('This field is required', 'required'.$field_id); 
	?>
	
	<?php echo form_error('fields['. $field_id .'][options][]'); ?>
	<ul class="options">
		<?php $optionNum = array_count($fld_name.'[options][]'); ?>
		<?php for($i=0; $i<$optionNum; $i++): ?>
		<li>
			<span class="dummy"></span>
			<?php echo form_input(array(
				'name' => $fld_name.'[options][]',
				'value' => set_value($fld_name.'[options][]'),
				'size' => '50'
				)); 
			?>
			<a class="delete_btn" href="#">X</a>
		</li>
		<?php endfor ?>
		<a class="add_btn add_option" href="#">Add Option</a>
	</ul>
	
	<ul class="validation">
		<li><a class="add_btn" href="#">Add Validation</a></li>
	</ul>
</li>