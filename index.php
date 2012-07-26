<?php 

include "inc/header.php"; 

$counter = new Counter();
$counter->access();

$area = new Area();
$umd = $area->getUMD();

$open_area = 0;
if( @$_POST['open_area'] ) $open_area = @$_POST['open_area'];
$open_jiga = 0;
if( @$_POST['open_jiga'] ) $open_area = @$_POST['open_jiga'];

?>
<style type="text/css">
.area {
	width:60px;
}
.jiga {
	width:115px;
}
ul>li {
	white-space:nowrap;
}
</style>
<link type="text/css" rel="stylesheet" href="/css/jquery.editable-select.css" />
<script type="text/javascript" src="/js/jquery.editable-select.pack.js"></script>
<script type="text/javascript">
$(document).ready(function(){


		$(".number").numeric();
		
		//-- begin init
		var val = $('#UMD').val();
		if( val ){
		$.post('/json/ri.php',{q:val},function(json){
			i = 1;
			$('#RI > option').remove();
			$('#RI').append($('<option value="">리 선택</option>'));


			if( json == false || json.length == 0 ) return;
			for(j  = 0 ; j < json.length ; j++){
			
				if( '<?php echo @$_POST['RI'];?>' == json[j].RI_CD ){
					document.getElementById('RI').options[i++] = new Option(json[j].RI_NM,json[j].RI_CD,true);						
				}else{
					document.getElementById('RI').options[i++] = new Option(json[j].RI_NM,json[j].RI_CD);
				}
							
			}
			

			if($.browser.msie){
						document.getElementById('RI').value = '<?php echo $_POST['RI'];?>';
			}
			// -- init
			printData();
					
		},'json');
		}
		
		//-- end init 

		$("#sdate").datepicker({
//			defaultDate: "+1w",
			changeMonth: true,
			onSelect: function( selectedDate ) {
				$("#edate").datepicker( "option", "minDate", selectedDate );
			}
		});
		$("#edate").datepicker({
//			defaultDate: "+1w",
			changeMonth: true,
			onSelect: function( selectedDate ) {
				$("#sdate").datepicker( "option", "maxDate", selectedDate );
			}
		});
		
	
	$('.yjiga').live('click',function(){
		var jiga = $(this).val();
		
		//대표지선 선택시 선택한 지가의 지가정보를 수정
		if( (jidx = $('.seladdr').index($('.seladdr:checked'))) != -1 ){
			$('.jiga:eq('+jidx+')').val(jiga);		
		}else{		
			var eq = ($('#jiga-area > tbody > tr').length-1);
			$('.jiga:eq('+eq+')').val(jiga);
		}


	});

	$('.del').live('click',function(){
		var idx = $('.del').index($(this));
//		$('.del:eq('+idx+')').remove();
		$('#jiga-area > tbody > tr:eq('+idx+')').remove();
		
		
		//대표지번 선택이 제거 될경우 마지막 지번을 자동으로 선택
		if( $('.seladdr').index($('.seladdr:checked')) == -1 ){
			var eq = ($('#jiga-area > tbody > tr').length-1);
			$('.seladdr:eq('+eq+')').prop('checked',true);
		}
		
		return false;
	});
	

	$('.seladdr').live('click',function(){
		var idx = $('.seladdr').index($(this));
//		$('html').animate({scrollTop:300},1000);
		var addr = '당진시'+$('.address:eq('+idx+')').text().replace(/\s/gi,'');
		//alert($('.jiga:eq('+idx+')').val())
//		alert(unNumberFormat($('.jiga:eq('+((idx*2)+1)+')').val()))
		$('#open-gongsijiga').val(unNumberFormat($('.jiga:eq('+((idx*2)+1)+')').val()));
		$('#open-addr').val($('.addr:eq('+idx+')').text());
		$('#open-address').val($('.address:eq('+idx+')').text());
		$('#use').val($('.use:eq('+idx+')').attr('data'));
		$('#state').val($('.state:eq('+idx+')').attr('data'));

		
		addrString = $('.address:eq('+idx+')').text();
		$.post('/api/naver.map.php',{query:addr},function(data){
			if( typeof data.point == 'undefined' ){
				alert('지도 좌표정보가 없습니다.');
				return;
			}		
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
	
	$('.submit').click(function(){
		//년도 체크
		if( $('#sdate').val() == '' ){ alert('개시날짜를 선택하십시오'); $('#sdate').focus(); return;}
		if( $('#edate').val() == '' ){ alert('종료날짜를 선택하십시오'); $('#edate').focus(); return;}
				
		var sdate = $('#sdate').val().split('-');
		var edate = $('#edate').val().split('-');		
		
		if( sdate[0] != edate[0] ){
			alert('기간은 같은 년도만 선택가능합니다.');
			$('#edate').focus();
			return;
		}
		
		var area = 0;
		var jiga = 0;		
		$('.area').each(function(index){

			area += parseInt(unNumberFormat($(this).val()),10);			

			if( typeof $('.jiga:eq('+((index*2)+1)+')').val() == 'undefined' ){
				jiga += parseInt(unNumberFormat($(this).val()),10) * parseInt(unNumberFormat($('.jiga:eq('+(index)+')').val()),10);			
			}else{
				jiga += parseInt(unNumberFormat($(this).val()),10) * parseInt(unNumberFormat($('.jiga:eq('+((index*2)+1)+')').val()),10);
			}

		});

		$('#open-area').val(area);
		$('#open-jiga').val(jiga);		

		

		if( (jidx = $('.seladdr').index($('.seladdr:checked'))) == -1 ){
			alert('대표 지번을 선택하십시오');
			$('.seladdr:eq(0)').focus();
			return;
		}

		if( parseInt($('#open-jiga').val(),10)==0 ){
			alert("공시지가 정보가 없습니다.");
			return;
		}

		setData();
		document.getElementById('form').submit();
	});
	
	
	

	$('#btn-search').bind('click',function(){
	
	
			if( $('#UMD').val() == '' ){ alert('읍/면/동 선택하십시오'); $('#UMD').focus(); return; }
			//if( $('#G').val() == '' ){ alert('필지구분을 선택하십시오'); $('#G').focus(); return; }			
			if( $('#S').val() == '' ){ alert('지번을 입력하십시오'); $('#S').focus(); return; }									

			loadData();
			return false;
	
		
		
	});	
	

});


function setData(){
	var data = '';
	$('#jiga-area > tbody > tr').each(function(idx){
		data += $('.addr:eq('+idx+')').text()+':'+$('.address:eq('+idx+')').text()+':';
		data += $('.use:eq('+idx+')').attr('data')+':'+$('.use:eq('+idx+')').text()+':';
		data += $('.state:eq('+idx+')').attr('data')+':'+$('.state:eq('+idx+')').text()+':'+$('.jimok:eq('+idx+')').text()+':';		
		
		if( typeof $('.jiga:eq('+((idx*2)+1)+')').val() == 'undefined' ){
			data += unNumberFormat($('.area:eq('+idx+')').val())+':'+unNumberFormat($('.jiga:eq('+(idx)+')').val())+'-';		
		}else{
			data += unNumberFormat($('.area:eq('+idx+')').val())+':'+unNumberFormat($('.jiga:eq('+((idx*2)+1)+')').val())+'-';
		}
		
		$('select.jiga:eq('+(idx)+') > option').each(function(oidx){
			if( oidx == 0 ) return true;
			data += $(this).attr('value')+'-';
		});
		data += ':';		
		data += $('.seladdr:eq('+idx+')').val()+'|';		
	});
//alert(data);
	$('#open-data').val(data);	
	
		idx = $('.seladdr').index($('.seladdr:checked'));
//		alert(idx);
		$('#open-gongsijiga').val(unNumberFormat($('.jiga:eq('+((idx*2)+1)+')').val()));
		$('#open-addr').val($('.addr:eq('+idx+')').text());
		$('#open-address').val($('.address:eq('+idx+')').text());
		$('#use').val($('.use:eq('+idx+')').attr('data'));
		$('#state').val($('.state:eq('+idx+')').attr('data'));	
}

function printData(){

	var data = $('#open-data').val();
	
	if( data == '' ) return;
	
	var	rows = data.split('|');
	for(i = 0 ; i < rows.length; i ++ ){
		if( rows[i] == '' ) continue;
		row = rows[i].split(':');
	var src = "<tr>"
		src += "<td width='40' height='35' align='left'>"+($('#jiga-area > tbody > tr').length+1)+"</td>";
		src += "<td width='180' align='left'>&nbsp;<span class='addr' style='display:none'>"+row[0] + "</span>";
		src += "<span class='address'>"+row[1]+"</span></td>";
	
		src += "<td width='120' align='center'><span class='use' data='"+row[2]+"'>"+row[3]+"</span></td>";
		src += "<td width='150' align='center'>&nbsp;<span class='state' data='"+row[4]+"'>"+row[5]+"</span></td>";
		src += "<td width='100' align='center'>&nbsp;<span class='jimok'>"+row[6]+"</span></td>";
			
		src += "<td width='97' align='right'><input type='text' class='area number' maxLength='7' value='"+numberFormat(row[7])+"'/></td>";
		src += "<td width='104' align='right'><select class='jiga'>";
		
		var options = row[8].split('-');
		for(o = 0 ; o < options.length-1; o++){
			if( o == 0 ){
				src += "<option value='"+numberFormat(options[o])+"'>"+numberFormat(options[o])+"</option>";
			}else{
				src += "<option value='"+options[o]+"'>"+options[o]+"</option>";			
			}
		}
		src += "</select></td>";
		var checked='';
		if( row[1] == $('#open-address').val() ) checked='checked="true"';
		src += "<td width='89' align='center'><input type='radio' name='addr' class='seladdr' value='"+row[9]+"' "+checked+"></td>";
		src += "<td width='50' align='left'>&nbsp;&nbsp;<a href='#del' class='del'><img src='img/del.jpg' border='0'><br></td>";
		src += "</tr>";
	
		$('#jiga-area > tbody').append($(src));	
		
		 $('.jiga:eq('+(($('#jiga-area > tbody > tr').length-1)*2)+')').editableSelect({
		      bg_iframe: true,
		      onSelect: function(list_item) {
//		      alert(	$('.editable-select-options').index($(this).parent().parent()));


						var idx = $('.editable-select-options').index(list_item.parent().parent());
						$('#editable-select' + idx ).val(this.text.val().split('(')[0]);


		      /*
		        alert('List item text: '+ list_item.text());
		        // 'this' is a reference to the instance of EditableSelect
		        // object, so you have full access to everything there
		        alert('Input value: '+ this.text.val());
		        */
		      },
		      case_sensitive: false, // If set to true, the user has to type in an exact
		                             // match for the item to get highlighted
		      items_then_scroll: 10 // If there are more than 10 items, display a scrollbar
		  });		
		
	}//end of for;
	


	$.post('/json/jiga_year.php',{umd:$('#UMD').val(),ri:$('#RI').val(),g:$('#G').val(),s:$('#S').val(),e:$('#E').val()},function(json)		{
				$('#jiga-year > tbody > tr').remove();		
				for(i = 0 ;  i < json.length; i++ ){
					$('#jiga-year > tbody').append($('<tr><td height="30" width="40" align="center">'+json[i].YEAR+'</td>'+
					"<td width='100' align='center'>"+json[i].JIGA+'</td>'+
					"<td width='40' align='center'><input type='radio' name='yjiga' value='" + json[i].JIGA + "' class='yjiga'></td>"+
					'</tr>'+
					"<tr><td height='1' background='img/start_point_106.jpg'></td><td background='img/start_point_106.jpg'></td><td background='img/start_point_106.jpg'></td></tr>"
					));				
				}
			},'json');	
						
		$('.number').off();
		$(".number").numeric();
					
		if( $('#open-address').val() == '' ) return;
		addr = $('#open-address').val();
		addrString = addr;
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
		

}

function loadData(){

$.post('/json/jiga_year.php',{umd:$('#UMD').val(),ri:$('#RI').val(),g:$('#G').val(),s:$('#S').val(),e:$('#E').val()},function(json)		{
				$('#jiga-year > tbody > tr').remove();		
				for(i = 0 ;  i < json.length; i++ ){
					$('#jiga-year > tbody').append($('<tr><td height="30" width="40" align="center">'+json[i].YEAR+'</td>'+
					"<td width='100' align='center'>"+json[i].JIGA+'</td>'+
					"<td width='40' align='center'><input type='radio' name='yjiga' value='" + json[i].JIGA + "' class='yjiga'></td>"+
					'</tr>'+
					"<tr><td height='1' background='img/start_point_106.jpg'></td><td background='img/start_point_106.jpg'></td><td background='img/start_point_106.jpg'></td></tr>"
					));				
				}
			},'json');
			
			$.post('/json/jiga_area.php',{umd:$('#UMD').val(),ri:$('#RI').val(),g:$('#G').val(),s:$('#S').val(),e:$('#E').val()},function(json)		{
			
					if( json.jiga == false ){
						alert('데이터가 존재하지 않습니다.');
						return;
					}
					
					//중복 체크
					chk = false;
					$('.seladdr').each(function(){
						var cd = $(this).val();
						if( cd == json.jiga.LAND_CD ){
							chk = true;
							return false;
						}
					});
					if( chk == true ){
						alert('이미 추가된 주소입니다.');
						return;
					}
					
					gbn = '';
					if( $('#G').val() == '2') gbn=' 산';
					if( $('#G').val() == '3') gbn=' 가지번';
					if( $('#G').val() == '4') gbn=' 블럭';										
					bungi='';
					if( $.trim($('#S').val()) ) bungi = ' '+$.trim($('#S').val());
					if( $.trim($('#E').val()) ){
						if( bungi ){
							bungi = bungi+'-'+$.trim($('#E').val());						
						}else{
							bungi = ' '+$.trim($('#E').val());
						}				
					}

					$('#use').val(json.jiga.USE_REGN1);
					$('#state').val(json.jiga.LAND_USE);
					$('#jimok').val(json.jiga.JIMOK);										
					
					$('#open-addr').val(json.addr.UMD_NM+' ' + json.addr.RI_NM);
					$('#open-address').val(json.addr.UMD_NM+' '+json.addr.RI_NM+gbn+bungi);					
					
					var checked = "";
//					alert($('.seladdr:checked').val() == )
					if( typeof $('.seladdr:checked').val() == 'undefined' ){
						checked = "checked='true'"
						
						$('#open-gongsijiga').val(json.jiga.JIGA);						
					}				

					var src = "<tr>"
	src += "<td width='40' height='35' align='left'>"+($('#jiga-area > tbody > tr').length+1)+"</td>";
	src += "<td width='180' align='left'>&nbsp;<span class='addr' style='display:none'>"+json.addr.UMD_NM+' '+json.addr.RI_NM + "</span>";
	src += "<span class='address'>"+json.addr.UMD_NM+' '+json.addr.RI_NM+gbn+bungi+"</span></td>";
	
		src += "<td width='120' align='center'><span class='use' data='"+json.jiga.USE_REGN1+"'>"+json.jiga.USE+"</span></td>";
		src += "<td width='150' align='center'>&nbsp;<span class='state' data='"+json.jiga.LAND_USE+"'>"+json.jiga.STATE+"</span></td>";
		src += "<td width='100' align='center'>&nbsp;<span class='jimok'>"+json.jiga.JIMOK+"</span></td>";
			
	src += "<td width='97' align='right'><input type='text' class='area number' value='"+json.jiga.LAND_AREA+"'/></td>";
	src += "<td width='104' align='right'><select class='jiga' style='width:80px;font-size:8px;'><option value='"+numberFormat(json.jiga.JIGA)+"'>"+numberFormat(json.jiga.JIGA)+"</option>";
	
				for(j = 0 ;  j < json.yjiga.length; j++ ){
					src +="<option value='"+numberFormat(json.yjiga[j].JIGA)+"("+json.yjiga[j].YEAR+"년)'>"+numberFormat(json.yjiga[j].JIGA)+"("+json.yjiga[j].YEAR+"년)</option>"
				}
				
					
	src += "</select></td>";
	src += "<td width='89' align='center'><input type='radio' name='addr' class='seladdr' value='"+json.jiga.LAND_CD+"' "+checked+"></td>";
	src += "<td width='50' align='left'>&nbsp;&nbsp;<a href='#del' class='del'><img src='img/del.jpg' border='0'><br></td>";
	src += "</tr>";
	/*
	src += " 																<tr><td height='1'  background='img/start_point_106.jpg'></td><td height='1' background='img/start_point_106.jpg'></td><td height='1' background='img/start_point_106.jpg'></td><td height='1' background='img/start_point_106.jpg'></td><td background='img/start_point_106.jpg'></td><td background='img/start_point_106.jpg'></td></tr>"; 
	//*/
																	
					$('#jiga-area > tbody').append($(src));		
					
		 $('.jiga:eq('+(($('#jiga-area > tbody > tr').length-1)*2)+')').editableSelect({
		      bg_iframe: true,
		      onSelect: function(list_item) {
//		      alert(	$('.editable-select-options').index($(this).parent().parent()));


						var idx = $('.editable-select-options').index(list_item.parent().parent());
						$('#editable-select' + idx ).val(this.text.val().split('(')[0]);


		      /*
		        alert('List item text: '+ list_item.text());
		        // 'this' is a reference to the instance of EditableSelect
		        // object, so you have full access to everything there
		        alert('Input value: '+ this.text.val());
		        */
		      },
		      case_sensitive: false, // If set to true, the user has to type in an exact
		                             // match for the item to get highlighted
		      items_then_scroll: 10 // If there are more than 10 items, display a scrollbar
		  });
		  
		  					
					$('.number').off();
					setEventNumber();
					$(".number").numeric();

		addr = json.addr.UMD_NM+json.addr.RI_NM+$.trim(gbn)+$.trim(bungi);
		addrString = json.addr.UMD_NM+' '+json.addr.RI_NM+gbn+bungi;
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
			},'json');	
			

}
</script>
<form id="form" onsubmit="return false" method="post" action="end.php">
<input type="hidden" name="open_data" id="open-data" value="<?php echo @$_POST['open_data'];?>" />

<input type="hidden" name="USE" id="use" value="<?php echo @$_POST['USE'];?>" />
<input type="hidden" name="STATE" id="state" value="<?php echo @$_POST['STATE'];?>" />
<input type="hidden" name="JIMOK" id="jimok" value="<?php echo @$_POST['JIMOK'];?>" />

<input type="hidden" name="open_addr" id="open-addr" value="<?php echo @$_POST['open_addr'];?>" />
<input type="hidden" name="open_address" id="open-address" value="<?php echo @$_POST['open_address'];?>" />
<input type="hidden" name="open_area" id="open-area" value="<?php echo $open_area;?>" />
<input type="hidden" name="open_jiga" id="open-jiga" value="<?php echo $open_jiga;?>" />

<input type="hidden" name="open_gongsijiga" id="open-gongsijiga" value="<?php echo @$_POST['open_gongsijiga'];?>" />
<!-- Save for Web Slices (001 개발부담금가산정_개시시점.JPG) -->
<table id="__01" width="1281" height="1025" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="33">
			<img src="img/101_StartPt_01.jpg" width="1280" height="26" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="26" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="8">
			<img src="img/101_StartPt_02.jpg" width="35" height="219" alt=""></td>
		<td colspan="2" rowspan="3">
			<img src="img/101_StartPt_03.jpg" width="142" height="72" alt=""></td>
		<td colspan="29">
			<img src="img/101_StartPt_04.jpg" width="1103" height="19" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="19" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="7">
			<img src="img/101_StartPt_05.jpg" width="21" height="200" alt=""></td>
		<td colspan="25">
			<img src="img/101_StartPt_06.jpg" width="1048" height="48" alt=""></td>
		<td rowspan="13">
			<img src="img/101_StartPt_07.jpg" width="14" height="897" alt=""></td>
		<td rowspan="23">
			<img src="img/101_StartPt_08.jpg" width="20" height="979" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="48" alt=""></td>
	</tr>
	<tr>
		<td rowspan="22">
			<img src="img/101_StartPt_09.jpg" width="7" height="931" alt=""></td>
		<td colspan="24" rowspan="2">
			<img src="img/101_StartPt_10.jpg" width="1041" height="8" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="5" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="3">
			<img src="img/101_StartPt_11.jpg" width="142" height="50" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="3" alt=""></td>
	</tr>
	<tr>
		<td colspan="8">
			<img src="img/101_StartPt_12.jpg" width="261" height="17" alt=""></td>
		<td>
			<img src="img/101_StartPt_13.jpg" width="17" height="17" alt=""></td>
		<td colspan="15">
			<img src="img/101_StartPt_14.jpg" width="763" height="17" alt=""></td>
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
	
						<table border='0' cellpadding='0' cellspacing='12' width='1010' background='img/search_bg.jpg' height='100'>
							<!-- 기간설정 -->
							<tr>
								<td align='left'>
									<table border='0' cellpadding='0' cellspacing='0'>
										<tr>
											<td><img src='img/start_point_25.jpg' border='0'><br></td>
											<td width='10'></td>
											<td>
<input type="text" name="sdate" size="10" id="sdate" class="sdate" value="<?php echo @$_POST['sdate']?>"/>
											</td>
											<td width='10'></td>
											<td style='font-family:dotum;font-size:9pt;'>부터~</td>
											<td width='10'></td>
											<td>
<input type="text" name="edate" size="10" id="edate" class="edate" value="<?php echo @$_POST['edate']?>"/>
											</td>
											<td width='10'></td>
											<td style='font-family:dotum;font-size:9pt;'>까지</td>
											<td width='10'></td>
											<td>

											</td>
										</tr>
									</table>
								</td>
							</tr>
							<!-- 기간설정 -->
							<!-- 주소검색 -->
							<tr>
								<td align='left'>
									<table border='0' cellpadding='0' cellspacing='0'>
										<tr>
											<td><img src='img/start_point_49.jpg' border='0'><br></td>
											<td width='10'></td>
											<td>
<select id="UMD" name="UMD">
<option value="">읍/면/동 선택</option>
<?php foreach($umd as $cd=>$nm):?>
<option value="<?=$cd?>" <?php if( @$_POST['UMD'] == $cd):?>selected="selected"<?php endif;?>><?=$nm?></option>
<?php endforeach;?>
</select>
											</td>
											<td width='10'></td>
											<td>
<select id="RI" name="RI">
<option value="">리 선택</option>
</select>
											</td>
											<td width='10'></td>
											<td>
<select id="G" name="G">
<option value="1">필지구분 선택</option>
<option value="1" <?php if( @$_POST['G'] == '1'):?>selected="selected"<?php endif;?>>일반지번</option>
<option value="2" <?php if( @$_POST['G'] == '2'):?>selected="selected"<?php endif;?>>산</option>
<option value="3" <?php if( @$_POST['G'] == '3'):?>selected="selected"<?php endif;?>>가지번</option>
<option value="5" <?php if( @$_POST['G'] == '5'):?>selected="selected"<?php endif;?>>블럭</option>
</select>
											</td>
											<td width='10'></td>
											<td>
<input type="text" name="S" id="S" size="5" maxlength="4" class="number2" value="<?php echo @$_POST['S'];?>"/> - <input type="text" name="E" id="E" class="number2" size="5" value="<?php echo @$_POST['E'];?>" maxlength="4" />
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
						<table border='0' cellpadding='0' cellspacing='0' height='15'><tr><td></td></tr></table>
						<table border='0' cellpadding='0' cellspacing='0' width='1010' height='626'>
							<tr>
							<!-- 공시지가검색 
								<td valign='top' width='228' background='img/gongsi_bg.jpg'>
									<table border='0' cellpadding='0' cellspacing='0' height='15'><tr><td></td></tr></table>
									<table border='0' cellpadding='0' cellspacing='0' width='228' >
										<tr>
											<td align='center'><img src='img/start_point_83.jpg' border='0'><br></td>
										</tr>
										<tr>
											<td align='center'><table border='0' cellpadding='0' cellspacing='0' height='15'><tr><td></td></tr></table><img src='img/start_point_93.jpg' border='0'><br></td>
										</tr>
										<tr>
											<td align='center'>

												<table border='0' cellpadding='0' cellspacing='0' style='font-family:dotum;font-size:9pt;' id="jiga-year">
													<tbody>
													</tbody>
												</table>
											
											</td>
										</tr>
										<tr>
									</table>
								</td>
							 공시지가검색 
								<td width='10'>&nbsp;</td>
								-->
								<td valign='top' width='769'>
									<table border='0' cellpadding='0' cellspacing='0' height='234' width='1009'  background='img/gongsi_lst_bg.jpg'>
										<tr>
											<!-- 지역별 공시지가 -->
											<td valign='top' width='1009' align='center'>
												<table border='0' cellpadding='0' cellspacing='0' height='15'><tr><td></td></tr></table>
												<table border='0' cellpadding='0' cellspacing='0' >
													<tr>
														<td align='center'><img src='img/start_point_87.jpg' border='0'><br></td>
													</tr>
													<tr>
														<td align='center'><table border='0' cellpadding='0' cellspacing='0' height='15'>
															<tr><td></td></tr></table><img src='img/start_point_96.jpg' border='0'><br></td>
													</tr>
													<tr>
														<td align='center'>
<div style="padding-left:17px;width:964px;height:130px;overflow-y:auto">
															<table border='0' cellpadding='0' cellspacing='0' style='font-family:dotum;font-size:9pt;' id="jiga-area">
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
									<table border='0' cellpadding='0' cellspacing='0' height='15'><tr><td></td></tr></table>
									<table border='0' cellpadding='5' cellspacing='0' width='1010'>
										<tr>
											<td align='right' width='450'><img src='img/start_point_159.jpg' border='0'><br></td>
											<td><img src='img/start_point_160.jpg' border='0'><br></td>
											<td width='10' align='center'><img src='img/start_point_162.jpg' border='0'><br></td>
											<td><input type="image" src='img/start_point_164.jpg' border='0' class="submit"><br></td>
											<td align='left'><input type="image" src='img/start_point_165.jpg' border='0' class="submit"><br></td>
										</tr>
									</table>
									<table border='0' cellpadding='0' cellspacing='0' height='12'><tr><td></td></tr></table>
									<table border='0' cellpadding='0' cellspacing='0' height='380' width='1009'  background='img/map_bg.jpg'>
										<tr>
										<!-- 지도보기 영역 -->
											<td valign='top' width='1009' align='center'>
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
																	<td width='776'>
																		
																	
																		
																	</td>
																	<td width='12'></td>
																	<td width='3'><img src='img/spe01.jpg' border='0'><br></td>
																	<td width='12'></td>
																	<td>
																		<table border='0' cellpadding='0' cellspacing='0' height='100%'>
																			<tr>
																				<td align='center'><a href="#zoomin" id="zoomin"><img src='img/plus.jpg' border='0'></a><a href="#zoomout" id="zoomout"><img src='img/minus.jpg' border='0'></a><br><br></td>
																			</tr>
																			<tr>
																				<td><img src='img/end_point_141.jpg' border='0'><br></td>
																			</tr>
																			<tr>
																				<td align='center'><br><a href="#map" id="open-map"><img src='img/end_point_144.jpg' border='0'></a><br></td>
																			</tr>
																		</table>
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
			<img src="img/101_StartPt_16.jpg" width="142" height="79" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="79" alt=""></td>
	</tr>
	<tr>
		<td colspan="2">
			<img src="img/101_StartPt_17.jpg" width="142" height="18" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="18" alt=""></td>
	</tr>
	<tr>
		<td rowspan="16">
			<img src="img/101_StartPt_18.jpg" width="30" height="779" alt=""></td>
		<td colspan="4">
			<img src="img/101_StartPt_19.jpg" width="162" height="29" alt=""></td>
		<td rowspan="3">
			<img src="img/101_StartPt_20.jpg" width="6" height="485" alt=""></td>
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
			<img src="img/101_StartPt_22.jpg" width="162" height="384" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="384" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="13">
			<img src="img/101_StartPt_23.jpg" width="11" height="294" alt=""></td>
		<td colspan="3">
			<div id='menu02' style='position:absolute;top:731px;left:41px;width:162px;'>
			<img src="img/101_StartPt_24.jpg" width="157" height="161" alt=""><br>
			<table border='0' cellpadding='0' cellspacing='0' height='7'><tr><td></td></tr></table>	
			</div>
			</td>
		<td>
			<img src="img/spacer.gif" width="1" height="161" alt=""></td>
	</tr>
	<tr>
		<td colspan="3" rowspan="12">
			<img src="img/101_StartPt_25.jpg" width="157" height="133" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="23" alt=""></td>
	</tr>
	<tr>
		<td colspan="24">
			<img src="img/101_StartPt_26.jpg" width="1041" height="28" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="28" alt=""></td>
	</tr>
	<tr>
		<td colspan="25">
			<img src="img/101_StartPt_27.jpg" width="1055" height="12" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="12" alt=""></td>
	</tr>
	<tr>
		<td rowspan="9">
			<img src="img/101_StartPt_28.jpg" width="20" height="70" alt=""></td>
		<td rowspan="8">
			<img src="img/101_StartPt_29.jpg" width="60" height="60" alt=""></td>
		<td rowspan="9">
			<img src="img/101_StartPt_30.jpg" width="32" height="70" alt=""></td>
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
			<img src="img/101_StartPt_44.jpg" width="91" height="70" alt=""></td>
		<td rowspan="2">
			<img src="img/101_StartPt_45.jpg" width="38" height="16" alt=""></td>
		<td rowspan="2"><span class="counter"><?php echo $counter->today();?></span></td>
		<td rowspan="9">
			<img src="img/101_StartPt_47.jpg" width="49" height="70" alt=""></td>
		<td rowspan="7">
			<img src="img/101_StartPt_48.jpg" width="57" height="57" alt=""></td>
		<td colspan="2" rowspan="9">
			<img src="img/101_StartPt_49.jpg" width="48" height="70" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="15" alt=""></td>
	</tr>
	<tr>
		<td rowspan="3">
			<img src="img/101_StartPt_50.jpg" width="108" height="9" alt=""></td>
		<td rowspan="3">
			<img src="img/101_StartPt_51.jpg" width="104" height="9" alt=""></td>
		<td rowspan="3">
			<img src="img/101_StartPt_52.jpg" width="62" height="9" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="1" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="img/101_StartPt_53.jpg" width="40" height="8" alt=""></td>
		<td rowspan="2">
			<img src="img/101_StartPt_54.jpg" width="40" height="8" alt=""></td>
		<td colspan="3" rowspan="2">
			<img src="img/101_StartPt_55.jpg" width="59" height="8" alt=""></td>
		<td rowspan="2">
			<img src="img/101_StartPt_56.jpg" width="79" height="8" alt=""></td>
		<td colspan="2">
			<img src="img/101_StartPt_57.jpg" width="103" height="4" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="4" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="img/101_StartPt_58.jpg" width="38" height="16" alt=""></td>
		<td rowspan="2"><span class="counter"><?php echo $counter->total();?></span></td>
		<td>
			<img src="img/spacer.gif" width="1" height="4" alt=""></td>
	</tr>
	<tr>
		<td colspan="15" rowspan="2">
			<img src="img/101_StartPt_60.jpg" width="595" height="28" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="12" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="4">
			<img src="img/101_StartPt_61.jpg" width="103" height="34" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="16" alt=""></td>
	</tr>
	<tr>
		<td colspan="15" rowspan="3">
			<img src="img/101_StartPt_62.jpg" width="595" height="18" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="5" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="img/101_StartPt_63.jpg" width="57" height="13" alt=""></td>
		<td>
			<img src="img/spacer.gif" width="1" height="3" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="img/101_StartPt_64.jpg" width="60" height="10" alt=""></td>
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
<?php include("inc/map.php");?>	
<?php include "inc/footer.php"; ?>