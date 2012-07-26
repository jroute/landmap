

	zoom  = 10
function setZoomIn(){
	zoom++;
	if( zoom > 14 ) zoom = 14;
	oMap.setLevel(zoom);

}

function setZoomOut(){
	zoom--;
	if( zoom < 5 ) zoom = 5;
	oMap.setLevel(zoom);
}

function openMap(){
	var point = oMap.getCenter();

	window.open('http://map.naver.com/?dlevel=11&lat='+point.y+'&lng='+point.x+'&menu=location&mapMode=0&enc=b64&cadastral=on');
}


//ƒﬁ∏∂¬Ô±‚
function numberFormat(num) {
	try{
		var pattern = /(-?[0-9]+)([0-9]{3})/;
		while(pattern.test(num)) {
			num = num.replace(pattern,"$1,$2");
		}
	}catch(e){
		alert(e.message);
	}
	return num;
}

//ƒﬁ∏∂¡¶∞≈
function unNumberFormat(num) {
	try{
		return (num.replace(/\,/g,""));
	}catch(e){
		return 0;
	}
}


$(document).ready(function(){
	

		
		
		
	$('#zoomin').click(function(){
		setZoomIn();
		return false;		
	});
	
	$('#zoomout').click(function(){
		setZoomOut();
		return false;		
	});	
	
	$('#open-map').click(function(){
		openMap();
		return false;
	});	
	

	
	$('#UMD').change(function(){
		var val = $(this).val();
		if( val == '' ){
			return;
		}
		$.post('/json/ri.php',{q:val},function(json){
			i = 1;
			$('#RI > option').remove();
			$('#RI').append($('<option value="">∏Æ º±≈√</option>'));
			if( json == false || json.length == 0 ) return;
			for(j  = 0 ; j < json.length ; j++){
				document.getElementById('RI').options[i++] = new Option(json[j].RI_NM,json[j].RI_CD);
			}
		},'json');
	});
	
	$('#UMD2').change(function(){
		var val = $(this).val();
		if( val == '' ){
			return;
		}
		$.post('/json/ri.php',{q:val},function(json){
			i = 1;
			$('#RI2 > option').remove();
			$('#RI2').append($('<option value="">∏Æ º±≈√</option>'));

			if( json == false || json.length == 0 ) return;

			for(j  = 0 ; j < json.length ; j++){
//			alert(json[j].RI_NM)
				document.getElementById('RI2').options[i++] = new Option(json[j].RI_NM,json[j].RI_CD);
			}
		},'json');
	});	
	

	$('.number2').numeric();
	setEventNumber();
});




function setEventNumber(){
	$('.number').off();
	$('.number').keypress(function(e){	
		var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		if( key == 13 ) return;
//alert(key)
		if( (key >= 48 && key <= 58) || key == 8 || key == 37 || key == 39){
			return true;
		}else{

      return false;
		}	
	})
	$('.number').keyup(function(e){
			$(this).val(numberFormat(unNumberFormat($(this).val())));

	});
	
	
	$('input.jiga').unbind('keypress');
	$('input.jiga').unbind('keyup');
	$('input.jiga').keypress(function(e){	
		var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		if( key == 13 ) return;
//alert(key)
		if( (key >= 48 && key <= 58) || key == 8 || key == 37 || key == 39){
			return true;
		}else{

      return false;
		}	
	})
	$('input.jiga').keyup(function(e){
		var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;	
		if( key == 13 ) return false;
			$(this).val(numberFormat(unNumberFormat($(this).val())));

	});
	
}