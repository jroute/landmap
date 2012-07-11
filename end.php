<?php include "inc/header.php"; 

if( isset($_POST['UMD2']) ){
	$UMD2 = $_POST['UMD2'];
}else{
	$UMD2 = $_POST['UMD'];
}
if( isset($_POST['UMD3']) ){
	$UMD3 = $_POST['UMD3'];
}else{
	$UMD3 = $_POST['UMD'];
}

if( isset($_POST['RI3']) ){
	$RI3 = $_POST['RI3'];
}else{
	$RI3 = $_POST['RI'];
}

if( isset($_POST['RI2']) ){
	$RI2 = $_POST['RI2'];
}else{
	$RI2 = $_POST['RI'];
}

if( isset($_POST['G3']) ){
	$G3 = $_POST['G3'];
}else{
	$G3 = $_POST['G'];
}

if( isset($_POST['S3']) ){
	$S3 = $_POST['S3'];
}else{
	$S3 = $_POST['S'];
}

if( isset($_POST['E3']) ){
	$E3 = $_POST['E3'];
}else{
	$E3 = $_POST['E'];
}

$counter = new Counter();

$area = new Area();
$umd = $area->getUMD();

$info = new Info();

$use = $info->getProperty('02');//용도
$state = $info->getProperty('06');//토지이용현황


$sdate = $_POST['sdate'];
list($sy,$sm,$sd) = explode('-',$sdate);

$lastDate[2] = date('t',mktime(0,0,0,2,1,$sy));

$open_area = (int)$_POST['open_area'];
$open_jiga = (int)$_POST['open_jiga'];

$open_total = $open_area*$open_jiga;


$setup = new Setup();

$increases = $setup->getIncrease();

$increases_total = 0;
//정상지가 상승분
trace('------------------------ start jiga ----------------------------------');

trace('sdate : '.$sdate);
trace('open total : '.$open_total." = $open_area*$open_jiga");
$acc_total = $open_total;
if( (int)$sm == 1 && (int)$sd == 1 ){}
else{
	$increases_total = 0;
	for($m = 1; $m<=(int)$sm ; $m++){
		if( $m == $sm ){
			$total = (int)($acc_total*$increases[$m-1]/100*((int)$sd)/$lastDate[$m]);
			trace("start :  $m MON1 ".$total." = (".$acc_total."*".$increases[$m-1]."/100*(".((int)$sd).")/".$lastDate[$m].")");
			$acc_total += $total;			
			$increases_total += $total;

		}else{
			$total = (int)($acc_total*$increases[$m-1]/100*$lastDate[$m]/$lastDate[$m]);			
			trace("start :  $m MON2 ".$total." = (".$acc_total."*".$increases[$m-1]."/100*(".($lastDate[$m]).")/".$lastDate[$m].")");			
			$acc_total += $total;
			$increases_total += $total;			

		}
		
	}
}
$open_cal_jiga =  $open_total+$increases_total;
trace("open jiga :  ".$open_cal_jiga." =  ".$open_total."+".$increases_total);			
?>


<script type="text/javascript">
$(document).ready(function(){



	//if( parseInt($('#open-area').val(),10) > 2700 ){
	//	alert('면적이 2700m2 넘었습니다. 표준개발비용으로 산정할 수 없습니다.');
	//}

	$('#lnk-info').click(function(){
		window.open('info2.php','info','width=820,height=700,scrollbars=yes');
	});

	$('.btn-back').click(function(){
		$('#form').attr('action','index.php');
		document.getElementById('form').submit();
		return false;
	});

		$('#setmap').live('click',function(){


		var addr = '당진시'+$('#open-address').val().replace(/\s/gi,'');
		addrString = $('#open-address').text();
		$.post('/api/naver.map.php',{query:addr},function(data){
			lng = data.point.x;
			lat =	data.point.y;
			oMap.clearOverlay();
				var oPoint = new nhn.api.map.LatLng(lat, lng);
				var oMarker = new nhn.api.map.Marker(oIcon, { title : '주소 : ' + addrString });
				oMarker.setPoint(oPoint);
				oMap.addOverlay(oMarker);
				oMap.setCenter(oPoint);
		},'json');

	});
	

	$('.seladdr').live('click',function(){
		var idx = $('.seladdr').index($(this));
		$('html').animate({scrollTop:300},1500);
		var addr = '당진시'+$('.addr:eq('+idx+')').text().replace(/\s/gi,'');
		addrString = $('.addr:eq('+idx+')').text();
		$.post('/api/naver.map.php',{query:addr},function(data){
			lng = data.point.x;
			lat =	data.point.y;
			oMap.clearOverlay();
				var oPoint = new nhn.api.map.LatLng(lat, lng);
				var oMarker = new nhn.api.map.Marker(oIcon, { title : '주소 : ' + addrString });
				oMarker.setPoint(oPoint);
				oMap.addOverlay(oMarker);
				oMap.setCenter(oPoint);
		},'json');
		
		$('#close-jiga').val($('.jiga:eq('+idx+')').text());

	});	

		var umd3 = $('#UMD').val();
		var umd2 = $('#UMD2').val();

		if( umd3 ){

			$.post('/json/ri.php',{q:umd3},function(json){
				try{
				var j = 1;
				$('#RI > option').remove();
				$('#RI').append($('<option value="">리 검색</option>'));
				if( json != false ){
					for(cd in json){

						if( '<?php echo $RI3;?>' == cd ){						
							//document.getElementById('RI').options[j++] = new Option(json[cd],cd,true);					
							$('#RI').append($('<option value="'+cd+'" selected="selected">'+json[cd]+'</option>'));					
						}else{
							//document.getElementById('RI').options[j++] = new Option(json[cd],cd);	
							$('#RI').append($('<option value="'+cd+'">'+json[cd]+'</option>'));	
						}
					}
					
					if($.browser.msie){
						document.getElementById('RI').value = '<?php echo $RI3;?>';
					}
				}
				}catch(e){alert(e.message);}
				
			},'json');
		}//end .post umd3
		
		if( umd2 ){
			$.post('/json/ri.php',{q:umd2},function(json){
				var i = 1;
				$('#RI2 > option').remove();
				$('#RI2').append($('<option value="">리 검색</option>'));
				if( json != false ){
					for(cd in json){
						if( '<?php echo $RI2;?>' == cd ){
							document.getElementById('RI2').options[i++] = new Option(json[cd],cd,true);					
						}else{
							document.getElementById('RI2').options[i++] = new Option(json[cd],cd);
						}
					}
					if($.browser.msie){
						document.getElementById('RI2').value = '<?php echo $RI2;?>'
					}					
				}
				
					//init 
				if( $('#USE').val() && $('#STATE').val()  ){
					loadStateData();
				}
			},'json');
		}//end .post umd2


	$('#UMD2').change(function(){
			$('#USE').val('');//reset;
			$('#STATE').val('');//reset;
	});
	
	$('#RI2').change(function(){
			$('#USE').val('');//reset;
			$('#STATE').val('');//reset;
	});
	
	$('#USE').change(function(){
			$('#STATE').val('');//reset;
	});
	$('#STATE').change(function(){
		var val = $(this).val();
				$('#jiga-area > tbody > tr').remove();
		if( val == '' ) return;
		
		if( $('#USE').val() == '' ){
			alert('용도지역을 먼저 선택하십시오');
			$('#USE').focus();
			$(this).val('');
			return;
		}

		loadStateData();
		
		
	});
			

	$('#btn-search').bind('click',function(){
	
	
			if( $('#UMD').val() == '' ){ alert('읍/면/동 검색을 선택하십시오'); $('#UMD').focus(); return; }
			if( $('#S').val() == '' ){ alert('지번을 입력하십시오'); $('#S').focus(); return; }									

			
			$.post('/json/jiga_area.php',{umd:$('#UMD').val(),ri:$('#RI').val(),g:$('#G').val(),s:$('#S').val(),e:$('#E').val()},function(json)		{
			
					if( json.jiga == false ){
						alert('데이터가 존재하지 않습니다.');
						return;
					}
					
					$('#close-jiga').val(numberFormat(json.jiga.JIGA)).focus();
		
			},'json');	
			
				
			return false;
	
		
		
	});	
		
	$('.submit').click(function(){
		$('.number').each(function(){
			$(this).val(unNumberFormat($(this).val()));
		});
		
		if( $('#close-jiga').val() == '0' ){
			alert('지가 금액을 확인하십시오');
			$('#close-jiga').focus();
			return;
		}
		document.getElementById('form').submit();
	});

});

function loadStateData(){

			$.post('/json/jiga_state.php',{umd:$('#UMD2').val(),ri:$('#RI2').val(),use:$('#USE').val(),state:$('#STATE').val()},function(json)		{
				$('#jiga-area > tbody > tr').remove();
					if( json.jiga.length == 0 ){
						alert('데이터가 존재하지 않습니다.');
						return;
					}



					gbn = '';
					if( $('input[type=hidden][name=G]').val() == '2') gbn=' 산';
					if( $('input[type=hidden][G]').val() == '3') gbn=' 가지번';
					if( $('input[type=hidden][G]').val() == '4') gbn=' 블럭';										
					bungi='';
					if( $.trim($('input[type=hidden][name=S]').val()) ) bungi = ' '+$.trim($('input[type=hidden][name=S]').val());
					if( $.trim($('input[type=hidden][name=E]').val()) ){
						if( bungi ){
							bungi = bungi+'-'+$.trim($('input[type=hidden][name=E]').val());						
						}else{
							bungi = ' '+$.trim($('input[type=hidden][name=E]').val());
						}				
					}
					
					addr = json.addr.UMD_NM+' '+json.addr.RI_NM + bungi;
					//alert($('#open-address').val() + ' ' + addr)
					if( $('#open-address').val() != addr ){
						addr = json.addr.UMD_NM+' '+json.addr.RI_NM;
					}		

					for(i = 0 ; i < json.jiga.length ; i++ ){
					var src = "<tr>"
	src += "<td width='55' height='30' align='center'>"+($('#jiga-area > tbody > tr').length+1)+"</td>";
	src += "<td width='270'>&nbsp;&nbsp;<span class='addr'>"+addr+ "</span></td>";
	
	src += "<td width='100' align='center'>"+$('#USE option:selected').text()+"</td>";
	src += "<td width='100' align='center'>"+$('#STATE option:selected').text()+"</td>";

	src += "<td width='70' align='center'>" + json.jiga[i].JIMOK+"</td>";	
	src += "<td width='100' align='right'><span class='area'>"+numberFormat(json.jiga[i].LAND_AREA)+"</span></td>";	
	
	src += "<td width='125' align='right'><span class='jiga'>"+numberFormat(json.jiga[i].JIGA)+"</span></td>";
	src += "<td width='70' align='center'><input type='radio' name='addr' class='seladdr'></td>";
	src += "																</tr>";
																	
					$('#jiga-area > tbody').append($(src));				
					}
			},'json');	
}
</script>
<form method="post" id="form" action="finish.php" onsubmit="return false;">
<input type="hidden" name="sdate" value="<?php echo $_POST['sdate'];?>" />
<input type="hidden" name="edate" value="<?php echo $_POST['edate'];?>" />

<input type="hidden" name="UMD" value="<?php echo $_POST['UMD'];?>" />
<input type="hidden" name="RI" value="<?php echo $_POST['RI'];?>" />

<input type="hidden" name="G" value="<?php echo $_POST['G'];?>" />
<input type="hidden" name="S" value="<?php echo $_POST['S'];?>" />
<input type="hidden" name="E" value="<?php echo $_POST['E'];?>" />


<input type="hidden" name="open_addr" id="open-addr" value="<?php echo $_POST['open_addr'];?>" />
<input type="hidden" name="open_address" id="open-address" value="<?php echo $_POST['open_address'];?>" />
<input type="hidden" name="open_area" id="open-area" value="<?php echo $_POST['open_area'];?>" />
<input type="hidden" name="open_jiga" id="open-jiga" value="<?php echo $_POST['open_jiga'];?>" />
<input type="hidden" name="open_cal_jiga" value="<?php echo $open_cal_jiga;?>"/>
<!-- Save for Web Slices (001 개발부담금가산정_개시시점.JPG) -->
<table id="__01" width="1281" height="1025" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="33">
			<img src="img/102_EndPt_01.jpg" width="1280" height="26" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="26" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="8">
			<img src="img/102_EndPt_02.jpg" width="35" height="219" alt=""></td>
		<td colspan="2" rowspan="3">
			<img src="img/102_EndPt_03.jpg" width="142" height="72" alt=""></td>
		<td colspan="29">
			<img src="img/102_EndPt_04.jpg" width="1103" height="19" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="19" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="7">
			<img src="img/102_EndPt_05.jpg" width="21" height="200" alt=""></td>
		<td colspan="25">
			<img src="img/102_EndPt_06.jpg" width="1048" height="48" alt=""></td>
		<td rowspan="13">
			<img src="img/102_EndPt_07.jpg" width="14" height="897" alt=""></td>
		<td rowspan="23">
			<img src="img/102_EndPt_08.jpg" width="20" height="979" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="48" alt=""></td>
	</tr>
	<tr>
		<td rowspan="22">
			<img src="img/102_EndPt_09.jpg" width="7" height="931" alt=""></td>
		<td colspan="24" rowspan="2">
			<img src="img/102_EndPt_10.jpg" width="1041" height="8" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="5" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="3">
			<img src="img/102_EndPt_11.jpg" width="142" height="50" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="3" alt=""></td>
	</tr>
	<tr>
		<td colspan="2">
			<img src="img/102_EndPt_12.jpg" width="27" height="17" alt=""></td>
		<td>
			<img src="img/102_EndPt_13.jpg" width="17" height="17" alt=""></td>
		<td colspan="21">
			<img src="img/102_EndPt_14.jpg" width="997" height="17" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="17" alt=""></td>
	</tr>
	<tr>
		<td colspan="24" rowspan="8">
<!-- 내용 들어가는 부분 시작	---------------------------------------------------------------------------------------------------------------------------->		

			<table border='0' cellpadding='0' cellspacing='0' height='12'><tr><td></td></tr></table>
			<table border='0' cellpadding='0' cellspacing='0' width='1041' height='100'>
				<tr>
					<td align='center'>
						<table border='0' cellpadding='0' cellspacing='12' width='1010' background='img/search_bg2.jpg' height='61'>
							
							<!-- 주소검색 -->
							<tr>
								<td align='left'>
									<table border='0' cellpadding='0' cellspacing='0'>
										<tr>
											<td><img src='img/start_point_49.jpg' border='0'><br></td>
											<td width='10'></td>
											<td>
<select id="UMD" name="UMD3">
<option value="">읍/면/동 검색</option>
<?php foreach($umd as $cd=>$nm):?>
<option value="<?=$cd?>" <?php if( $UMD3 == $cd ):?>selected="selected"<?php endif;?>><?=$nm?></option>
<?php endforeach;?>
</select>
											</td>
											<td width='10'></td>
											<td>
<select id="RI" name="RI3" style="width:90px">
<option value="">리 검색</option>
</select>
											</td>
											<td width='10'></td>
											<td>
<select id="G" name="G3">
<option value="1" <?php if( @$G3 == '1'):?>selected="selected"<?php endif;?>>지명/지번 검색</option>
<option value="2" <?php if( @$G3 == '2'):?>selected="selected"<?php endif;?>>산</option>
<option value="3" <?php if( @$G3 == '3'):?>selected="selected"<?php endif;?>>가지번</option>
<option value="5" <?php if( @$G3 == '4'):?>selected="selected"<?php endif;?>>블럭</option>
</select>
											</td>
											<td width='10'></td>
											<td>
<input type="text" name="S3" id="S" size="5" value="<?php echo $S3;?>"/>~<input type="text" name="E3" id="E" size="5" value="<?php echo $E3;?>"/>
											</td>
											<td width='10'></td>
											<td>
												<input type="image" src='img/start_point_60.jpg' border='0' id="btn-search" ><br>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<!-- 주소검색 -->
						</table>
						<table border='0' cellpadding='0' cellspacing='0' height='12'><tr><td></td></tr></table>
						<table border='0' cellpadding='5' cellspacing='0'>
							<tr>
								<td><a href="#" class="btn-back"><img src='img/end_point_166.jpg' border='0'></a><br></td>
								<td><a href="#" class="btn-back"><img src='img/end_point_167.jpg' border='0'></a><br></td>
								<td width='10' align='center'><img src='img/end_point_169.jpg' border='0'><br></td>
								<td><input type="image" src='img/end_point_171.jpg' border='0' class="submit"><br></td>
								<td><input type="image" src='img/end_point_172.jpg' border='0' class="submit"><br></td>
							</tr>
						</table>
						<table border='0' cellpadding='0' cellspacing='0' height='15'><tr><td></td></tr></table>
						<table border='0' cellpadding='0' cellspacing='0' width='1010' height='61'>
							<tr>
							
								<td width='10'>&nbsp;</td>
								<td valign='top' width='769'>
									<table border='0' cellpadding='0' cellspacing='0' height='275' width='1010'  background='img/search_bg3.jpg'>
										<tr>
											<!-- 지역별 공시지가 -->
											<td valign='top' width='1010' align='center'>
												<table border='0' cellpadding='0' cellspacing='0' height='15'><tr><td></td></tr></table>
												<table border='0' cellpadding='0' cellspacing='0' >
													<tr>
														<td align='center'><img src='img/end_point_53.jpg' border='0'><br></td>
													</tr>
													<tr>
														<td align='left'>
															<table border='0' cellpadding='0' cellspacing='0' height='15'><tr><td></td></tr></table>
															<table border='0' cellpadding='0' cellspacing='0' style='font-family:dotum;font-size:9pt;'>
																<tr>
																	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src='img/end_point_72.jpg' border='0'><br></td>
																	<td>
																		<table border='0' cellpadding='0' cellspacing='0'>
																			<tr>
																				<td width='10'></td>
																				<td>
<select id="UMD2" name="UMD2">
<option value="">읍/면/동 검색</option>
<?php foreach($umd as $cd=>$nm):?>
<option value="<?=$cd?>" <?php if( $cd == $UMD2 ):?>selected="selected"<?php endif;?>><?=$nm?></option>
<?php endforeach;?>
</select>
																				</td>
																				<td width='10'></td>
																				<td>
<select id="RI2" name="RI2">
<option value="">리 검색</option>
</select>
																				</td>
																				<td width='10'></td>
																				<td>
<select id="USE" name="USE">
<option value="">용도지역 선택</option>
<?php foreach($use as $cd=>$nm):?>
<option value="<?=$cd?>" <?php if( @$_POST['USE'] == $cd ):?>selected="selected"<?php endif;?>><?=$nm?></option>
<?php endforeach;?>
</select>
																				</td>
																				<td width='10'></td>
																				<td>
<select id="STATE" name="STATE">
<option value="">토지이용현황 선택</option>
<?php foreach($state as $cd=>$nm):?>
<option value="<?=$cd?>" <?php if( @$_POST['STATE'] == $cd ):?>selected="selected"<?php endif;?>><?=$nm?></option>
<?php endforeach;?>
</select>
																				</td>

																				<td width='10'></td>
																				<td>
																					<a href="info2.php" id="lnk-info" target='info'><img src='img/end_point_69.jpg' border='0'></a><br>
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td align='center'><table border='0' cellpadding='0' cellspacing='0' height='15'><tr><td></td></tr></table><img src='img/end_point_82.jpg' border='0'><br></td>
													</tr>
													<tr>
														<td align='center'>
<div style="padding-left:15px;width:983px;height:130px;overflow-y:auto">		
															<table border='0' cellpadding='0' cellspacing='0' style='font-family:dotum;font-size:9pt;' id="jiga-area" width="100%">
																<tbody>

																</tbody>																
															</table>
</div>
														</td>
													</tr>
													<tr>
												</table>
											</td>
										<!-- 지역별 공시지가 -->
										</tr>
									</table>
									<table border='0' cellpadding='0' cellspacing='0' height='12'><tr><td></td></tr></table>
									<table border='0' cellpadding='0' cellspacing='0' height='380' width='1010'>
										<tr>
											<td>
												<!-- 공시지가검색 -->
												<td valign='top' width='228' background='img/mj.jpg'>
													<table border='0' cellpadding='0' cellspacing='0' height='15'><tr><td></td></tr></table>
													<table border='0' cellpadding='0' cellspacing='0' width='228'>
														<tr>
															<td align='center'><img src='img/end_point_111.jpg' border='0'><br></td>
														</tr>
														<tr><td height='18'></td></tr>
														<tr>
															<td align='center'>
																<img src='img/end_point_124.jpg' border='0'><br>
															</td>
														</tr>
														<tr><td height='18'></td></tr>
														<tr>
															<td align='right' style='font-size:16px;font-weight:bold;font-family:dotum;'>
																<?php echo $_POST['open_address'];?>&nbsp;&nbsp;&nbsp;
															</td>
														</tr>
														<tr><td height='30' align='center'><img src='img/sep3.jpg' border='0'></td></tr>
														<tr>
															<td align='center'>
																<img src='img/end_point_134.jpg' border='0'><br>
															</td>
														</tr>
														<tr><td height='18'></td></tr>
														<tr>
															<td align='right' style='font-size:16px;font-weight:bold;font-family:dotum;'>
																<input type="text" size="10" style='border:0px;height:24px;font-size:22px;font-weight:bold;' name="close_area" id="close_area" class="number" value="<?php echo number_format($_POST['open_area']);?>"/> m<sup>2</sup>&nbsp;&nbsp;&nbsp;
															</td>
														</tr>
														<tr><td height='30' align='center'><img src='img/sep3.jpg' border='0'></td></tr>
														<tr>
															<td align='center'>
																<img src='img/end_point_148.jpg' border='0'><br>
															</td>
														</tr>
														<tr><td height='18'></td></tr>
														<tr>
															<td align='right' style='font-size:16px;font-weight:bold;font-family:dotum;'>
																<input type="text" name="close_jiga"  style='border:0px;height:24px;font-size:22px;font-weight:bold;' size="10"  class="number" id="close-jiga" value="0"/>&nbsp;&nbsp;&nbsp;
															</td>
														</tr>
														<tr><td height='30' align='center'><img src='img/sep3.jpg' border='0'></td></tr>
														<tr>
															<td align='center'>
																<a href="#naver-map" id="setmap"><img src='img/end_point_155.jpg' border='0'></a>
															</td>
														</tr>
														
													</table>
												</td>
											<!-- 공시지가검색 -->
											</td>
											<td width='12'></td>
										<!-- 지도보기 영역 -->
											<td valign='top' width='769' align='center'  background='img/map_bg.jpg'>
												<table border='0' cellpadding='0' cellspacing='0' height='15'><tr><td></td></tr></table>
												<table border='0' cellpadding='0' cellspacing='0' >
													<tr>
														<td align='center'><img src='img/start_point_124.jpg' border='0'><br></td>
													</tr>
													<tr>
														<td align='center'>
															<table border='0' cellpadding='0' cellspacing='0' height='12'><tr><td></td></tr></table>
															<table border='0' cellpadding='0' cellspacing='0'>
																<tr>
																	<td>
																		
<?php include "inc/map.php";?>																		
																		
																	</td>
																	<td width='12'></td>
																	<td width='3'><img src='img/spe01.jpg' border='0'><br></td>
																	<td width='12'></td>
																	<td>
																		<table border='0' cellpadding='0' cellspacing='0' height='100%'>
																			<tr>
																				<td align='center'><a href="#zoom" id="zoom"><img src='img/end_point_127.jpg' border='0'></a><br><br></td>
																			</tr>
																			<tr>
																				<td><img src='img/end_point_141.jpg' border='0'><br></td>
																			</tr>
																			<tr>
																				<td align='center'><br><a href="#map" id="open-map"><img src='img/end_point_144.jpg' border='0'></a><br></td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
															
														</td>
													</tr>
													<tr>
												</table>
											</td>
										<!-- 지도보기 영역 -->
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<table border='0' cellpadding='0' cellspacing='0' height='12'><tr><td></td></tr></table>
						<table border='0' cellpadding='5' cellspacing='0'>
							<tr>
								<td><a href="#" class="btn-back"><img src='img/end_point_166.jpg' border='0'></a><br></td>
								<td><a href="#" class="btn-back"><img src='img/end_point_167.jpg' border='0'></a><br></td>
								<td width='10' align='center'><img src='img/end_point_169.jpg' border='0'><br></td>
								<td><input type="image" src='img/end_point_171.jpg' border='0' class="submit"><br></td>
								<td><input type="image" src='img/end_point_172.jpg' border='0' class="submit"><br></td>
							</tr>
						</table>

					</td>
				</tr>
			</table>

<!-- 내용 들어가는 부분 끝		---------------------------------------------------------------------------------------------------------------------------->
		</td>
		<td>
			<img src="img/spacer.gif" width="1" height="30" alt=""></td>
	</tr>
	<tr>
		<td colspan="2">
			<img src="img/102_EndPt_16.jpg" width="142" height="79" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="79" alt=""></td>
	</tr>
	<tr>
		<td colspan="2">
			<img src="img/102_EndPt_17.jpg" width="142" height="18" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="18" alt=""></td>
	</tr>
	<tr>
		<td rowspan="16">
			<img src="img/102_EndPt_18.jpg" width="30" height="779" alt=""></td>
		<td colspan="4">
			<img src="img/102_EndPt_19.jpg" width="162" height="29" alt=""></td>
		<td rowspan="3">
			<img src="img/102_EndPt_20.jpg" width="6" height="485" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="29" alt=""></td>
	</tr>
	<tr>
		<td colspan="4">
<!-- 레프트 메뉴 시작 ------------------------------------------------------------------------------------------------------------------------------------>
			<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='#eef2f5' height='73'>
				<tr><td height='3'></td><td height='3'></td></tr>
				<tr>
					<td width='20' height='18'><img src='img/menu_blet.jpg' border='0'><br></td><td><a href="index.php" style='font-weight:bold;color:#b9010b;font-family:dotum;font-size:12px;'>개발부담금 가산정</a></td>
				</tR>
				<tr>
					<td height='18'></td><td><a href="term.php" style='color:#333333;font-family:dotum;font-size:12px;'>개발부담금 안내</a></td>
				</tR>
				<tr>
					<td height='18'></td><td><a href="http://www.law.go.kr/lsStmdInfoP.do?lsiSeq=118682" target="_blank" style='color:#333333;font-family:dotum;font-size:12px;'>관련법률</a></td>
				</tR>
				<tr><td height='3'></td><td height='3'></td></tr>
			</table>

<!-- 레프트 메뉴 끝   ------------------------------------------------------------------------------------------------------------------------------------>		
			</td>
		<td>
			<img src="img/spacer.gif" width="1" height="72" alt=""></td>
	</tr>
	<tr>
		<td colspan="4">
			<img src="img/102_EndPt_22.jpg" width="162" height="384" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="384" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="13">
			<img src="img/102_EndPt_23.jpg" width="11" height="294" alt=""></td>
		<td colspan="3">
			<div id='menu02' style='position:absolute;top:737px;left:41px;width:162px;'>
			<img src="img/102_EndPt_24.jpg" width="157" height="161" alt=""><br>
			<table border='0' cellpadding='0' cellspacing='0' height='5'><tr><td></td></tr></table>	
			</td>
			</div>
		<td>
			<img src="img/spacer.gif" width="1" height="161" alt=""></td>
	</tr>
	<tr>
		<td colspan="3" rowspan="12">
			<img src="img/102_EndPt_25.jpg" width="157" height="133" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="23" alt=""></td>
	</tr>
	<tr>
		<td colspan="24">
			<img src="img/102_EndPt_26.jpg" width="1041" height="28" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="28" alt=""></td>
	</tr>
	<tr>
		<td colspan="25">
			<img src="img/102_EndPt_27.jpg" width="1055" height="12" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="12" alt=""></td>
	</tr>
	<tr>
		<td rowspan="9">
			<img src="img/102_EndPt_28.jpg" width="20" height="70" alt=""></td>
		<td colspan="3" rowspan="8">
			<img src="img/102_EndPt_29.jpg" width="60" height="60" alt=""></td>
		<td rowspan="9">
			<img src="img/102_EndPt_30.jpg" width="32" height="70" alt=""></td>
		<td rowspan="2">
			<a href='http://www.dangjin.go.kr/html/kr/intro/intro_04_01.html' target='_newhome'><img src="img/102_EndPt_31.jpg" width="40" height="16" alt="" border='0'><br></a></td>
		<td rowspan="4">
			<img src="img/102_EndPt_32.jpg" width="17" height="24" alt=""></td>
		<td rowspan="2">
			<a href='http://www.dangjin.go.kr/html/kr/intro/intro_04_02.html' target='_newhome'><img src="img/102_EndPt_33.jpg" width="40" height="16" alt="" border='0'><br></a></td>
		<td rowspan="4">
			<img src="img/102_EndPt_34.jpg" width="19" height="24" alt=""></td>
		<td rowspan="2">
			<a href='http://www.dangjin.go.kr/html/kr/intro/intro_05_02.html' target='_newhome'><img src="img/102_EndPt_35.jpg" width="59" height="16" alt="" border='0'><br></a></td>
		<td rowspan="4">
			<img src="img/102_EndPt_36.jpg" width="18" height="24" alt=""></td>
		<td rowspan="2">
			<a href='http://www.dangjin.go.kr/html/kr/intro/intro_03.html' target='_newhome'><img src="img/102_EndPt_37.jpg" width="79" height="16" alt="" border='0'><br></a></td>
		<td rowspan="4">
			<img src="img/102_EndPt_38.jpg" width="17" height="24" alt=""></td>
		<td>
			<a href='http://www.dangjin.go.kr/html/kr/intro/intro_04.html' target='_newhome'><img src="img/102_EndPt_39.jpg" width="108" height="15" alt="" border='0'><br></a></td>
		<td rowspan="4">
			<img src="img/102_EndPt_40.jpg" width="16" height="24" alt=""></td>
		<td>
			<a href='http://www.dangjin.go.kr/html/kr/intro/intro_07.html' target='_newhome'><img src="img/102_EndPt_41.jpg" width="104" height="15" alt="" border='0'><br></a></td>
		<td rowspan="4">
			<img src="img/102_EndPt_42.jpg" width="16" height="24" alt=""></td>
		<td>
			<a href='http://www.dangjin.go.kr/html/kr/intro/intro_08.html' target='_newhome'><img src="img/102_EndPt_43.jpg" width="62" height="15" alt="" border='0'><br></a></td>




		<td rowspan="9">
			<img src="img/102_EndPt_44.jpg" width="91" height="70" alt=""></td>
		<td rowspan="2">
			<img src="img/102_EndPt_45.jpg" width="38" height="16" alt=""></td>
		<td rowspan="2"><span class="counter"><?php echo $counter->today();?></span></td>
		<td rowspan="9">
			<img src="img/102_EndPt_47.jpg" width="49" height="70" alt=""></td>
		<td rowspan="7">	
			<img src="img/102_EndPt_48.jpg" width="57" height="57" alt=""></td>
		<td colspan="2" rowspan="9">
			<img src="img/102_EndPt_49.jpg" width="48" height="70" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="15" alt=""></td>
	</tr>
	<tr>
		<td rowspan="3">
			<img src="img/102_EndPt_50.jpg" width="108" height="9" alt=""></td>
		<td rowspan="3">
			<img src="img/102_EndPt_51.jpg" width="104" height="9" alt=""></td>
		<td rowspan="3">
			<img src="img/102_EndPt_52.jpg" width="62" height="9" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="1" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="img/102_EndPt_53.jpg" width="40" height="8" alt=""></td>
		<td rowspan="2">
			<img src="img/102_EndPt_54.jpg" width="40" height="8" alt=""></td>
		<td rowspan="2">
			<img src="img/102_EndPt_55.jpg" width="59" height="8" alt=""></td>
		<td rowspan="2">
			<img src="img/102_EndPt_56.jpg" width="79" height="8" alt=""></td>
		<td colspan="2">
			<img src="img/102_EndPt_57.jpg" width="103" height="4" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="4" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="img/102_EndPt_58.jpg" width="38" height="16" alt=""></td>
		<td rowspan="2"><span class="counter"><?php echo $counter->total();?></span></td>
		<td>
			<img src="img/spacer.gif" width="1" height="4" alt=""></td>
	</tr>
	<tr>
		<td colspan="13" rowspan="2">
			<img src="img/102_EndPt_60.jpg" width="595" height="28" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="12" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="4">
			<img src="img/102_EndPt_61.jpg" width="103" height="34" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="16" alt=""></td>
	</tr>
	<tr>
		<td colspan="13" rowspan="3">
			<img src="img/102_EndPt_62.jpg" width="595" height="18" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="5" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="img/102_EndPt_63.jpg" width="57" height="13" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="3" alt=""></td>
	</tr>
	<tr>
		<td colspan="3">
			<img src="img/102_EndPt_64.jpg" width="60" height="10" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="img/spacer.gif" width="30" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="5" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="6" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="136" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="15" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="6" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="7" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="20" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="7" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="17" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="36" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="32" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="40" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="17" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="40" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="19" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="59" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="18" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="79" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="17" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="108" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="16" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="104" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="16" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="62" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="91" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="38" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="65" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="49" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="57" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="34" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="14" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="20" height="1" alt=""></td>
		<td></td>
	</tr>
</table>
<!-- End Save for Web Slices -->
</form>
<?php include "inc/footer.php"; ?>