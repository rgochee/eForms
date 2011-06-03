<?php if ($form === false): ?>
	Invalid form number!
<?php else: ?>

<style type="text/css">

#data_searcher { margin: 10px 0; padding: 5px; background-color: #FFFE73; border: 1px solid #FFA573; }
#data_search { width: 50%; }
#data_search, #col_names { padding: 2px; margin: 3px; }
#more_options { margin: 5px 0 0; display: none; }

</style>

<h2>"<?php echo $form->name; ?>" Data</h2>

<p><?php echo anchor(nice_form_uri('forms/fill/', $form->id, $form->name), "Go to form", 'title="View the form"'); ?></p>

<form id="data_searcher" method="GET">
	<label for="data_search">Search for </label>
	<input type="text" name="data_search" id="data_search" value="" />
	<label for="col_names">in </label>
	<select id="col_names" name="col_names">
		<option value="-1" selected="selected">All fields</option>
		<?php foreach ($form->fields as $index=>$field): ?>
		<option value=".col<?php echo $index; ?>" ><?php echo $field->name; ?></option>
		<?php endforeach; ?>
	</select>
	<input type="submit" value="Search" />
	<a href="#" id="options_toggle">More Options</a>
	
	<div id="more_options">
		Submitted between 
		<input type="text" id="from_date"  value="" size="10" /> and 
		<input type="text" id="to_date" value="" size="10" />
	</div>
</form>

<p id="search_info"></p>

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
		<?php foreach ($form->fields as $index=>$field): ?>
		<td class="col<?php echo $index; ?>">
			<?php echo set_text($row[$field->name], '&nbsp;'); ?>
		</td>
		<?php endforeach; ?>
		
		<td class="time_col"><?php echo date('m/d/Y g:ia', $row['_time_submitted']); ?></td>
	</tr>
	<?php endforeach; ?>
	<tr id="nodata" style="display: none;"><td colspan="<?php echo count($form->fields) + 1; ?>">No data</td></tr>
	<?php else: ?>
	<tr id="nodata"><td colspan="<?php echo count($form->fields) + 1; ?>">No data</td></tr>
	<?php endif; ?>
	</tbody>
</table>

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/smoothness/jquery-ui.css" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>static/js/tablesorter.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>
<script type="text/javascript">

function parseSubmittedDate(str) {
	var date_and_time = str.split(' ');
	var date = date_and_time[0];
	return new Date(date).getTime();
}

$(function() {
	// initialize ui
	$('#from_date, #to_date').datepicker({altFormat:'mm/dd/yy'});
	$('#form_data').tablesorter();
	$('#options_toggle').click(function(e) {
		e.preventDefault();
		
		$('#more_options').toggle(150);
	});
	
	// main search handler
	$('#data_searcher').submit(function(e) {
		e.preventDefault();
		
		var search_string = $('#data_search').val().trim();
		var search_terms;
		// leave undefined if there are no search terms
		if (search_string !== "") {
			search_terms = search_string.toLowerCase().split(' ');
			$('#search_info').text('Searching for "' + search_string + '"...');
		} else {
			$('#search_info').text('');
		}
		
		var field = $('#col_names').val();
		var fromDate = new Date($('#from_date').val()).getTime();
		var toDate = new Date($('#to_date').val()).getTime();
		
		var found = false;
		$('#form_data').children('tbody').children(':not(#nodata)').hide()
			.each(function() {
				// date filter
				var submitted_string = $(this).find('.time_col').text();
				var submitted = parseSubmittedDate(submitted_string);
				if (fromDate && submitted < fromDate) {
					return;
				}
				if (toDate && submitted > toDate) {
					return;
				}
				
				if (!search_terms) {
					found = true;
					$(this).show();
					return;
				}
				
				// field filter (what DOM elements should be searched on)
				var contents = "";
				if (field == -1) {
					contents = $(this).text().toLowerCase();
				} else {
					contents = $(this).find(field).text().toLowerCase();
				}
				
				// search through said elements
				for (var i in search_terms) {
					if (search_terms[i] === "") {
						continue;	 // don't bother searching the empty string
					}
					if (contents.indexOf(search_terms[i]) == -1) {
						return;	// term is not found
					}
				}
				// show only when ALL terms are found
				found = true;
				$(this).show();
			});
		if (!found) {
			$('#nodata').show();
		} else {
			$('#nodata').hide();
		}
	});
	
	// events triggering data update
	const SPACE = 32;
	$('#data_search').keyup(function(ev) {
		if (ev.keyCode === SPACE) {
			$('#data_searcher').trigger('submit');
		}
	});
	
	$('#data_search').blur(function(ev) {
		$('#data_searcher').trigger('submit');
	});
	
	$('#col_names, #from_date, #to_date').change(function() {
		$('#data_searcher').trigger('submit');
	});
	
	// in case of form autocomplete, 
	// we don't want to show erronous output
	$('#data_searcher').trigger('submit');
});

</script>
<?php endif; ?>
