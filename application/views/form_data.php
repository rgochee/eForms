<?php
if ($form === false):
?>
Invalid form number!
<?php
else:
?>
<h2>"<?php echo $form->name; ?>" Data</h2>

<p><?php echo anchor(niceFormUri('forms/fill/', $form->id, $form->name), "Go to form", 'title="View the form"'); ?></p>

<table id="form_data" cellspacing="0">
	<thead><tr>
		<?php foreach ($form->fields as $field): ?>
		<th><?php echo $field->name; ?><span class="sortIcon"></span></th>
		<?php endforeach; ?>
		<th>time submitted<span class="sortIcon"></span></th>
	</tr></thead>
	
	<tbody>
	<?php if (!empty($data)): ?>
	<?php foreach ($data as $row): ?>
	<tr>
		<?php foreach ($form->fields as $field): ?>
		<td><?php echo tryPrint($row[$field->name], '&nbsp;'); ?></td>
		<?php endforeach; ?>
		
		<td class="time_col"><?php echo date('n/j/Y g:ia', $row['_time_submitted']); ?></td>
	</tr>
	<?php endforeach; ?>
	<?php else: ?>
	<tr><td id="nodata" colspan="<?php echo count($form->fields) + 1; ?>">No data</td></tr>
	<?php endif ?>
	</tbody>
</table>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>static/js/tablesorter.min.js"></script>
<script type="text/javascript">

$(function() {
	$("#form_data").tablesorter();
});

</script>
<?php
endif
?>