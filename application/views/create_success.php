
<p>You've successfully created a form! </p>
<p>
<?php $urlName = '/' . str_replace(' ', '-', strtolower($form_name)); ?>
Click <?php echo anchor('forms/fill/' . $form_id . $urlName, 'here'); ?> to view the form. 
Click <?php echo anchor('admin/edit/' . $form_id . $urlName, 'here'); ?> to edit it.
</p>