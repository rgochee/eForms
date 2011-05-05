<style type="text/css">
th { background-color: #ccc; padding: 2px 5px; }
td { padding: 2px 5px; }
table, th, td { border: 1px solid #bbb; }
#nodata { text-align: center; }
</style>

<h2><?php echo $form->name; ?></h2>

<?php echo $form->description; ?></h2>

		<?php foreach ($form->fields as $field): ?>
			<label><?php echo $field->name; ?></label>
<?php if ($field->type="text"): ?>
<input class="field->label" name="fields[<?=$field->id?>]" type="text" value="" size="50" />
<?php else: ?>
	"not a text field!"
<?php endif; ?>
		<?php endforeach ?>