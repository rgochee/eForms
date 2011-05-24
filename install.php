<html>
<head>
<title>Install</title>

<link rel="stylesheet" href="static/css/screen.css" />
<style type="text/css">
body { background-color: #CCCCCC; }
#content_inner { margin-top: 25px; width: 350px; background-color: #FFFFFF; }
input[type=submit] { display: block; }
input[type=text], input[type=password] { width: 95%; height: 30px; }
</style>

</head>
<body>
<div id="content_inner">
<h2>INSTALL</h2>
<?php
if (isset($_POST['submit'])):
	$templateFile = 'application/config/database.template.php';
	$configFile = 'application/config/database.php';
	$fh = fopen($templateFile, 'rb'); 
	$configData = fread($fh, filesize($templateFile)); 
	fclose($fh); 

	$configData = str_replace('{{HOSTNAME}}', $_POST['mysql_host'], $configData);
	$configData = str_replace('{{USERNAME}}', $_POST['mysql_user'], $configData);
	$configData = str_replace('{{PASSWORD}}', $_POST['mysql_pass'], $configData);
	$configData = str_replace('{{DATABASE}}', $_POST['mysql_dbse'], $configData);
	//$configData = str_replace('{{DBPREFIX}}', $_POST['mysql_dbpr'], $configData);
	
	file_put_contents($configFile, $configData);

	$link = mysql_connect($_POST['mysql_host'], $_POST['mysql_user'], $_POST['mysql_pass']);
	mysql_select_db($_POST['mysql_dbse']);
	$schema = file_get_contents('application/core/schema.sql');
	$sql = explode(';',$schema);
	
	echo '<pre>';
	foreach (array_slice($sql, 0, -1) as $query)
	{
		echo mysql_query($query) ? 'Success' : mysql_error();
		echo "\n";
	}
	echo '</pre>';
?>
	<p>If this works, please delete this file for security!</p>
<?php else: ?>
	<form action="" method="post">
		<label class="field_label" for="mysql_host">MySQL host:</label>
		<input type="text" id="mysql_host" name="mysql_host" />
		<label class="field_label" for="mysql_user">MySQL user:</label>
		<input type="text" id="mysql_user" name="mysql_user" />
		<label class="field_label" for="mysql_pass">MySQL password:</label>
		<input type="password" id="mysql_pass" name="mysql_pass" />
		<label class="field_label" for="mysql_dbse">MySQL database:</label>
		<input type="text" id="mysql_dbse" name="mysql_dbse" />
		<?php
		/* MySQL database prefix:
		<input type="text" name="mysql_dbpr" /> */
		?>
		<input type="submit" name="submit" value="Install" />
	</form>
<?php endif; ?>
</div>
</body>
</html>