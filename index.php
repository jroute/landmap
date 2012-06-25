<?php include "inc/header.php"; 
	
	


	$mdb = odbc_connect("DANGGIN_INFO", "", "");

    $query      = "SELECT UMD_NM FROM C_AREACODE WHERE SIDOSGG_CD='44270'";
    $result     = odbc_exec($mdb, $query);


$umd = array();
while( $row = odbc_fetch_array($result) ) {
	if( trim($row['UMD_NM']) == '' ) continue;
	$umd[] = $row['UMD_NM'];
}


$umd = array_unique($umd);

asort($umd);



odbc_free_result($result);


odbc_close($mdb); 
?>
<script type="text/javascript">
$(document).ready(function(){
	
	$('#UMD').change(function(){
		var val = $(this).val();
		if( val == '' ){
			return;
		}
		$.post('/json/ri.php',{q:val},function(json){
			i = 1;
			$('#RI > option').remove();
			$('#RI').append($('<option value="">선택</option>'));
			for(idx in json){
				document.getElementById('RI').options[i++] = new Option(json[idx],json[idx]);
			}
		},'json');
	});
});
</script>
<form>
<select id="UMD">
<option value="">선택</option>
<?php foreach($umd as $val):?>
<option value="<?=$val?>"><?=$val?></option>
<?php endforeach;?>
</select>

<select id="RI">
<option value="">선택</option>
</select>
</form>

<?php include "inc/footer.php"; ?>