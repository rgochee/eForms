
<h2><?php echo $form->name; ?></h2>

<p><?php echo $form->description; ?></p>

<form method="post">
<?php foreach ($form->fields as $field): ?>
<div class="field">
	<label for="fields<?php echo $field->id; ?>" class="field_label"><?php echo $field->name; ?></label>
	<span class="field_help"><?php echo $field->description; ?></span>
	<?php if ($field->type == "" || $field->type == "text"): ?>
		<input id="fields<?php echo $field->id; ?>" name="fields[<?php echo $field->id; ?>]" type="text" value="" size="50" />
	<?php else: ?>
		not a text field!
	<?php endif ?>
</div>
<?php endforeach ?>
<div class="field">
	<input type="submit" value="Submit Form" />
</div>
</form>
