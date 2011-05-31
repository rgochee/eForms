<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>eForms <?=tryPrint($title)?></title>

<link rel="stylesheet" href="<?= base_url() ?>static/css/screen.css"/>

</head>
<body>

<div id="header">
	<img style="width: 50px; height: 40px;"
 	alt="AeroSpace Corp Logo" src="<?= base_url() ?>static/css/images/aero.gif">
	<h1 id="logo"><a href="<?= base_url() ?>">eForms</a></h1>
</div>

<div id="content">
	<div id="toolbar">
		<div id="menubar" class="left">
		<ul>
		<li><a href=<?= base_url()?> target="" title="Home page"><span>Home</span></a></li>
		<li><a href=<?= base_url()?>forms/browse target="" title="Browse forms"><span>Browse</span></a></li>
			
		<?php if($this->session->userdata('admin')): ?>
		<li><a href=<?= base_url()?>admin/create target="" title="Create form"><span>Create</span></a></li>
		<?php endif ?>
		</ul>
		</div>
		
		<div class="right">
			<form id="search_form" action='<?= base_url() ?>forms/search' method="get">
				<input type="text" name="find" value="" size="30">
				<input type="submit" name="Submit" value="Find Forms" />
			</form>
		</div>
		<div class="clear"></div>
	</div>

	<div id="content_inner">
