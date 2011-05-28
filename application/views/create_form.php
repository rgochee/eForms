<style type="text/css">
#create_form { padding-bottom: 30px; }
.field { list-style-type: none; border-bottom: 1px solid #bbb; padding: 5px 5px 8px; margin-top: 10px; background-color: #F0F0F0; }
.field input, .field select { margin-bottom: 3px; }
.field input[type=text], .field textarea { width: 400px; }

.options { display: none; }
.add_btn { color: #2E3884; }
.add_btn, .delete_btn { display: inline-block; text-decoration: none; }
.delete_btn { margin-left: 5px; color: #BF6430; font-weight: bold; padding: 3px; }
.delete_btn:hover { text-decoration: underline; color: #FF5C00; }
#name { height: 22px; padding: 2px; }
textarea { font: 12px Verdana, Arial, Helvetica, sans-serif; height: 50px; }
.editingField { background-color: #FFFE73; border-bottom: 1px solid #BFBE30; } 
#success_msg { font-size: 110%; font-weight: bold; color: #A6A500; }
</style>

<h2>Create Form</h2>
<form id="create_form" method="post">
	<?php if (isset($success) && $success): ?>
		<p id="success_msg">Edit successful!</p>
	<?php endif; ?>

	<div class="field editingField">
		<label class="field_label"  for="name">Form Name</label>
		<?php echo form_error('name'); ?>
		<input id="name" type="text" name="name" size="50" value="<?php echo set_value('name'); ?>" />
		
		<label class="field_label"  for="description">Form Description</label>
		<?php echo form_error('description'); ?>
		<textarea id="description" name="description" cols="50"><?php echo set_value('description'); ?></textarea>
	</div>

	<ul id="fields">
	<?php $fields = returnWithDefault($fields, array(array())); ?>
	<?php for($i=0; $i<$numFields; $i++):
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

<div id="validationDlg"></div>

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/themes/smoothness/jquery-ui.css" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
<script type="text/javascript">

$(document).ready(function() {
	$('#add_field').click(function(e) {
		e.preventDefault();
		
		var tpl = $('#field_tpl').html();
		var index = $('#fields').children().length;
			
		var html = tpl.replace(/{{index}}/g, index);
		$(html)
			.appendTo('#fields')
			.children('input:first')
				.trigger('focus');
	});
	$('.add_option').live('click', function(e) {
		e.preventDefault();
		
		var ul = $(this).parent();
		var html = ul.children(':first').clone();
		html.children('input').val("");
		$(this).before(html);
	});
	$('.delete_btn').live('click', function(e) {
		e.preventDefault();
		
		var li = $(this).parent();
		var ul = li.parent();
		if (ul.children('li').length > 1)
			li.remove();
	});
	$('.type_select').live('change', function(e) {
		var type = $(this).val();
		var dummy = "";
		
		switch (type) {
			case "textbox": 
			case "textarea": 
			case "date":
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
	$('.field').live('focus', function() {
		var fieldDiv = $(this);
		
		if (!fieldDiv.hasClass('editingField') && fieldDiv.attr('id') !== 'form_btns') {
			$('.editingField').removeClass('editingField');
			fieldDiv.addClass('editingField');
		}
	});
	
	$('#validationDlg').dialog({
		title: 'Validation',
		position: ['center', 150],
		width: 400,
		modal: true,
		autoOpen: false
	});
	$('.add_validation').live('click', function() {
		var url = this.href;
		var field_id = $(this).closest('.field').attr('id').substring(6);
		var rulesStr = $(this).closest('.validation').find('input[type=hidden]').val();
		var dialog = $('#validationDlg');
		dialog.data('fid', field_id);
		
		dialog.load(url, {rules: rulesStr}, function() {
			dialog.dialog('open');
			$('#validation_type').trigger('change');
		});
		return false;
	});
	$('#validation_type').live('change', function() {
		var opt = $(this).val();
		$('.selected_opts').removeClass('selected_opts');
		$('#'+opt+'_opts').addClass('selected_opts');
	});
	$('#validation_form').live('submit', function() {
		var url = $(this).attr('action');
		var data = $(this).serialize();
		$.post(url, data, function(data) {
			var validationResult = $.parseJSON(data);
			var field_id = $('#validationDlg').data('fid');
			var validationList = $('.validation:eq('+field_id+')')
			validationList.find('.pretty_rules')
					.text(validationResult['pretty']);
			validationList.find('input[type=hidden]')
					.val(validationResult['rules']);
			$('#validationDlg').dialog('close');
		});
	
		return false;
	});

});

</script>