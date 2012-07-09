<?php


$config['odbc'] = array(
	'area'=>'LANDMAP_AREA',	
	'jiga'=>'LANDMAP_GONGSIJIGA'	
);


$config['mysql'] = array(
	'host'=>'127.0.0.1',
	'user'=>'root',
	'password'=>'landupdate',
	'database'=>'landmap_db'
);





$conn = mysql_connect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password']) or die(mysql_error($conn));
mysql_select_db($config['mysql']['database'], $conn) or die(mysql_error($conn));
mysql_query("set names euckr", $conn);