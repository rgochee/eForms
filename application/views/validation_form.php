<style type="text/css">

#validation_opts { padding-left: 0; }
#validation_opts > li { margin: 10px 0; }
ul { list-style: none; }
li { margin: 2px; }
.type_opts { display: none; }
.selected_opts { display: block; }
</style>

<?php echo form_open('admin/validation', 'id="validation_form"'); ?>

Leave fields empty for no validation
<?php echo validation_errors(); ?>

<ul id="validation_opts">
<li>Length Validation
	<ul>
		<li>
			Range: 
			<?php echo form_input('min_length', set_value('min_length'), 'size="3"'); ?> - 
            <?php echo form_input('max_length', set_value('max_length'), 'size="3"'); ?>
		</li>
	</ul>
</li>

<li>Validation type:
	<?php echo form_dropdown('vtype', $validationTypes, set_value('vtype'), 'id="validation_type"'); ?>
	
	<ul id="char_opts" class="type_opts">
		<li>
			<?php echo form_radio('chars', 'disallow', 'disallow' === set_value('chars'), 'id="disallow"'); ?>
			<?php echo form_label('Anything but: ', 'disallow'); ?>
			<?php echo form_radio('chars', 'allow', 'allow' == set_value('chars'), 'id="allow"'); ?>
			<?php echo form_label('Contains only: ', 'allow'); ?>
		</li>

		<li><?php echo form_input('char_spec', set_value('char_spec')); ?> (List characters separated by space)</li>
	</ul>
	<ul id="integer_opts" class="type_opts">
		<li>
			Range: 
            <?php echo form_input('greater_than', set_value('greater_than'), 'size="3"'); ?> - 
			<?php echo form_input('less_than', set_value('less_than'), 'size="3"'); ?>
		</li>
	</ul>
	<ul id="phone_format_opts" class="type_opts" style="font: 10pt Courier New, monospace;" >
		Store in database as:
		<?php foreach ($phoneFormats as $i=>$format): ?>
		<li>
			<?php echo form_radio('phone_format', $i, FALSE, 'id="phone'.$i.'"'); ?>
			<?php echo form_label($format, 'phone'.$i); ?>
		</li>
		<?php endforeach; ?>
	</ul>

</li>
</ul>

<?php echo form_submit('submit', 'Set validation rules'); ?>

<?php echo form_close(); ?>


