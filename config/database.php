<?php


$config['mdb'] = array(
		
);


$config['mysql'] = array(
	'host'=>'127.0.0.1',
	'user'=>'jroute',
	'password'=>'jroute',
	'database'=>'danggin_db'
);


function odbc_connect_custom($db)
{
	global $path_mdb;
	
// $dbdir is the location of your databases.
// The main thing to watch out for is permissions.
// The process your web server is running under must
// have read / write permissions to this folder

    $dbdir = $path_mdb;
    
    $cfg_dsn = "DRIVER={Microsoft Access Driver (*.mdb)};
        DBQ=".$dbdir.$db.".mdb;UserCommitSync=Yes;
        Threads=3;
        SafeTransactions=0;
        PageTimeout=5;
        MaxScanRows=8;
        MaxBufferSize=2048;
        DriverId=281;
        DefaultDir=".$dbdir;

// The DefaultDir setting will probably be ok if you have gone for 
// a typical installation

    $cfg_dsn_login = "";
    $cfg_dsn_mdp = "";

    return odbc_connect($cfg_dsn,$cfg_dsn_login,$cfg_dsn_mdp) or die("odbc error");
}



$conn = mysql_connect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password']) or die(mysql_error($conn));
mysql_select_db($config['mysql']['database'], $conn) or die(mysql_error($conn));