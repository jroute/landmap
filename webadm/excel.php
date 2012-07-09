<?php
include "inc/auth.php";
include $_SERVER['DOCUMENT_ROOT']."/config/default.php";
include $_SERVER['DOCUMENT_ROOT']."/config/database.php";


if( $_GET['t'] == 'access' ){
	$filename = "접속통계-".date('Ymd');
}else if( $_GET['t'] == 'calculate' ){
	$filename = "비용산정통계-".date('Ymd');
}


// Must be fresh start
  if( headers_sent() )
    die('Headers Sent');

  // Required for some browsers
  if(ini_get('zlib.output_compression'))
    ini_set('zlib.output_compression', 'Off'); 
    
    header("Pragma: public"); // required
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false); // required for certain browsers
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"".($filename.".xls")."\";" );
    header("Content-Transfer-Encoding: binary");
//    header("Content-Length: ".$fsize);

if( $_GET['t'] == 'access' ){
?>

<table>
<thead>
<tr>
<th>날짜</th>
<th>접속카운터</th>
</tr>
</thead>
<tbody>
<?php

$res1 = mysql_query("select date,count from access_counter order by date desc",$conn) or die(mysql_error());
while($data = mysql_fetch_assoc($res1)){
?>
<tr><td><?php echo $data['date'];?></td><td align="right"><?php echo (int)@$data['count'];?></td></tr>
<?php 
}
?>
</tbody>
</table>
<?php
}else if( $_GET['t'] == 'calculate' ){
?>
<table>
<thead>
<tr>
<th>날짜</th>
<th>주소</th>
<th>카운터</th>
</tr>
</thead>
<tbody>
<?php
$res2 = mysql_query("select date,address,count from calculate_counter order by date desc",$conn) or die(mysql_error());

while($data = mysql_fetch_assoc($res2)){
?>
<tr><td><?php echo $data['date'];?></td>
<td><?php echo $data['address'];?></td>
<td align="right"><?php echo (int)@$data['count'];?></td></tr>
<?php
}
?>

</tbody>
</table>
<?php
}

