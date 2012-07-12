<?php include "inc/header.php"; 

$counter = new Counter();

$info = new Info();

$use = $info->getProperty('02');//용도
$state = $info->getProperty('06');//토지이용현황


	$jiga = new GongsiJiga();
	
if( $_POST['USE'] &&  $_POST['STATE'] ){
	$USE = $_POST['USE'];
	$STATE = $_POST['STATE']; 
}else{
	$END = $jiga->getAreaJiga($_POST['UMD3'],$_POST['RI3'],$_POST['G3'],$_POST['S3'],$_POST['E3']);
	
	$USE = $END['USE_REGN1'];
	$STATE = $END['LAND_USE'];
}

$area = new Area();
$umd = $area->getUMD();


	

//	$data['jiga'] = $jiga->getAreaJiga($cd);
$OPEN = $jiga->getAreaJiga($_POST['UMD'],$_POST['RI'],$_POST['G'],$_POST['S'],$_POST['E']);

$sdate = $_POST['sdate'];
$edate = $_POST['edate'];
list($sy,$sm,$sd) = explode('-',$sdate);
list($ey,$em,$ed) = explode('-',$edate);

$lastDate[2] = date('t',mktime(0,0,0,2,1,$ey));

$close_area = (int)$_POST['close_area'];
$close_jiga = (int)$_POST['close_jiga'];

$close_total = $close_area*$close_jiga;


$setup = new Setup();

$increases = $setup->getIncrease();

$calcost = $setup->getCalCost();

$increases_total = 0;
//정상지가 상승분

trace('------------------------ start jiga ----------------------------------');

trace('sdate : '.$sdate);
trace('edate : '.$edate);
trace('close total : '.$close_total." = $close_area*$close_jiga");

$acc_total = $close_total;
if( (int)$sm == 1 && (int)$sd == 1 ){}
else{
	$increases_total = 0;
	for($m = 1; $m<=(int)$em ; $m++){
		if( $m == $em ){
			$total = (int)($acc_total*$increases[$m-1]/100*(int)$ed/$lastDate[$m]);
			trace("END :  $m MON1 ".$total." = (".$acc_total."*".$increases[$m-1]."/100*(".((int)$ed).")/".$lastDate[$m].")");			
			$acc_total += $total;			
			$increases_total += $total;

		}else{
			$total = (int)($acc_total*$increases[$m-1]/100*$lastDate[$m]/$lastDate[$m]);
			trace("END :  $m MON2 ".$total." = (".$acc_total."*".$increases[$m-1]."/100*(".($lastDate[$m]).")/".$lastDate[$m].")");						
			$acc_total += $total;
			$increases_total += $total;			

		}
		
	}
}
$close_cal_jiga =  $close_total+$increases_total;
trace("END JIGA :  ".$close_cal_jiga." =  ".$close_total."+".$increases_total);			
?>


<script type="text/javascript">
$(document).ready(function(){


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
		
		
		$('#caljiga').click(function(){
			$('.number').each(function(){
					$(this).val(unNumberFormat($(this).val()));
			});
			$.post('/json/caljiga.php',$('#form').serialize(),function(json){
				$('#dev-impact-fees').text(json.dev_impact_fees);
				$('#devimpactfees').val(json.dev_impact_fees);				
				
				$('#increase').attr('disabled',false);
				
//				$('#print-devcost').html($.formatNumber($('#devcost').val(), {format:"#,###", locale:"kr"}));
				$('#print-devcost').text(json.devcost);
				
				$('#print-increases').text(json.increases);
				
				$('.number').each(function(){
						$(this).val(numberFormat($(this).val()));
					});				
			},'json');
			return false;
		});
		
		// 감면 적용
		$('#increase').change(function(){

			var val = $(this).val();
			
			if( val == '' ) return;

			red_impact_fees = unNumberFormat($('#devimpactfees').val())*val/100;

			$('#reduction-impact-fees').text(numberFormat(String(red_impact_fees)));

		});
		
		if( parseInt($('#close-area').val(),10) >= 2700 ){
			alert("면적이 2700 ㎡ 초과되었습니다.\n면적이 2700 ㎡ 이하에서만 표준개발비용을 사용하셔야 합니다.\n개발비용을 직접 입력해 주세요.");
			$('#devcost').focus();
		}
		
		$('#devcost').focus();
		
});
</script>
<form method="post" id="form" action="end.php">
<input type="hidden" name="open_data" value="<?php echo $_POST['open_data'];?>" />


<input type="hidden" name="UMD" value="<?php echo $_POST['UMD'];?>" />
<input type="hidden" name="RI" value="<?php echo $_POST['RI'];?>" />

<input type="hidden" name="UMD2" value="<?php echo $_POST['UMD2'];?>" />
<input type="hidden" name="RI2" value="<?php echo $_POST['RI2'];?>" />

<input type="hidden" name="UMD3" value="<?php echo $_POST['UMD3'];?>" />
<input type="hidden" name="RI3" value="<?php echo $_POST['RI3'];?>" />


<input type="hidden" name="G" value="<?php echo $_POST['G'];?>" />
<input type="hidden" name="S" value="<?php echo $_POST['S'];?>" />
<input type="hidden" name="E" value="<?php echo $_POST['E'];?>" />

<input type="hidden" name="G3" value="<?php echo $_POST['G3'];?>" />
<input type="hidden" name="S3" value="<?php echo $_POST['S3'];?>" />
<input type="hidden" name="E3" value="<?php echo $_POST['E3'];?>" />

<input type="hidden" name="USE" value="<?php echo $_POST['USE'];?>" />
<input type="hidden" name="STATE" value="<?php echo $_POST['STATE'];?>" />

<input type="hidden" name="sdate" value="<?php echo $_POST['sdate'];?>" />
<input type="hidden" name="edate" value="<?php echo $_POST['edate'];?>" />

<input type="hidden" name="dev_impact_fees" id="devimpactfees" value="0"/>
<input type="hidden" name="open_addr" id="open-addr" value="<?php echo $_POST['open_addr'];?>" />
<input type="hidden" name="open_address" id="open-address" value="<?php echo $_POST['open_address'];?>" />
<input type="hidden" name="open_area" id="open-area" value="<?php echo $_POST['open_area'];?>" />
<input type="hidden" name="open_jiga" id="open-jiga" value="<?php echo $_POST['open_jiga'];?>" />

<input type="hidden" name="open_gongsijiga" id="open-gongsijiga" value="<?php echo $_POST['open_gongsijiga'];?>" />

<input type="hidden" name="open_cal_jiga" id="open-caljiga" value="<?php echo $_POST['open_cal_jiga'];?>"/>
<input type="hidden" name="close_area" id="close-area" value="<?php echo $_POST['close_area'];?>" />
<input type="hidden" name="close_jiga" id="close-jiga" value="<?php echo $_POST['close_jiga'];?>" />
<input type="hidden" name="close_cal_jiga" id="close-caljiga" value="<?php echo $close_cal_jiga;?>"/>
<!-- Save for Web Slices (001 개발부담금가산정_개시시점.JPG) -->
<table id="__01" width="1281" height="1025" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="33">
			<img src="img/103_DevPt_01.jpg" width="1280" height="26" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="26" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="8">
			<img src="img/103_DevPt_02.jpg" width="35" height="219" alt=""></td>
		<td colspan="2" rowspan="3">
			<img src="img/103_DevPt_03.jpg" width="142" height="72" alt=""></td>
		<td colspan="29">
			<img src="img/103_DevPt_04.jpg" width="1103" height="19" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="19" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="7">
			<img src="img/103_DevPt_05.jpg" width="21" height="200" alt=""></td>
		<td colspan="25">
			<img src="img/103_DevPt_06.jpg" width="1048" height="48" alt=""></td>
		<td rowspan="13">
			<img src="img/103_DevPt_07.jpg" width="14" height="897" alt=""></td>
		<td rowspan="23">
			<img src="img/103_DevPt_08.jpg" width="20" height="979" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="48" alt=""></td>
	</tr>
	<tr>
		<td rowspan="22">
			<img src="img/103_DevPt_09.jpg" width="7" height="931" alt=""></td>
		<td colspan="24" rowspan="2">
			<img src="img/103_DevPt_10.jpg" width="1041" height="8" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="5" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="3">
			<img src="img/103_DevPt_11.jpg" width="142" height="50" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="3" alt=""></td>
	</tr>
	<tr>
		<td colspan="8">
			</td>
		<td>
			<img src="img/103_DevPt_13.jpg" width="17" height="17" alt=""></td>
		<td colspan="15">
			<img src="img/103_DevPt_14.jpg" width="763" height="17" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="17" alt=""></td>
	</tr>
	<tr>
		<td colspan="24" rowspan="8">
<!-- 내용 들어가는 부분 시작	---------------------------------------------------------------------------------------------------------------------------->		

			<table border='0' cellpadding='0' cellspacing='0' height='12'><tr><td></td></tr></table>
			<table border='0' cellpadding='0' cellspacing='0' width='1041' height='144'>
				<tr>
					<td align='center'>
						<table border='0' cellpadding='0' cellspacing='12' width='1010' background='img/devtopbg.jpg' height='144'>
							
							<!-- 주소검색 -->
							<tr>
								<td align='center'>
									<table border='0' cellpadding='0' cellspacing='0' >
										<tr>
											<td align='center'><img src='img/dev_point_24.jpg' border='0'><br></td>
										</tr>
									</table>
									<table border='0' cellpadding='0' cellspacing='0' height='10'><tr><td></td></tr></table>
									<table border='0' cellpadding='0' cellspacing='0' >
										<tr>
											<td>
												<table border='0' cellpadding='0' cellspacing='0' >
													<tr>
														<td align='left'><img src='img/dev_point_42.jpg' border='0'><br></td>
														<td width='10'></td>
														<td>
															<table border='0' cellpadding='0' cellspacing='0' >
																<tr>
																	<td><input type='text' name='devcost' id="devcost" class="number" style='text-align:right;width:288px;' value="<?php echo number_format($calcost[1]);?>"></td>
																	<td width='10'></td>
																	<td><a href="#caljiga" id="caljiga"><img src='img/dev_point_34.jpg' border='0'></a><br></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr><td height='12'></td><td width='12'></td><td height='12'></td></tr>
													<tr>
														<td align='left'><img src='img/dev_point_56.jpg' border='0'><br></td>
														<td width='10'></td>
														<td>
														<select type='text' name='increase' id="increase" style='width:400px;' disabled="disabled">
<option value="">감면사항 선택</option>														
<?php
$res = mysql_query("select `rid`,`item`,content,rate from reduction order by item desc",$conn) or die(mysql_error($conn));
while($data = mysql_fetch_assoc($res)):
?>
<option value="<?php echo $data['rate'];?>"><?php echo $data['content'];?></option>
<?php endwhile;?>														
														</select>
														</td>
													</tr>
												</table>
											</td>
											<td width='15' align='center'>
												<img src='img/sep00.jpg' border='0' height='74'><br>
											</td>
											<td background='img/bg000.jpg' width='462'>
												<table border='0' cellpadding='0' cellspacing='0' width='462' >
													<tr>
														<td width='10'></td>
														<td align='left' width="200"><img src='img/dev_point_45.jpg' border='0'><br></td>
														<td width='10'></td>
														<td align='right'>
															<span id="dev-impact-fees" style='font-size:22px;font-weight:bold;color:#0078ff;' >0</span>
														</td>
														<td width='10'></td>
													</tr>
													<tr><td height='12'></td><td height='12'></td><td height='12'></td><td width='12'></td><td height='12'></td></tr>
													<tr>
														<td width='10'></td>
														<td align='left'><img src='img/dev_point_58.jpg' border='0'><br></td>
														<td width='10'></td>
														<td style='font-size:22px;font-weight:bold;color:#0078ff;' align='right'>
															<span id="reduction-impact-fees" style='font-size:22px;font-weight:bold;color:#0078ff;' >0</span>
														</td>
														<td width='10'></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<!-- 주소검색 -->
						</table>
						<table border='0' cellpadding='0' cellspacing='4' width='1010' height='45' style='font-family:dotum;font-size:10px;' bgcolor='#d6e6f6'>
							<tr>
								<td style='font-family:dotum;font-size:11px;font-color:#777777;' bgcolor='#f1f7fd' height='50'>개발기간</td>
								<td style='font-family:dotum;font-size:14px;font-weight:bold;' bgcolor='#ffffff'>
								<?php echo $_POST['sdate'];?> ~ <?php echo $_POST['edate'];?> 
								</td>
							</tr>
							<tr>
								<td style='font-family:dotum;font-size:11px;font-color:#777777;' bgcolor='#f1f7fd'>개시시점</td>
								<td style='padding:0 0 0 0;'>
									<table border='0' cellpadding='0' cellspacing='0' width='100%'>
										<tr bgcolor='#e3effb'>
											<td style='font-family:dotum;font-size:11px;font-color:#777777;' bgcolor='#f1f7fd' height='25'>대표지번</td>
											<td style='font-family:dotum;font-size:11px;font-color:#777777;' bgcolor='#f1f7fd'>총면적</td>
											<td style='font-family:dotum;font-size:11px;font-color:#777777;' bgcolor='#f1f7fd'>공시지가</td>
											<td style='font-family:dotum;font-size:11px;font-color:#777777;' bgcolor='#f1f7fd'>용도지역</td>
											<td style='font-family:dotum;font-size:11px;font-color:#777777;' bgcolor='#f1f7fd'>토지이용상황</td>
										</tr>
										<tr>
											<td style='font-family:dotum;font-size:14px;font-weight:bold;' bgcolor='#ffffff' height='25'><?php echo $_POST['open_address'];?></td>
											<td style='font-family:dotum;font-size:14px;font-weight:bold;' bgcolor='#ffffff'><?php echo number_format($_POST['open_area']);?></td>
											<td style='font-family:dotum;font-size:14px;font-weight:bold;' bgcolor='#ffffff'><?php echo number_format($_POST['open_gongsijiga']);?></td>
											<td style='font-family:dotum;font-size:14px;font-weight:bold;' bgcolor='#ffffff'><?php echo $use[$OPEN['USE_REGN1']];?></td>
											<td style='font-family:dotum;font-size:14px;font-weight:bold;' bgcolor='#ffffff'><?php echo $state[$OPEN['LAND_USE']];?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td style='font-family:dotum;font-size:11px;font-color:#777777;' bgcolor='#f1f7fd' height='30'>종료시점</td>
								<td style='padding:0 0 0 0;'>
									<table border='0' cellpadding='0' cellspacing='0' width='100%'>
										<tr bgcolor='#e3effb'>
											<td style='font-family:dotum;font-size:11px;font-color:#777777;' bgcolor='#f1f7fd' height='25'>총면적</td>
											<td style='font-family:dotum;font-size:11px;font-color:#777777;' bgcolor='#f1f7fd'>공시지가</td>
											<td style='font-family:dotum;font-size:11px;font-color:#777777;' bgcolor='#f1f7fd'>용도지역</td>
											<td style='font-family:dotum;font-size:11px;font-color:#777777;' bgcolor='#f1f7fd'>토지이용상황</td>
										</tr>
										<tr>
											<td style='font-family:dotum;font-size:14px;font-weight:bold;' bgcolor='#ffffff' height='25'><?php echo number_format($_POST['close_area']);?></td>
											<td style='font-family:dotum;font-size:14px;font-weight:bold;' bgcolor='#ffffff'><?php echo number_format($_POST['close_jiga']);?></td>
											<td style='font-family:dotum;font-size:14px;font-weight:bold;' bgcolor='#ffffff'><?php echo $use[$USE];?></td>
											<td style='font-family:dotum;font-size:14px;font-weight:bold;' bgcolor='#ffffff'	><?php echo $state[$STATE];?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<table border='0' cellpadding='0' cellspacing='0' width='1010' bgcolor='#d6e6f6' height='45'>
							<tr>
								<td width='20'></td>
								<td><img src='img/dev_point_79.jpg' border='0'><br></td>
								<td width='10'></td>
								<td align='right' style='font-size:16px;font-weight:bold;' ><?php echo number_format($_POST['open_cal_jiga']);?></td>
								<td width='10'></td>
								<td><img src='img/dev_point_82.jpg' border='0'><br></td>
								<td width='10'></td>	
								<td align='right' style='font-size:16px;font-weight:bold;' ><?php echo number_format($close_cal_jiga);?></td>
								<td width='10'></td>
								<td><img src='img/dev_point_84.jpg' border='0'><br></td>
								<td width='10'></td>
								<td align='right' style='font-size:16px;font-weight:bold;' ><span id="print-devcost" style='font-size:16px;font-weight:bold;color:#0078ff;' ><?php echo number_format($calcost[1]);?></span></td>
								<td width='10'></td>
								<td><img src='img/dev_point_84_1.jpg' border='0'><br></td>
								<td width='10'></td>
								<td align='right' style='font-size:16px;font-weight:bold;' ><span id="print-increases" style='font-size:16px;font-weight:bold;color:#0078ff;' >0</span></td>
								<td width='20'></td>
							</tr>
						</table>
						<table border='0' cellpadding='0' cellspacing='0' height='12'><tr><td></td></tr></table>
						<table border='0' cellpadding='5' cellspacing='0'>
							<tr>
								<td><input type="image" src='img/dev_point_126.jpg' border='0'><br></td>
								<td><img src='img/dev_point_127.jpg' border='0'><br></td>
								<td align='center' width='10'><img src='img/end_point_169.jpg' border='0'><br></td>
								<td><img src='img/dev_point_131.jpg' border='0'><br></td>
								<td><img src='img/dev_point_132.jpg' border='0'><br></td>
							</tr>
						</table>	
						<table border='0' cellpadding='0' cellspacing='0' height='15'><tr><td></td></tr></table>
						<table border='0' cellpadding='0' cellspacing='0' width='1011' height='534'>
							<tr>
							
								<td width='10'>&nbsp;</td>
								<td valign='top' width='769'>
									<table border='0' cellpadding='0' cellspacing='0' height='534' width='1011'  background='img/bg1212.jpg'>
										<tr>
											<!-- 지역별 공시지가 -->
											<td valign='top' align='center'>
												<table border='0' cellpadding='0' cellspacing='0' height='15'><tr><td></td></tr></table>
												<table border='0' cellpadding='0' cellspacing='0' >
													<tr>
														<td align='center'><img src='img/dev_point_99.jpg' border='0'><br></td>
													</tr>
													<tr>
														<td>
															<table border='0' cellpadding='0' cellspacing='0' height='15'><tr><td></td></tr></table>
															<table border='0' cellpadding='0' cellspacing='0'>
																<tr>
																	<td>
<?php include "inc/map_finish.php";?>																		
																		
																	</td>
																	<td width='12'></td>
																	<td align='center' width='10'><img src='img/sep0000.jpg' border='0'></td>

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
																			<tr><td height='18'></td></tr>
																			<tr>
																				<td><img src='img/end_point_141.jpg' border='0'><br></td>
																			</tr>
																			<tr>
																				<td align='center'><br><img src='img/dev_point_117.jpg' border='0'><br></td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
														
													</tr>
												</table>
											</td>
											
										<!-- 지역별 공시지가 -->
										</tr>
									</table>
									
								</td>
							</tr>
						</table>
						<table border='0' cellpadding='0' cellspacing='0' height='12'><tr><td></td></tr></table>
						<table border='0' cellpadding='5' cellspacing='0'>
							<tr>
								<td><input type="image" src='img/dev_point_126.jpg' border='0'><br></td>
								<td><img src='img/dev_point_127.jpg' border='0'><br></td>
								<td align='center' width='10'><img src='img/end_point_169.jpg' border='0'><br></td>
								<td><img src='img/dev_point_131.jpg' border='0'><br></td>
								<td><img src='img/dev_point_132.jpg' border='0'><br></td>
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
			<img src="img/103_DevPt_16.jpg" width="142" height="79" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="79" alt=""></td>
	</tr>
	<tr>
		<td colspan="2">
			<img src="img/103_DevPt_17.jpg" width="142" height="18" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="18" alt=""></td>
	</tr>
	<tr>
		<td rowspan="16">
			<img src="img/103_DevPt_18.jpg" width="30" height="779" alt=""></td>
		<td colspan="4">
			<img src="img/103_DevPt_19.jpg" width="162" height="29" alt=""></td>
		<td rowspan="3">
			<img src="img/103_DevPt_20.jpg" width="6" height="485" alt=""></td>
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
			<img src="img/103_DevPt_22.jpg" width="162" height="384" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="384" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="13">
			<img src="img/103_DevPt_23.jpg" width="11" height="294" alt=""></td>
		<td colspan="3">
			<div id='menu02' style='position:absolute;top:818px;left:41px;width:162px;'>
			<img src="img/103_DevPt_24.jpg" width="157" height="161" alt="">
			</div>
			</td>
		<td>
			<img src="img/spacer.gif" width="1" height="161" alt=""></td>
	</tr>
	<tr>
		<td colspan="3" rowspan="12">
			<img src="img/103_DevPt_25.jpg" width="157" height="133" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="23" alt=""></td>
	</tr>
	<tr>
		<td colspan="24">
			<img src="img/103_DevPt_26.jpg" width="1041" height="28" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="28" alt=""></td>
	</tr>
	<tr>
		<td colspan="25">
			<img src="img/103_DevPt_27.jpg" width="1055" height="12" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="12" alt=""></td>
	</tr>
	<tr>
		<td rowspan="9">
			<img src="img/103_DevPt_28.jpg" width="20" height="70" alt=""></td>
		<td rowspan="8">
			<img src="img/103_DevPt_29.jpg" width="60" height="60" alt=""></td>
		<td rowspan="9">
			<img src="img/103_DevPt_30.jpg" width="32" height="70" alt=""></td>
		<td rowspan="2">
			<a href='http://www.dangjin.go.kr/html/kr/intro/intro_04_01.html' target='_newhome'><img src="img/101_StartPt_31.jpg" width="40" height="16" alt="" border='0'><br></a></td>
		<td rowspan="4">
			<img src="img/101_StartPt_32.jpg" width="17" height="24" alt=""></td>
		<td rowspan="2">
			<a href='http://www.dangjin.go.kr/html/kr/intro/intro_04_02.html' target='_newhome'><img src="img/101_StartPt_33.jpg" width="40" height="16" alt="" border='0'><br></a></td>
		<td rowspan="4">
			<img src="img/101_StartPt_34.jpg" width="19" height="24" alt=""></td>
		<td colspan="3" rowspan="2">
			<a href='http://www.dangjin.go.kr/html/kr/intro/intro_05_02.html' target='_newhome'><img src="img/101_StartPt_35.jpg" width="59" height="16" alt="" border='0'><br></a></td>
		<td rowspan="4">
			<img src="img/101_StartPt_36.jpg" width="18" height="24" alt=""></td>
		<td rowspan="2">
			<a href='http://www.dangjin.go.kr/html/kr/intro/intro_03.html' target='_newhome'><img src="img/101_StartPt_37.jpg" width="79" height="16" alt="" border='0'><br></a></td>
		<td rowspan="4">
			<img src="img/101_StartPt_38.jpg" width="17" height="24" alt=""></td>
		<td>
			<a href='http://www.dangjin.go.kr/html/kr/intro/intro_04.html' target='_newhome'><img src="img/101_StartPt_39.jpg" width="108" height="15" alt="" border='0'><br></a></td>
		<td rowspan="4">
			<img src="img/101_StartPt_40.jpg" width="16" height="24" alt=""></td>
		<td>
			<a href='http://www.dangjin.go.kr/html/kr/intro/intro_07.html' target='_newhome'><img src="img/101_StartPt_41.jpg" width="104" height="15" alt="" border='0'><br></a></td>
		<td rowspan="4">
			<img src="img/101_StartPt_42.jpg" width="16" height="24" alt=""></td>
		<td>
			<a href='http://www.dangjin.go.kr/html/kr/intro/intro_08.html' target='_newhome'><img src="img/101_StartPt_43.jpg" width="62" height="15" alt="" border='0'><br></a></td>
		<td rowspan="9">
			<img src="img/103_DevPt_44.jpg" width="91" height="70" alt=""></td>
		<td rowspan="2">
			<img src="img/103_DevPt_45.jpg" width="38" height="16" alt=""></td>
		<td rowspan="2"><span class="counter"><?php echo $counter->today();?></span></td>
		<td rowspan="9">
			<img src="img/103_DevPt_47.jpg" width="49" height="70" alt=""></td>
		<td rowspan="7">
			<img src="img/103_DevPt_48.jpg" width="57" height="57" alt=""></td>
		<td colspan="2" rowspan="9">
			<img src="img/103_DevPt_49.jpg" width="48" height="70" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="15" alt=""></td>
	</tr>
	<tr>
		<td rowspan="3">
			<img src="img/103_DevPt_50.jpg" width="108" height="9" alt=""></td>
		<td rowspan="3">
			<img src="img/103_DevPt_51.jpg" width="104" height="9" alt=""></td>
		<td rowspan="3">
			<img src="img/103_DevPt_52.jpg" width="62" height="9" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="1" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="img/103_DevPt_53.jpg" width="40" height="8" alt=""></td>
		<td rowspan="2">
			<img src="img/103_DevPt_54.jpg" width="40" height="8" alt=""></td>
		<td colspan="3" rowspan="2">
			<img src="img/103_DevPt_55.jpg" width="59" height="8" alt=""></td>
		<td rowspan="2">
			<img src="img/103_DevPt_56.jpg" width="79" height="8" alt=""></td>
		<td colspan="2">
			<img src="img/103_DevPt_57.jpg" width="103" height="4" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="4" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="img/103_DevPt_58.jpg" width="38" height="16" alt=""></td>
		<td rowspan="2"><span class="counter"><?php echo $counter->total();?></span></td>
		<td>
			<img src="img/spacer.gif" width="1" height="4" alt=""></td>
	</tr>
	<tr>
		<td colspan="15" rowspan="2">
			<img src="img/103_DevPt_60.jpg" width="595" height="28" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="12" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="4">
			<img src="img/103_DevPt_61.jpg" width="103" height="34" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="16" alt=""></td>
	</tr>
	<tr>
		<td colspan="15" rowspan="3">
			<img src="img/103_DevPt_62.jpg" width="595" height="18" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="5" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="img/103_DevPt_63.jpg" width="57" height="13" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="3" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="img/103_DevPt_64.jpg" width="60" height="10" alt=""></td>
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
			<img src="img/spacer.gif" width="60" height="1" alt=""></td>
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
			<img src="img/spacer.gif" width="33" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="17" height="1" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="9" height="1" alt=""></td>
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