<?php 

include "../config/default.php"; 
include "../config/database.php"; 
include "../class/area.cls.php"; 



	$q = $_POST['q'];

	$area = new Area();	

	$ri = $area->getRI($q);

	foreach($ri as $cd=>$nm){
		$ri[$cd] = iconv('euc-kr','utf-8',$nm);
	}
	echo json_encode($ri);
	
	$area->close();
