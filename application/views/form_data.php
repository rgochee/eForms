
<h2>"<?php echo $form_name; ?>" Data</h2>

<p><?php echo anchor(niceFormUri('forms/fill/', $form_id, $form_name), "Go to form", 'title="View the form"'); ?></p>

<table id="form_data" cellspacing="0">
	<thead><tr>
		<?php foreach ($fields as $field_name): ?>
		<th><?php echo $field_name; ?><span class="sortIcon"></span></th>
		<?php endforeach; ?>
		<th>time submitted<span class="sortIcon"></span></th>
	</tr></thead>
	
	<tbody>
	<?php if (!empty($data)): ?>
	<?php foreach ($data as $row): ?>
	<tr>
		<?php foreach ($fields as $field_name): ?>
		<td><?php echo tryPrint($row[$field_name], '&nbsp;'); ?></td>
		<?php endforeach; ?>
		
		<td class="time_col"><?php echo date('n/j/Y g:ia', $row['time_submitted']); ?></td>
	</tr>
	<?php endforeach; ?>
	<?php else: ?>
	<tr><td id="nodata" colspan="<?php echo count($fields) + 1; ?>">No data</td></tr>
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