<?php 

include "../config/default.php"; 
include "../config/database.php"; 
include "../class/gongsi_jiga.cls.php"; 



	$umd = $_POST['umd'];
	$ri = $_POST['ri'];
	$g = $_POST['g'];
	$s = $_POST['s'];			
	$e = $_POST['e'];				

	
	$jiga = new GongsiJiga();	

	echo json_encode($jiga->getYearJiga($umd,$ri,$g,$s,$e));
	
	$jiga->close();
