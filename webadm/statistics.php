<?php 
include "inc/auth.php";

include "../inc/header.php";
	
$year = $_GET['year'];
$month = $_GET['month'];

$lastDay = date('t', mktime(0, 0, 0, $month, 1, $year));

if( $year=='' ) $year = date('Y');
if( $month=='' ) $month = date('m');





$m=2;
include "menu.php";?>
<div id="content">
<form id="ym">
<select name="year">
<?php for($y = date('Y'); $y >= 2012 ; $y-- ):?>
	<option value="<?php echo $y;?>" <?php if( $year == $y):?>selected="selected"<?php endif;?>><?php echo $y;?></option>
<?php endfor;?>
</select>년
<select name="month">
<?php for($m = 1; $m <= 12 ; $m++ ):?>
	<option value="<?php echo $m;?>" <?php if( $month == $m):?>selected="selected"<?php endif;?>><?php echo $m;?></option>
<?php endfor;?>
</select>월
<input type="submit" value="검색">
</form>

<fieldset>
<legend>접속 통계</legend> 

<table class="tbl">
<thead>
<tr>
<th>날짜</th>
<th>접속카운터</th>
</tr>
</thead>
<tbody>
<?php

$res1 = mysql_query("select date,count from access_counter where left(date,7)='".$year."-".sprintf('%02d',$month)."' order by date desc",$conn) or die(mysql_error());
$counter1 = array();
while($data = mysql_fetch_assoc($res1)){
?>
<tr><td width="200" align="center"><?php echo $data['date'];?></td>
<td><?php echo (int)@$data['count'];?></td></tr>
<?php 
}
?>
</tbody>
</table>
<div class="wa-submit-area">
	<button type="button" id="down-access">엑셀데이터 다운로드</button>
	<button type="button" id="del-access">데이터 삭제</button>
</div>
</form>
</fieldset>



<form id="ym">
<select name="year">
<?php for($y = date('Y'); $y >= 2012 ; $y-- ):?>
	<option value="<?php echo $y;?>" <?php if( $year == $y):?>selected="selected"<?php endif;?>><?php echo $y;?></option>
<?php endfor;?>
</select>년
<select name="month">
<?php for($m = 1; $m <= 12 ; $m++ ):?>
	<option value="<?php echo $m;?>" <?php if( $month == $m):?>selected="selected"<?php endif;?>><?php echo $m;?></option>
<?php endfor;?>
</select>월
<input type="submit" value="검색">
</form>

<fieldset>
<legend>비용 산정 통계</legend> 

<table class="tbl">
<thead>
<tr>
<th width="200" align="center">날짜</th>
<th>주소</th>
<th>카운터</th>
</tr>
</thead>
<tbody>
<?php
$res2 = mysql_query("select date,address,count from calculate_counter where left(date,7)='".$year."-".sprintf('%02d',$month)."' order by date desc",$conn) or die(mysql_error());

$counter2 = array();
while($data = mysql_fetch_assoc($res2)){
?>
<tr><td width="200" align="center"><?php echo $data['date'];?></td>
<td><?php echo $data['address'];?></td>
<td><?php echo (int)@$data['count'];?></td></tr>
<?php
}
?>

</tbody>
</table>

<div class="wa-submit-area">
	<button type="button" id="down-calculate">엑셀데이터 다운로드</button>
	<button type="button" id="del-calculate">데이터 삭제</button>
</div>

</fieldset>

</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#down-access').click(function(){
		location.href = 'excel.php?t=access';
	});
	$('#down-calculate').click(function(){
		location.href = 'excel.php?t=calculate';	
	});	
	
	$('#del-access').click(function(){
		if( confirm('접속통계 데이터를 삭제하시겠습니까?\n\n*삭제된 데이터는 복구 할 수 없습니다.') ){
			$.post('delete.php',{t:'access'},function(data){
				if( data == 'ok' ){
					alert('삭제되었습니다.');
					location.reload();
				}else{
					alert('삭제 실패');					
				}
			});			
		}
	});
	$('#del-calculate').click(function(){
		if( confirm('비용 산정 통계 데이터를 삭제하시겠습니까?\n\n*삭제된 데이터는 복구 할 수 없습니다.') ){
			$.post('delete.php',{t:'calculate'},function(data){
				if( data == 'ok' ){
					alert('삭제되었습니다.');
					location.reload();					
				}else{
					alert('삭제 실패');					
				}
			});
		}	
	});		
});
</script>

<?php include "../inc/footer.php";?>