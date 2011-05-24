
<h2><?php echo $form->name; ?></h2>

<p><?php echo $form->description; ?></p>

<form method="post">
<?php foreach ($form->fields as $field): ?>
<div class="field">
	<label for="fields<?php echo $field->id; ?>" class="field_label<?php if ($field->required) { echo ' field_required'; } ?>"><?php echo $field->name; ?></label>
	<span class="field_help"><?php echo $field->description; ?></span>
	
	<?php if ($field->type=="checkbox"): ?>
		<?php echo form_error('fields['.$field->id.'][]'); ?>
		<?php foreach ($field->options->getValueOptions() as $option_id=>$option): ?>
			<div class="subfield">
			<input id="fields<?php echo $field->id."-".$option_id; ?>" name="fields[<?php echo $field->id; ?>][]" type="checkbox" value="<?php echo $option; ?>" />
			<label for="fields<?php echo $field->id."-".$option_id; ?>"><?php echo $option; ?></label>
			</div>
		<?php endforeach ?>
	<?php elseif ($field->type=="radio"): ?>
		<?php echo form_error('fields['.$field->id.']'); ?>
		<?php foreach ($field->options->getValueOptions() as $option_id=>$option): ?>
			<div class="subfield">
			<input id="fields<?php echo $field->id."-".$option_id; ?>" name="fields[<?php echo $field->id; ?>]" type="radio" value="<?php echo $option; ?>" /> 
			<label for="fields<?php echo $field->id."-".$option_id; ?>"><?php echo $option; ?></label>
			</div>
		<?php endforeach ?>
	<?php elseif ($field->type=="dropdown"): ?>
		<?php echo form_error('fields['.$field->id.']'); ?>
		<select id="fields<?php echo $field->id; ?>" name="fields[<?php echo $field->id; ?>]">
		<?php foreach ($field->options->getValueOptions() as $option): ?>
			<option value="<?php echo $option; ?>" /> <?php echo $option; ?></option>
		<?php endforeach ?>
		</select>
	<?php else: // assume it's a text input ?>
		<?php echo form_error('fields['.$field->id.']'); ?>
		<input id="fields<?php echo $field->id; ?>" name="fields[<?php echo $field->id; ?>]" type="text" size="50" />
	<?php endif ?>
</div>
<?php endforeach ?>
<div class="field">
	<input type="submit" value="Submit Form" />
</div>
</form>
