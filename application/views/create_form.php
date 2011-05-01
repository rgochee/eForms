
<style type="text/css">
.field { border-bottom: 1px solid #bbb; padding: 5px 5px 8px; margin-top: 10px; background-color: #F0F0F0; }
.field input, .field select { margin-bottom: 3px; }
.field input[type=text], .field textarea { width: 400px; }

.options { display: none; }
.delete_option { display: inline-block; margin-left: 5px; color: #FF5C00; font-weight: bold; padding: 1px 2px; }
.add_option { color: #A6A500; }
#name { height: 22px; padding: 2px; }
textarea { font: 12px Verdana, Arial, Helvetica, sans-serif; height: 50px; }
</style>

<form id="create_form" method="post">
<h2>Create Form</h2>

<?php 
	$fields = array(array("name"=>"Field 1", "description"=>"", "type"=>"text"));
	$types = array(
		"text" => "Text",
		"checkbox" => "Checkboxes",
		"radio" => "Radio",
		"list" => "Dropdown list"
	);
?>

	<div class="field">
		<label class="field_label"  for="name">Form Name</label>
		<input id="name" type="text" name="name" size="50" />
		
		<label class="field_label"  for="description">Form Description</label>
		<textarea id="description" name="description" cols="50"></textarea>
	</div>

<?php foreach($fields as $i => $field): ?>
	<div id="field#<?= $i ?>" class="field">
		<label class="field_label" for="name<?= $i ?>">Field Name</label>
		<input id="name<?= $i ?>" type="text" name="fields[<?= $i ?>][name]" value="<?= $field['name'] ?>" size="50" />
		
		<label class="field_label" for="description<?= $i ?>">Help Text</label>
		<textarea id="description<?= $i ?>" name="fields[<?= $i ?>][description]" value="<?= $field['description'] ?>" rows="3" cols="35"></textarea>
		
		<label class="field_label" for="type<?= $i ?>">Type</label>
		<select id="type<?= $i ?>"  class="type_select" name="fields[<?= $i ?>][type]" value="<?= $field['type'] ?>">
		<?php foreach($types as $val=>$text): ?>
			<?php if($val === $field['type']): ?>
				<option value="<?= $val ?>" selected="selected"><?= $text ?></option>
			<?php else: ?>
				<option value="<?= $val ?>"><?= $text ?></option>
			<?php endif ?>
		<?php endforeach ?>
		</select>
		
		<input  id="required<?= $i ?>" type="checkbox" name="fields[<?= $i ?>][required]" />
		<label for="required<?= $i ?>">This field is required.</label>
		
		<ul class="options">
			<?php 
				if(isset($field['options']))
					$field_options = $field['options'];
				else
					$field_options = array(""); 
			?>
			<?php foreach($field_options as $option): ?>
			<li>
				<span class="dummy"></span>
				<input name="fields[<?= $i ?>][options][]" type="text" value="<?= $option ?>" size="50" />
				<a class="delete_option" href="#">X</a>
			</li>
			<?php endforeach ?>
			<li><a class="add_option" href="#">Add Option</a></li>
		</ul>
	</div>
<?php endforeach ?>

	<div id="form_btns" class="field">
		<input id="add_field" type="button" value="Add a Field" />
		<input id="submit_btn" type="submit" value="Create Form" />
	</div>
</form>

<div id="field_tpl" style="display: none;">
	<div id="field#{{index}}" class="field">
		<label class="field_label" for="name{{index}}">Field Name</label>
		<input id="name{{index}}" type="text" name="fields[{{index}}][name]" size="30" />
		
		<label class="field_label" for="description{{index}}">Help Text</label>
		<textarea id="description{{index}}" name="fields[{{index}}][description]" cols="35" rows="3"></textarea>
		
		<label class="field_label" for="type{{index}}">Type</label>
		<select id="type{{index}}" class="type_select" name="fields[{{index}}][type]">
		<?php foreach($types as $val=>$text): ?>
			<option value="<?= $val ?>"><?= $text ?></option>
		<?php endforeach ?>
		</select>
		
		<input id="required{{index}}" type="checkbox" name="fields[{{index}}][required]" />
		<label for="required{{index}}">This field is required.</label>
		
		<ul class="options">
			<li>
				<span class="dummy"></span>
				<input name="fields[{{index}}][options][]" type="text" size="50" />
				<a class="delete_option" href="#">X</a>
			</li>
			<li><a class="add_option" href="#">Add Option</a></li>
		</ul>
	</div>
</div>
	
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">

$(document).ready(function() {
	$('#add_field').click(function(e) {
		e.preventDefault();
		
		var tpl = $('#field_tpl').html(),
			index = $('#create_form').children('[id^=field#]').length,
			
			html = tpl.replace(/{{index}}/g, index);
		$('#form_btns').before(html);
	});
	$('.add_option').live('click', function(e) {
		e.preventDefault();
		
		var li = $(this).parent(),
			ul = li.parent(),
			tpl = ul.children(':first').clone();
		tpl.children('input').val("");
		li.before(tpl);
	});
	$('.delete_option').live('click', function(e) {
		e.preventDefault();
		
		var li = $(this).parent(),
			ul = li.parent();
		if (ul.children().length > 2)
			li.remove();
	});
	$('.type_select').live('change', function(e) {
		var type = $(this).val(),
			dummy = "";
		
		switch(type) {
			case "text": 
				$(this).siblings('.options').hide();
			break;
			case "list":
			case "checkbox":
			case "radio":
				if(type === "list")
					dummy = '';
				else
					dummy = '<input class="dummy" type="'+type+'" />';
				
				$(this).siblings('.options')
					.find('.dummy')
						.html(dummy)
					.end()
					.show();
			break;
		}
	}).trigger('change');
});
</script>