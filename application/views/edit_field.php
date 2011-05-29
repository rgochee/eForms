<?php
	$this->load->model('form');
	$types = FieldTypes::getArray();
	$fld_name = 'fields[' . $index . ']';
?>
<li id="field#<?php echo $index; ?>" class="field">
	<?php echo form_hidden($fld_name.'[id]', set_value($fld_name.'[id]', 0)); ?>
	
	<?php 
		echo form_label('Field Name', 'name'.$index, array('class' => 'field_label')); 
		echo form_error('fields['. $index .'][name]'); 
		echo form_input($fld_name.'[name]', set_value($fld_name.'[name]'), 'id="name'.$index.'" size="50"'); 
	?>
	<a class="delete_btn" href="#">Delete Field</a>
	
	<?php 
		echo form_label('Help Text', 'description'.$index, array('class' => 'field_label')); 
		echo form_error('fields['. $index .'][description]'); 
		echo form_textarea($fld_name.'[description]', set_value($fld_name.'[description]'),
			'id="description'.$index.'" cols="35" rows="3"'); 
	?>
	
	<?php 
		echo form_label('Field Type', 'type'.$index, array('class' => 'field_label')); 
		echo form_error('fields['. $index .'][type]'); 
		echo form_error('fields['. $index .'][required]'); 
		echo form_dropdown($fld_name.'[type]', $types, set_value($fld_name.'[type]'), 
			'id="type'.$index.'" class="type_select"'); 
	?>
	
	<?php 
		echo form_checkbox($fld_name.'[required]', 'true', set_value($fld_name.'[required]'), 
			'id="required'.$index.'"');
		echo form_label('This field is required', 'required'.$index); 
	?>
	
	<?php echo form_error('fields['. $index .'][options][]'); ?>
	<ul class="options">
		<?php $optionNum = max(array_count($fld_name.'[options][]'), 1); ?>
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