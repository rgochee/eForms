<html>
<head>
<title>Install</title>
</head>
<body>
INSTALL
<?php
if (isset($_POST['submit'])):
	$configFile = 'application/config/database.php';
	$fh = fopen($configFile, 'rb'); 
	$configData = fread($fh, filesize($configFile)); 
	fclose($fh); 

	$s1 = '/db\[\'default\'\]\[\'';
	$s1a = 'db[\'default\'][\'';
	$s2 = '\'\] = \'';
	$s2a = '\'] = \'';
	$s3 = '[^\']*';
	$s4 = '\';/im';
	$s4a = '\';';

	$configData = preg_replace($s1.'(hostname)'.$s2.$s3.$s4, $s1a.'$1'.$s2a.$_POST['mysql_host'].$s4a, $configData);
	$configData = preg_replace($s1.'(username)'.$s2.$s3.$s4, $s1a.'$1'.$s2a.$_POST['mysql_user'].$s4a, $configData);
	$configData = preg_replace($s1.'(password)'.$s2.$s3.$s4, $s1a.'$1'.$s2a.$_POST['mysql_pass'].$s4a, $configData);
	$configData = preg_replace($s1.'(database)'.$s2.$s3.$s4, $s1a.'$1'.$s2a.$_POST['mysql_dbse'].$s4a, $configData);
	//$configData = preg_replace($s1.'(dbprefix)'.$s2.$s3.$s4, $s1a.'$1'.$s2a.$_POST['mysql_dbpr'].$s4a, $configData);
	file_put_contents($configFile, $configData);

	$link = mysql_connect($_POST['mysql_host'], $_POST['mysql_user'], $_POST['mysql_pass']);
	mysql_select_db($_POST['mysql_dbse']);
	$schema = file_get_contents('application/core/schema.sql');
	$sql = explode(';',$schema);
	foreach ($sql as $query)
	{
		echo mysql_query($query) ? '1' : '0';
	}
?>
If this works, please delete this file for security!
<?php
else:
?>
<form action="" method="post">
MySQL host:
<input type="text" name="mysql_host" />
MySQL user:
<input type="text" name="mysql_user" />
MySQL password:
<input type="text" name="mysql_pass" />
MySQL database:
<input type="text" name="mysql_dbse" />
<?php
/* MySQL database prefix:
<input type="text" name="mysql_dbpr" /> */
?>
<input type="submit" name="submit" />
</form>
<?php
endif;
?>
</body>
</html>