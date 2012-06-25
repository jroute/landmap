<?php
include "../config/default.php";

if( trim($_POST['query'])){
	$q=1;	
}else{
	$query = "당진시";
	$q=0;
}

$api_url = "http://openapi.map.naver.com/api/geocode.php?encoding=utf-8&coord=latlng&key=".$naver_api_key."&query=".$query;


$xml = simplexml_load_file($api_url);
$xml->item->q = $q;

echo json_encode($xml->item);