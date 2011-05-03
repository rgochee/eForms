
<h2>Available Forms</h2>

<ul id="forms_list">
<?php foreach($forms as $form): ?>
	<li class="form"><?=anchor('forms/fill/'.$form->id, $form->name)?></li>
<?php endforeach ?>
</ul>