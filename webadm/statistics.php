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
</select>��
<select name="month">
<?php for($m = 1; $m <= 12 ; $m++ ):?>
	<option value="<?php echo $m;?>" <?php if( $month == $m):?>selected="selected"<?php endif;?>><?php echo $m;?></option>
<?php endfor;?>
</select>��
<input type="submit" value="�˻�">
</form>

<fieldset>
<legend>���� ���</legend> 

<table class="tbl">
<thead>
<tr>
<th>��¥</th>
<th>����ī����</th>
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
	<button type="button" id="down-access">���������� �ٿ�ε�</button>
	<button type="button" id="del-access">������ ����</button>
</div>
</form>
</fieldset>



<form id="ym">
<select name="year">
<?php for($y = date('Y'); $y >= 2012 ; $y-- ):?>
	<option value="<?php echo $y;?>" <?php if( $year == $y):?>selected="selected"<?php endif;?>><?php echo $y;?></option>
<?php endfor;?>
</select>��
<select name="month">
<?php for($m = 1; $m <= 12 ; $m++ ):?>
	<option value="<?php echo $m;?>" <?php if( $month == $m):?>selected="selected"<?php endif;?>><?php echo $m;?></option>
<?php endfor;?>
</select>��
<input type="submit" value="�˻�">
</form>

<fieldset>
<legend>��� ���� ���</legend> 

<table class="tbl">
<thead>
<tr>
<th width="200" align="center">��¥</th>
<th>�ּ�</th>
<th>ī����</th>
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
	<button type="button" id="down-calculate">���������� �ٿ�ε�</button>
	<button type="button" id="del-calculate">������ ����</button>
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
		if( confirm('������� �����͸� �����Ͻðڽ��ϱ�?\n\n*������ �����ʹ� ���� �� �� �����ϴ�.') ){
			$.post('delete.php',{t:'access'},function(data){
				if( data == 'ok' ){
					alert('�����Ǿ����ϴ�.');
					location.reload();
				}else{
					alert('���� ����');					
				}
			});			
		}
	});
	$('#del-calculate').click(function(){
		if( confirm('��� ���� ��� �����͸� �����Ͻðڽ��ϱ�?\n\n*������ �����ʹ� ���� �� �� �����ϴ�.') ){
			$.post('delete.php',{t:'calculate'},function(data){
				if( data == 'ok' ){
					alert('�����Ǿ����ϴ�.');
					location.reload();					
				}else{
					alert('���� ����');					
				}
			});
		}	
	});		
});
</script>

<?php include "../inc/footer.php";?>