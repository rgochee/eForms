
<h2><?php echo $form->name; ?></h2>

<p><?php echo $form->description; ?></p>

<form method="post">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/smoothness/jquery-ui.css" type="text/css" /> ..
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>
<?php foreach ($form->fields as $field): ?>
<div class="field">
	<label for="fields<?php echo $field->id; ?>" class="field_label<?php if ($field->required) { echo ' field_required'; } ?>"><?php echo $field->name; ?></label>
	<span class="field_help"><?php echo $field->description; ?></span>
	<?php echo form_error('fields['.$field->id.']'); ?>
	<?php if ($field->type=="checkbox"): ?>
		<?php foreach ($field->options->getOptions() as $option_id=>$option): ?>
			<div class="subfield">
			<input id="fields<?php echo $field->id."-".$option_id; ?>" name="fields[<?php echo $field->id; ?>][]" type="checkbox" value="<?php echo $option; ?>" />
			<label for="fields<?php echo $field->id."-".$option_id; ?>"><?php echo $option; ?></label>
			</div>
		<?php endforeach ?>
	<?php elseif ($field->type=="radio"): ?>
		<?php foreach ($field->options->getOptions() as $option_id=>$option): ?>
			<div class="subfield">
			<input id="fields<?php echo $field->id."-".$option_id; ?>" name="fields[<?php echo $field->id; ?>]" type="radio" value="<?php echo $option; ?>" /> 
			<label for="fields<?php echo $field->id."-".$option_id; ?>"><?php echo $option; ?></label>
			</div>
		<?php endforeach ?>
	<?php elseif ($field->type=="dropdown"): ?>
		<select id="fields<?php echo $field->id; ?>" name="fields[<?php echo $field->id; ?>]">
		<?php foreach ($field->options->getOptions() as $option): ?>
			<option value="<?php echo $option; ?>" /> <?php echo $option; ?></option>
		<?php endforeach ?>
		</select>
	<?php elseif ($field->type=="date"): ?> 
		<input id="fieldsdate<?php echo $field->id; ?>" type="textbox" size="50" />
		<input id="fields<?php echo $field->id; ?>" name="fields[<?php echo $field->id; ?>]" type="hidden" />
		<script>$(document).ready(function(){ $("#fieldsdate<?php echo $field->id; ?>").datepicker({altField:'#fields<?php echo $field->id; ?>',altFormat:'yy-mm-dd'});});</script>
	<?php elseif ($field->type=="textarea"): ?>
		<textarea id="fields<?php echo $field->id; ?>" name="fields[<?php echo $field->id; ?>]" cols="30" rows="3"></textarea>
	<?php else: // assume it's a text input ?>
		<input id="fields<?php echo $field->id; ?>" name="fields[<?php echo $field->id; ?>]" type="text" size="50" />
	<?php endif ?>
</div>
<?php endforeach ?>
<div class="field">
	<input type="submit" value="Submit Form" />
</div>
</form>
