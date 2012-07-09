<?php 

include "../config/default.php"; 
include "../config/database.php"; 
include "../class/area.cls.php"; 
include "../class/gongsi_jiga.cls.php"; 




	$umd = trim($_POST['umd']);
	$ri = trim($_POST['ri']);
	$g = trim($_POST['g']);
	$s = trim($_POST['s']);			
	$e = trim($_POST['e']);				


	$jiga = new GongsiJiga();	

//	$data['jiga'] = $jiga->getAreaJiga($cd);
	$data['jiga'] = $jiga->getAreaJiga($umd,$ri,$g,$s,$e);

	$area = new Area();	
	$addr = $area->getAddr($umd,$ri);

		$addr['SGG_NM'] = iconv('euc-kr','utf-8',$addr['SGG_NM']);
		$addr['UMD_NM'] = iconv('euc-kr','utf-8',$addr['UMD_NM']);
		$addr['RI_NM'] = iconv('euc-kr','utf-8',$addr['RI_NM']);				
	$data['addr'] = $addr;	
	
	
	echo json_encode($data);
	
	$jiga->close();
