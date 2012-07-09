<?php 

include "../config/default.php"; 
include "../config/database.php"; 
include "../class/area.cls.php"; 
include "../class/gongsi_jiga.cls.php"; 




	$umd = trim($_POST['umd']);
	$ri = trim($_POST['ri']);
	$use = trim($_POST['use']);
	$state = trim($_POST['state']);			
				
	$jiga = new GongsiJiga();	

	$umd = '107';
	$ri = '00';
	$use = '13';
	$state = '150';

	$data['jiga'] = $jiga->getStateJiga($umd,$ri,$use,$state);

	$area = new Area();	
	$addr = $area->getAddr($umd,$ri);
//	SGG_NM,UMD_CD,UMD_NM,RI_CD,RI_NM
		$addr['SGG_NM'] = iconv('euc-kr','utf-8',$addr['SGG_NM']);
		$addr['UMD_NM'] = iconv('euc-kr','utf-8',$addr['UMD_NM']);		 		
		$addr['RI_NM'] = iconv('euc-kr','utf-8',$addr['RI_NM']);		 

	$data['addr'] = $addr;	
	

	echo json_encode($data);
	
	$jiga->close();
