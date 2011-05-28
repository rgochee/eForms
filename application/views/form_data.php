
<style type="text/css">
th { background-color: #ccc; padding: 2px 5px; }
td { padding: 2px 5px; }
table, th, td { border: 1px solid #bbb; }
#nodata { text-align: center; }
</style>

<h2>"<?php echo $form_name; ?>" Data</h2>

<p><?php echo anchor(niceFormUri('forms/fill/', $form_id, $form_name), "Go to form", 'title="View the form"'); ?></p>

<table cellspacing="0">
	<tr>
		<?php foreach ($fields as $field_name): ?>
		<th><?php echo $field_name; ?></th>
		<?php endforeach ?>
		
	</tr>
	
	<?php if (!empty($data)): ?>
	<?php foreach ($data as $row): ?>
	<tr>
		<?php foreach ($fields as $field_name): ?>
		<td><?php echo tryPrint($row[$field_name], '&nbsp;'); ?></td>
		<?php endforeach ?>
		
	</tr>
	<?php endforeach ?>
	<?php else: ?>
	<tr><td id="nodata" colspan="<?php echo count($fields); ?>">No data</td></tr>
	<?php endif ?>
	
</table>