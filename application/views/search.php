<style type="text/css">
#forms_list { list-style: none; }
.form_info, .last_mod { color: #555555; }
.form_link { font-weight: bold; color: #2E3884; text-decoration: none; font-size: 130%; }
.form_info a { color: #2E3884; text-decoration: none; }
.form_link:hover, .form_info a:hover { text-decoration: underline; } 
</style>

<?php /* Page Title */ ?>
<h2>Search Results</h2>

  <?php /* Retrieve query from search field in header */ ?>
  <?php $var = @$_GET['find']; ?>

<?php /* Page numbers if 20+ forms */ ?>
<?php echo $this->pagination->create_links(); ?>

<ul id="forms_list">
<?php echo form_error('find'); ?>
<?php if (!empty($forms)): ?>
<?php /* For each form, it tries to match the form name with the query, and if successful, it'll display the results accordingly */ ?>
<?php foreach($forms as $form): ?>
	<li class="form"> 
                <?php /* Lowercase both the form name and query */ ?>
		<?php $urlName = '/' . str_replace(' ', '-', strtolower($form->name)); ?>
		<?php $query = '/' . str_replace(' ', '-', strtolower($var)); ?>
		<?php if(strlen(strstr($query,$urlName))>0): ?>  
			<?php echo anchor(nice_form_uri('forms/fill/', $form->id, $form->name), $form->name, 'title="Fill form" class="form_link"'); ?>

			<span class="last_mod">Last modified: <?php echo date('M d, Y', $form->time_created); ?></span>
			<div class="form_info">
                        <?php /* If this is an admin, it'll allow for editing and viewing data of filled out forms */ ?>
			<?php if($this->session->userdata('admin')): ?>
		<?php echo anchor(nice_form_uri('admin/edit/', $form->id, $form->name), 'edit', 'title="Edit the form"'); ?>
		<?php echo anchor(nice_form_uri('admin/data/', $form->id, $form->name), 'data', 'title="View form responses"'); ?>

		<?php endif ?>
		</div>
	</li>
	<?php endif ?>

<?php endforeach ?>
<?php /* In case no forms are found */ ?>
<?php else: ?>
	<li>No forms found!</li>
<?php endif ?>
</ul>
<?php echo $this->pagination->create_links(); ?>

