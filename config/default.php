<?php
header("Content-Type: text/html; charset=EUC-KR");

//�׽�Ʈ ��� - ��ÿ��� false�� �� �����Ͻñ� �ٶ��ϴ�.
$testmode = true;

if( $testmode ){
	error_reporting(0);
	ini_set('display_errors','On');
}


//������ Ÿ��Ʋ ��
$title = "���ߺδ�� ������ �ý���";

//���̹� �� API Key
$naver_api_key = "416a1bccd9c457243c03e597533b41a4";

//������ ���̵�, ��й�ȣ ����
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