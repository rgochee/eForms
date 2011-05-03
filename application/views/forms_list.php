
<h2>Available Forms</h2>

<ul id="forms_list">
<?php if (!empty($forms)): ?>
<?php foreach($forms as $form): ?>
	<li class="form"><?=anchor('forms/fill/'.$form->id, $form->name)?></li>
<?php endforeach ?>
<?php else: ?>
	<li>No forms!</li>
<?php endif ?>
</ul>