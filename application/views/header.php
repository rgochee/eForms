<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo getTitle(); ?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>static/css/screen.css"/>

</head>
<body>

<div id="header">
    <h1 id="logo"><a href="<?php echo base_url(); ?>">eForms</a></h1>
</div>

<div id="content">
    <div id="toolbar">
        <div id="menubar" class="left">
            <?php echo anchor('', 'Home', 'title="Home page"'); ?>
            <?php echo anchor('forms/browse', 'Browse', 'title="Browse all of the forms"'); ?>

            <?php if($this->session->userdata('admin')): ?>
            <?php echo anchor('admin/create', 'Create', 'title="Create a form"'); ?>
            <?php endif; ?>
        </div>
        <div class="right">
            <form action="" method="get">
                <input type="text" size="30" />
                <input type="submit" value="Search Forms" />
            </form>
        </div>
        <div class="clear"></div>
    </div>

    <div id="content_inner">
