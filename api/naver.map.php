<?php
include "../config/default.php";

if( trim($_POST['query']) || trim($_GET['query'])){
	if( trim($_POST['query']) )
		$query  = trim($_POST['query']);
		
	if( trim($_GET['query']) )
		$query  = trim($_GET['query']);
		
				
	$q=1;	
}else{
	$query = "´çÁø½Ã";
	$q=0;
}

$api_url = "http://openapi.map.naver.com/api/geocode.php?encoding=euc-kr&coord=latlng&key=".$naver_api_key."&query=".$query;


$xml = simplexml_load_file($api_url);
$xml->item->q = $q;

echo json_encode($xml->item);