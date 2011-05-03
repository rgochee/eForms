
<h2>Available Forms</h2>

<ul id="forms_list">
<?php foreach($forms as $form): ?>
	<li class="form"><?=anchor('forms/fill/'.$form->form_id, $form->form_name)?></li>
<?php endforeach ?>
</ul>