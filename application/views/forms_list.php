
<h2>Available Forms</h2>

<ul id="forms_list">
<?php if (!empty($forms)): ?>
<?php foreach($forms as $form): ?>
	<li class="form">
		<?php echo anchor('forms/fill/'.$form->id, $form->name); ?>
		
		<?php if($this->session->userdata('admin')): ?>
		(<?php echo anchor('admin/data/'.$form->id, 'View data', 'title="View data"'); ?>)
		<?php endif ?>
	</li>
<?php endforeach ?>
<?php else: ?>
	<li>No forms!</li>
<?php endif ?>
</ul>