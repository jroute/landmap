<?php 

include "../config/default.php"; 
include "../config/database.php"; 
include "../class/area.cls.php"; 



	$q = $_POST['q'];

	$area = new Area();	

	$ri = $area->getRI($q);

	foreach($ri as $i=>$data){
		$ri[$i] = $data;	
		$ri[$i]['RI_NM'] = iconv('euc-kr','utf-8',$data['RI_NM']);
	}
	echo json_encode($ri);
	
	$area->close();
