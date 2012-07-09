<?php
header("Content-Type: text/html; charset=EUC-KR");

//테스트 모드 - 운영시에는 false로 꼭 변경하시기 바랍니다.
$testmode = true;

if( $testmode ){
	error_reporting(0);
	ini_set('display_errors','On');
}


//윈도우 타이틀 명
$title = "개발부담금 가산정 시스템";

//네이버 맵 API Key
$naver_api_key = "416a1bccd9c457243c03e597533b41a4";

//관리자 아이디, 비밀번호 설정
$webadm = array(
'user'=>'webadm',
'password'=>'1234'
);





$ROOT =  $_SERVER['DOCUMENT_ROOT'];

$path_mdb = $ROOT."/mdb/";


$lastDate = array(0,31,28,31,30,31,30,31,31,30,31,30,31);


function trace($log){
	global $testmode;
	
	if( $testmode ){
		$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/mdb/log','a');
		fwrite($fp, date('Y-m-d H:i:s').' : '.$log."\n");
		fclose($fp);
	}
}