<?php 
session_start();
include "../inc/header.php";

if(  $_POST['user'] && $_POST['passwd'] ){
	
	if( $_POST['user'] == $webadm['user'] && $_POST['passwd'] = $webadm['password'] ){
		$_SESSION['webadm'] = 'true';
		header('location: setup.php');		
	}else{
		echo '<div style="color:red">아이디와 비밀번호를 확인 하십시오</div>';
	}
}
?>

<div id="login-box">
<form method="post" action="login.php">
<ul>
<li><input type="text" name="user" /></li>
<li><input type="password" name="passwd" /></li>
<li><input type="submit" value="로그인" /></li>
</ul>
</form>
</div>

<?php 
include "../inc/footer.php";