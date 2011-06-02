<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo getTitle(); ?></title>

<meta name="description" content="Web-based Form Management System" />
<meta name="keywords" content="Aerospace, forms, form management" />
<meta name="author" content="CS 130 eForms Team" />
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<link rel="stylesheet" href="<?php echo base_url(); ?>static/css/screen.css"/>

</head>
<body>

<div id="header">
	<img style="width: 50px; height: 40px;"
 	alt="AeroSpace Corp Logo" src="<?php echo base_url(); ?>static/css/images/aero.gif">
	<h1 id="logo"><a href="<?php echo base_url(); ?>">eForms</a></h1>
</div>

<div id="content">
	<div id="toolbar">
		<div id="menubar" class="left">
		<ul>
		<li><a href="<?php echo base_url(); ?>" title="Home page"><span>Home</span></a></li>
		<li><a href="<?php echo base_url(); ?>forms/browse" title="Browse forms"><span>Browse</span></a></li>
			
		<?php if($this->session->userdata('admin')): ?>
		<li><a href="<?php echo base_url(); ?>admin/create" title="Create form"><span>Create</span></a></li>
		<?php endif ?>
		</ul>
		</div>
		
		<div class="right">
			<form id="search_form" action="<?php echo base_url(); ?>forms/search" method="get">
				<input type="text" name="find" value="" size="30">
				<input type="submit" value="Find Forms" />
			</form>
		</div>
		<div class="clear"></div>
	</div>

	<div id="content_inner">
