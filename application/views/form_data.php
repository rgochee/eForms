
<style type="text/css">
th { background-color: #ccc; padding: 2px 5px; }
td { padding: 2px 5px; }
table, th, td { border: 1px solid #bbb; }
#nodata { text-align: center; }
</style>

<h2><?php echo $form_name; ?> Data</h2>

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
		<td><?php echo $row[$field_name]; ?></td>
		<?php endforeach ?>
		
	</tr>
	<?php endforeach ?>
	<?php else: ?>
	<tr><td id="nodata" colspan="<?php echo count($fields); ?>">No data</td></tr>
	<?php endif ?>
	
</table>