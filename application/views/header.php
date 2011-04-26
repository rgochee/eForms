<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>eForms</title>

<link rel="stylesheet" href="<?=base_url() ?>static/css/screen.css"/>

</head>
<body>

<div id="header">
    <h1 id="logo"><a href="<?= base_url() ?>">eForms</a></h1>
</div>

<div id="content">
    <div id="toolbar">
        <div id="menubar" class="left">
            <?= anchor('', 'Browse Forms', 'title="Browse all of the forms"'); ?>

            <?php if($this->session->userdata('admin')): ?>
            <?= anchor('admin/create', 'Create Form', 'title="Create a form"'); ?>
            <? endif ?>
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