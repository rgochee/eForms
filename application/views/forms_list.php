
<h2>Available Forms</h2>

<ul id="forms_list">
<?php if (!empty($forms)): ?>
<?php foreach($forms as $form): ?>
	<li class="form">
		<?php $urlName = '/' . str_replace(' ', '-', strtolower($form->name)); ?>
		<?php echo anchor('forms/fill/' . $form->id . $urlName, $form->name, 'title="Fill form"'); ?>
		
		<?php if($this->session->userdata('admin')): ?>
		(<?php echo anchor('admin/edit/' . $form->id . $urlName, 'Edit data', 'title="Edit the form"'); ?>|
		<?php echo anchor('admin/data/' . $form->id . $urlName, 'View data', 'title="View form responses"'); ?>)
		<?php endif ?>
		
	</li>
<?php endforeach ?>
<?php else: ?>
	<li>No forms!</li>
<?php endif ?>
</ul>