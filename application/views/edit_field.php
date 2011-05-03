<?php
	$types = array(
		"text" => "Text",
		"checkbox" => "Checkboxes",
		"radio" => "Radio",
		"list" => "Dropdown list"
	);
?>
<li id="field#<?=$field_id?>" class="field">
	<label class="field_label" for="name<?=$field_id?>">Field Name</label>
	<input id="name<?=$field_id?>" type="text" name="fields[<?=$field_id?>][name]" value="<?=tryPrint($field['name'])?>" size="50" />
	<a class="delete_field" href="#">Delete Field</a>
	
	<label class="field_label" for="description<?=$field_id?>">Help Text</label>
	<textarea id="description<?=$field_id?>" name="fields[<?=$field_id?>][description]" rows="3" cols="35">
	<?=tryPrint($field['description'])?>
	</textarea>
	
	<label class="field_label" for="type<?=$field_id?>">Type</label>
	<select id="type<?=$field_id?>"  class="type_select" name="fields[<?=$field_id?>][type]">
	<?php foreach ($types as $val=>$text): ?>
	<?php if (isset($field['type']) && $field['type'] === $val): ?>
		<option value="<?=$val?>" selected="selected"><?=$text?></option>
	<?php else: ?>
		<option value="<?=$val?>"><?=$text?></option>
	<?php endif ?>
	<?php endforeach ?>
	</select>
	
	<input  id="required<?=$field_id?>" type="checkbox" name="fields[<?=$field_id?>][required]" />
	<label for="required<?=$field_id?>">This field is required.</label>
	
	<?php $field_options = returnWithDefault($field['options'], array("")); ?>
	<ul class="options">
		<?php foreach ($field_options as $option): ?>
		<li>
			<span class="dummy"></span>
			<input name="fields[<?=$field_id?>][options][]" type="text" value="<?=$option?>" size="50" />
			<a class="delete_option" href="#">X</a>
		</li>
		<?php endforeach ?>
		<a class="add_option" href="#">Add Option</a>
	</ul>
</li>