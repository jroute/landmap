<?php 

include "../config/default.php"; 
include "../config/database.php"; 



	$q = $_POST['q'];
	
	$mdb = odbc_connect("DANGGIN_INFO", "", "");


    $query      = "SELECT RI_NM FROM C_AREACODE WHERE UMD_NM='".$q."'";

    $result     = odbc_exec($mdb, $query);



$umd = array();
while( $row = odbc_fetch_array($result) ) {
	if( trim($row['RI_NM']) == '' ) continue;
	$umd[] = $row['RI_NM'];
}

$umd = array_unique($umd);

asort($umd);
echo json_encode($umd);


odbc_free_result($result);
odbc_close($mdb); 
