<?php 
include "inc/auth.php";
include $_SERVER['DOCUMENT_ROOT']."/config/default.php";
include $_SERVER['DOCUMENT_ROOT']."/config/database.php";
include $_SERVER['DOCUMENT_ROOT']."/class/setup.cls.php";


if( $_POST['mode'] == 'calcost' ){

	$setup = new Setup();

	$setup->saveCalCost($_POST['data']);

}else if( $_POST['mode'] == 'increase' ){

	$setup = new Setup();

	$setup->saveIncrease($_POST['data']);
	
}else if( $_POST['mode'] == 'mdb' ){

	if( $_FILES['info']['size'] > 0 ){
		if( !move_uploaded_file($_FILES['info']['tmp_name'], $path_mdb."INFO.mdb") ){
			die('파일업로드 실패<br /><a href="setup.php">이전페이지</a>');			
		}
		chmod($path_mdb."INFO.mdb", 0777);		
		
	}else	if( $_FILES['jiga']['size'] > 0 ){
		if( !move_uploaded_file($_FILES['jiga']['tmp_name'], $path_mdb."GONGSIJIGA.mdb") ){
			die('파일업로드 실패<br /><a href="setup.php">이전페이지</a>');
		}
		chmod($path_mdb."GONGSIJIGA.mdb", 0777);
	}
	
}else if( $_POST['mode'] == 'reduction' ){
	
	$res = mysql_query("insert into reduction (item,content,rate,created) values ('".$_POST['item']."','".$_POST['content']."',".(int)$_POST['rate'].",sysdate())",$conn);
	if( !$res ){
		die('감면사항 등록 실패<br /><a href="setup.php">이전페이지</a>');
	}
	
}else if( $_POST['mode'] == 'delete-reduction' ){
	$res = mysql_query("delete from reduction where rid=".(int)$_POST['rid']);
	if( !$res ){
		die('감면사항 삭제 실패<br /><a href="setup.php">이전페이지</a>');
	}else{
		echo 'ok';
	}
	exit;
}


header("location:setup.php");