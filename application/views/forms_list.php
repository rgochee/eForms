
<style type="text/css">
#forms_list { list-style: none; }
.form { display: block; padding: 5px; border-bottom: 1px solid #CCCCCC; }
.form_info, .last_mod { color: #555555; }
.form_link { font-weight: bold; color: #2E3884; text-decoration: none; font-size: 130%; }
.form_info a { color: #2E3884; text-decoration: none; }
.form_link:hover, .form_info a:hover { text-decoration: underline; }
</style>

<h2>Available Forms</h2>

<?php echo $this->pagination->create_links(); ?>
<ul id="forms_list">
<?php if (!empty($forms)): ?>
<?php foreach($forms as $form): ?>
	<li class="form">
		<?php echo anchor(niceFormUri('forms/fill/', $form->id, $form->name), $form->name, 'title="Fill form" class="form_link"'); ?>
		<span class="last_mod">Last modified: <?php echo date('M d, Y', $form->time_created); ?></span>
		
		<div class="form_info">
		<?php if($this->session->userdata('admin')): ?>
		<?php echo anchor(niceFormUri('admin/edit/', $form->id, $form->name), 'edit', 'title="Edit the form"'); ?>
		<?php echo anchor(niceFormUri('admin/data/', $form->id, $form->name), 'data', 'title="View form responses"'); ?>
		<?php endif ?>
		</div>
		
	</li>
<?php endforeach ?>
<?php else: ?>
	<li>No forms!</li>
<?php endif ?>
</ul>
<?php echo $this->pagination->create_links(); ?>
