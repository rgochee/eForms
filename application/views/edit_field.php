<?php
	$this->load->model('form');
	$types = FieldTypes::getArray();
	$fld_name = 'fields[' . $field_id . ']';
?>
<li id="field#<?php echo $field_id; ?>" class="field">
	<?php 
		echo form_label('Field Name', 'name'.$field_id, array('class' => 'field_label')); 
		echo form_error('fields['. $field_id .'][name]'); 
		echo form_input($fld_name.'[name]', set_value($fld_name.'[name]'), 'id="name'.$field_id.'" size="50"'); 
	?>
	<a class="delete_btn" href="#">Delete Field</a>
	
	<?php 
		echo form_label('Help Text', 'description'.$field_id, array('class' => 'field_label')); 
		echo form_error('fields['. $field_id .'][description]'); 
		echo form_textarea($fld_name.'[description]', set_value($fld_name.'[description]'),
			'id="description'.$field_id.'" cols="35" rows="3"'); 
	?>
	
	<?php 
		echo form_label('Field Type', 'type'.$field_id, array('class' => 'field_label')); 
		echo form_error('fields['. $field_id .'][type]'); 
		echo form_error('fields['. $field_id .'][required]'); 
		echo form_dropdown($fld_name.'[type]', $types, set_value($fld_name.'[type]'), 
			'id="type'.$field_id.'" class="type_select"'); 
	?>
	
	<?php 
		echo form_checkbox($fld_name.'[required]', 'true', set_value($fld_name.'[required]'), 
			'id="required'.$field_id.'"');
		echo form_label('This field is required', 'required'.$field_id); 
	?>
	
	<?php echo form_error('fields['. $field_id .'][options][]'); ?>
	<ul class="options">
		<?php $optionNum = array_count($fld_name.'[options][]'); ?>
		<?php for ($i=0; $i<$optionNum; $i++): ?>
		<li>
			<span class="dummy"></span>
			<?php echo form_input($fld_name.'[options][]', set_value($fld_name.'[options][]'), 'size="50"'); ?>
			<a class="delete_btn" href="#">X</a>
		</li>
		<?php endfor; ?>
		<a class="add_btn add_option" href="#">Add Option</a>
	</ul>
	
	<ul class="validation">
		<li><?php echo form_hidden($fld_name.'[validation]', set_value($fld_name.'[validation]')); ?></li>
		<li class="pretty_rules"></li>
		<li><?php echo anchor('admin/validation', 'Set Validation', 'class="add_btn add_validation"'); ?></li>
	</ul>
</li>