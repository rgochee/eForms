<style type="text/css">
.field { list-style-type: none; border-bottom: 1px solid #bbb; padding: 5px 5px 8px; margin-top: 10px; background-color: #F0F0F0; }
.field input, .field select { margin-bottom: 3px; }
.field input[type=text], .field textarea { width: 400px; }

.options { display: none; }
.delete_field, .delete_option { display: inline-block; margin-left: 5px; color: #FF5C00; font-weight: bold; padding: 1px 2px; }
.add_option { color: #A6A500; }
.delete_field, .add_option, .delete_option { text-decoration: none; }
.delete_field:hover, .add_option:hover, .delete_option:hover { text-decoration: underline; }
#name { height: 22px; padding: 2px; }
textarea { font: 12px Verdana, Arial, Helvetica, sans-serif; height: 50px; }
</style>

<form id="create_form" method="post">
<h2>Create Form</h2>
	<div class="field">
		<label class="field_label"  for="name">Form Name</label>
		<?php echo form_error('name'); ?>
		<input id="name" type="text" name="name" size="50" value="<?php echo set_value('name'); ?>" />
		
		<label class="field_label"  for="description">Form Description</label>
		<?php echo form_error('description'); ?>
		<textarea id="description" name="description" cols="50"><?php echo set_value('description'); ?></textarea>
	</div>

	<ul id="fields">
	<?php $fields = returnWithDefault($fields, array(array())); ?>
	<?php for($i=0; $i<array_count('fields[]'); $i++):
		$field = array(); ?>
	<?php $this->load->view('edit_field', array('field_id'=>$i, 'field'=>$field)); ?>
	<?php endfor ?>
	</ul>

	<div id="form_btns" class="field">
		<input id="add_field" type="button" value="Add a Field" />
		<input id="submit_btn" type="submit" value="Create Form" />
	</div>
</form>

<div id="field_tpl" style="display: none;">
	<?php $this->load->view('edit_field', array('field_id'=>'{{index}}', 'field'=>array())); ?>
</div>
	
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">

$(document).ready(function() {
	$('#add_field').click(function(e) {
		e.preventDefault();
		
		var tpl = $('#field_tpl').html(),
			index = $('#fields').children().length,
			
			html = tpl.replace(/{{index}}/g, index);
		$('#fields').append(html);
	});
	$('.add_option').live('click', function(e) {
		e.preventDefault();
		
		var ul = $(this).parent(),
			html = ul.children(':first').clone();
		html.children('input').val("");
		$(this).before(html);
	});
	$('.delete_field, .delete_option').live('click', function(e) {
		e.preventDefault();
		
		var li = $(this).parent(),
			ul = li.parent();
		if (ul.children('li').length > 1)
			li.remove();
	});
	$('.type_select').live('change', function(e) {
		var type = $(this).val(),
			dummy = "";
		
		switch(type) {
			case "textbox": 
				$(this).siblings('.options').hide();
			break;
			case "dropdown":
			case "checkbox":
			case "radio":
				if(type === "dropdown")
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