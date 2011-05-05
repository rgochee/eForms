
<h2><?php echo $form->name; ?></h2>

<p><?php echo $form->description; ?></p>

<form method="post">
<?php foreach ($form->fields as $field): ?>
<div class="field">
	<label for="fields<?php echo $field->id; ?>" class="field_label<?php if ($field->required) { echo ' field_required'; } ?>"><?php echo $field->name; ?></label>
	<?php if ($field->type=="checkbox"): ?>
		<?php foreach ($field->options->getOptions() as $option): ?>
			<div class="subfield">
			<input id="fields<?php echo $field->id; ?>" name="fields[<?php echo $field->id; ?>]" type="checkbox" value="<?php echo $option; ?>" /> <?php echo $option; ?>
			</div>
		<?php endforeach ?>
	<?php elseif ($field->type=="radio"): ?>
		<?php foreach ($field->options->getOptions() as $option): ?>
			<div class="subfield">
			<input id="fields<?php echo $field->id; ?>" name="fields[<?php echo $field->id; ?>]" type="radio" value="<?php echo $option; ?>" /> <?php echo $option; ?>
			</div>
		<?php endforeach ?>
	<?php elseif ($field->type=="dropdown"): ?>
		<select id="fields<?php echo $field->id; ?>" name="fields[<?php echo $field->id; ?>]">
		<?php foreach ($field->options->getOptions() as $option): ?>
			<option value="<?php echo $option; ?>" /> <?php echo $option; ?></option>
		<?php endforeach ?>
		</select>
	<?php else: // assume it's a text input ?>
		<input id="fields<?php echo $field->id; ?>" name="fields[<?php echo $field->id; ?>]" type="text" size="50" />
	<?php endif ?>
</div>
<?php endforeach ?>
<div class="field">
	<input type="submit" value="Submit Form" />
</div>
</form>
