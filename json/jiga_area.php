<?php 

include "../config/default.php"; 
include "../config/database.php"; 
include "../class/area.cls.php";
include "../class/info.cls.php"; 
include "../class/gongsi_jiga.cls.php"; 




	$umd = trim($_POST['umd']);
	$ri = trim($_POST['ri']);
	$g = trim($_POST['g']);
	$s = trim($_POST['s']);			
	$e = trim($_POST['e']);				
	
	
	
	$info = new Info();

	$jimok = $info->getProperty('01');//지목	
	$use = $info->getProperty('02');//용도
	$state = $info->getProperty('06');//토지이용현황

	$jiga = new GongsiJiga();	

//	$data['jiga'] = $jiga->getAreaJiga($cd);
	$data['jiga'] = $jiga->getAreaJiga($umd,$ri,$g,$s,$e);
	if( $data['jiga'] ){
				$data['jiga']['STATE'] = iconv('euc-kr','utf-8',$state[$data['jiga']['LAND_USE']]);	
  			$data['jiga']['USE'] = iconv('euc-kr','utf-8',$use[$data['jiga']['USE_REGN1']]);	
   			$data['jiga']['JIMOK'] = iconv('euc-kr','utf-8',$jimok[$data['jiga']['JIMOK']]);	
	}
	$area = new Area();	
	$addr = $area->getAddr($umd,$ri);

		$addr['SGG_NM'] = iconv('euc-kr','utf-8',$addr['SGG_NM']);
		$addr['UMD_NM'] = iconv('euc-kr','utf-8',$addr['UMD_NM']);
		$addr['RI_NM'] = iconv('euc-kr','utf-8',$addr['RI_NM']);	
		
			
	$data['addr'] = $addr;	
	
	
	echo json_encode($data);
	
	$jiga->close();
