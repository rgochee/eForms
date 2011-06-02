
<h2><?php echo $form->name; ?></h2>

<p><?php echo $form->description; ?></p>

<?php echo form_open(); ?>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/smoothness/jquery-ui.css" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>

<?php foreach ($form->fields as $field): ?>
<div class="field">
	<?php 
		$inputName = 'fields['.$field->id.']';
		$class = 'field_label ' . ($field->required? 'field_required':'');
		echo form_label($field->name, 'fields'.$field->id, array('class' => $class)); 
	?>
	<span class="field_help"><?php echo $field->description; ?></span>
	
	<?php echo form_error('fields['.$field->id.']'); ?>
	<?php echo form_error('fields['.$field->id.'][]'); // for checkbox ?>
	
	<?php if ($field->type == FieldTypes::CHECKBOX): ?>
		<?php $values = set_values($inputName.'[]'); ?>
		<?php foreach ($field->options->getValueOptions() as $option_id=>$option): ?>
		<div class="subfield">
		<?php 
			echo form_checkbox($inputName.'[]', $option, array_search($option, $values) !== FALSE, 
				'id="fields'.$field->id."-".$option_id.'"');
			echo form_label($option, 'fields'.$field->id."-".$option_id); 
		?>
		</div>
		<?php endforeach; ?>
		
	<?php elseif ($field->type == FieldTypes::RADIO): ?>
		<?php foreach ($field->options->getValueOptions() as $option_id=>$option): ?>
		<div class="subfield">
		<?php 
			echo form_radio($inputName, $option, $option == set_value($inputName), 'id="fields'.$field->id."-".$option_id.'"');
			echo form_label($option, 'fields'.$field->id."-".$option_id); 
		?>
		</div>
		<?php endforeach; ?>
		
	<?php elseif ($field->type == FieldTypes::DROPDOWN): ?>
		<?php echo form_dropdown($inputName, $field->options->getValueOptions(), set_value($inputName), 'id="fields'.$field->id.'"'); ?>
		
	<?php elseif ($field->type == FieldTypes::DATE): ?> 
		<input type="text" id="datepicker<?php echo $field->id; ?>" class="date_field" size="50" />
		<input type="hidden" id="fields<?php echo $field->id; ?>" name="<?php echo $inputName; ?>" value="<?php echo set_value($inputName); ?>" />
		
	<?php elseif ($field->type == FieldTypes::TEXTAREA): ?>
		<?php echo form_textarea($inputName, set_value($inputName), 'id="fields'.$field->id.'" rows="3" cols="30"'); ?>
		
	<?php else: // assume it's a text input ?>
		<?php echo form_input($inputName, set_value($inputName), 'id="fields'.$field->id.'" size="50"'); ?>
	<?php endif; ?>
</div>
<?php endforeach; ?>

<div class="field">
	<input type="submit" value="Submit Form" />
</div>

<script type="text/javascript">
$(document).ready(function() {
	// initialize date fields
	$('.date_field').each(function() {
		var id = $(this).attr('id');
		var fid = id.substring(10);
		var altField = '#fields' + fid;
		
		$(this).datepicker({
			dateFormat: 'mm/dd/yy',
			altFormat: 'yy-mm-dd',
			altField: altField
		});
		
		var date = $.datepicker.parseDate('yy-mm-dd', $(altField).val());
		$(this).datepicker('setDate', date);
	});
});
</script>

<?php echo form_close(); ?>
