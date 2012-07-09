<?php 
include "inc/auth.php";
include "../inc/header.php";
	

$setup = new Setup();

$inreases = $setup->getIncrease();	

$calcost = $setup->getCalCost();	

?>
<script type="text/javascript">
$(document).ready(function(){

	
	$('.btn-delete').click(function(){
		$this = $(this);
		if( confirm('삭제하시겠습니까?') == true ){
			$.post('./save.php',{mode:'delete-reduction',rid:$(this).val()},function(res){
				if( res == 'ok' ){
					var idx = $('.btn-delete').index($this);
					$('tbl-reduction>tbody>tr:eq('+idx+')').remove();
				}
			});
		}
	});
});
</script>


<?php 
$m=1;
include "menu.php";
?>
<div id="content">


<fieldset>
<legend>정상지가 상승분 데이터 관리</legend> 
<form  method="post" action="save.php">
<input type="hidden" name="mode" value="increase" />
<ul>
<li>1월 <input type="text" size="5" name="data[]" value="<?php echo $inreases[0];?>" /></li>
<li>2월 <input type="text" size="5" name="data[]" value="<?php echo $inreases[1];?>" /></li>
<li>3월 <input type="text" size="5" name="data[]" value="<?php echo $inreases[2];?>" /></li>
<li>4월 <input type="text" size="5" name="data[]" value="<?php echo $inreases[3];?>" /></li>
<li>5월 <input type="text" size="5" name="data[]" value="<?php echo $inreases[4];?>" /></li>
<li>6월 <input type="text" size="5" name="data[]" value="<?php echo $inreases[5];?>" /></li>
<li>7월 <input type="text" size="5" name="data[]" value="<?php echo $inreases[6];?>" /></li>
<li>8월 <input type="text" size="5" name="data[]" value="<?php echo $inreases[7];?>" /></li>
<li>9월 <input type="text" size="5" name="data[]" value="<?php echo $inreases[8];?>" /></li>
<li>10월 <input type="text" size="5" name="data[]" value="<?php echo $inreases[9];?>" /></li>
<li>11월 <input type="text" size="5" name="data[]" value="<?php echo $inreases[10];?>" /></li>
<li>12월 <input type="text" size="5" name="data[]" value="<?php echo $inreases[11];?>" /></li>
</ul>
<div class="wa-submit-area"><input type="submit" value="수정" /></div>
</form>
</fieldset>


<fieldset>
<legend>비용 산정 데이터 관리</legend> 
<form  method="post" action="save.php">
<input type="hidden" name="mode" value="calcost" />
<ul>
<li>연이율 <input type="text" size="5" name="data[]" value="<?php echo $calcost[0];?>" /></li>
<li>표준개발비용 <input type="text" size="15" name="data[]" value="<?php echo $calcost[1];?>" /></li>
</ul>

<div class="wa-submit-area"><input type="submit" value="수정" /></div>

</form>
</fieldset>

<!--

<fieldset>
<legend>데이터베이스 관리</legend> 
<form  method="post" action="save.php" enctype="multipart/form-data">
<input type="hidden" name="mode" value="mdb"/>
<ul>

<li>INFO MDB <input type="file" name="info" /></li>
<li>공시지가 MDB <input type="file" name="jiga" /></li>
</ul>

<div class="wa-submit-area"><input type="submit" value="수정" /></div>
</form>
</fieldset>
-->

<fieldset>
<legend>감면사항 데이터 관리</legend> 
<form method="post" action="save.php">
<input type="hidden" name="mode" value="reduction"/>


<table id="tbl-reduction" >
<thead>
<tr>
<th>감면사항</th><th>감면내용</th><th>이율</th><th>-</th>
</tr>
<tr>
<td><input type="text" size="30" name="item"></td>
<td><input type="text" size="60" name="content"></td>
<td><input type="text" size="5" name="rate"></td>
<td><input type="submit" value="추가"></td>
</tr>
</thead>
<tbody>
<?php
$res = mysql_query("select `rid`,`item`,content,rate from reduction order by item desc",$conn) or die(mysql_error($conn));
while($data = mysql_fetch_assoc($res)):
?>
<tr>
<td><?php echo $data['item'];?></td>
<td><?php echo $data['content'];?></td>
<td><?php echo $data['rate'];?>%</td>
<td><button type="button" class="btn-delete" value="<?php echo $data['rid'];?>">삭제</button></td>
</tr>
<?php endwhile;?>
</tbody>
</table>

</form>
</fieldset>
</div>
<?php include "../inc/footer.php";?>