<?php
	$this->load->model('form');
	$types = FieldTypes::getTypes();
	$fld_name = 'fields[' . $field_id . ']';
?>
<li id="field#<?php echo $field_id; ?>" class="field">
	<label class="field_label" for="name<?php echo $field_id; ?>">Field Name</label>
	<?php echo form_error('fields['. $field_id .'][name]'); ?>
	<input id="name<?php echo $field_id; ?>" type="text" name="<?php echo $fld_name; ?>[name]" value="<?php echo set_value($fld_name.'[name]') ?>" size="50" />
	<a class="delete_field" href="#">Delete Field</a>
	
	<label class="field_label" for="description<?php echo $field_id; ?>">Help Text</label>
	<?php echo form_error('fields['. $field_id .'][description]'); ?>
	<textarea id="description<?php echo $field_id; ?>" name="<?php echo $fld_name; ?>[description]" rows="3" cols="35"><?php echo set_value($fld_name.'[description]') ?></textarea>
	
	<label class="field_label" for="type<?php echo $field_id; ?>">Type</label>
	<?php echo form_error('fields['. $field_id .'][type]'); ?>
	<?php echo form_error('fields['. $field_id .'][required]'); ?>
	<select id="type<?php echo $field_id; ?>"  class="type_select" name="<?php echo $fld_name; ?>[type]">
	<?php foreach ($types as $type): ?>
	<option value="<?php echo $type; ?>" <?php echo set_select($fld_name.'[type]', $type, $type==$types[0]); ?>>
		<?php echo FieldTypes::asPrettyName($type); ?>
	</option>
	<?php endforeach ?>
	</select>
	
	<input id="required<?php echo $field_id; ?>" type="checkbox" name="<?php echo $fld_name; ?>[required]" <?php echo set_checkbox($fld_name.'[required]', 'on'); ?> />
	<label for="required<?php echo $field_id; ?>">This field is required.</label>
	
	<?php $fieldNum = array_count($fld_name.'[options][]'); ?>
	<ul class="options">
		<?php for($i=0; $i<$fieldNum; $i++): ?>
		<li>
			<span class="dummy"></span>
			<input name="<?php echo $fld_name; ?>[options][]" type="text" value="<?php echo set_value($fld_name.'[options][]'); ?>" size="50" />
			<a class="delete_option" href="#">X</a>
		</li>
		<?php endfor ?>
		<a class="add_option" href="#">Add Option</a>
	</ul>
</li>