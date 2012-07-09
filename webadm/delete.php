<?php
include "inc/auth.php";
include $_SERVER['DOCUMENT_ROOT']."/config/default.php";
include $_SERVER['DOCUMENT_ROOT']."/config/database.php";


if( $_POST['t'] == 'access' ){
	if( mysql_query("truncate table access_counter",$conn) ){
		echo 'ok';
	}else{
		echo 'fail';		
	}
}else if( $_POST['t'] == 'calculate' ){
	if( mysql_query("truncate table calculate_counter",$conn) ){
		echo 'ok';
	}else{
		echo 'fail';		
	}
}

