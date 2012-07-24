

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

	window.open('http://map.naver.com/?dlevel=11&lat='+point.y+'&lng='+point.x+'&menu=location&mapMode=0&enc=b64');
}


//ÄÞ¸¶Âï±â
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

//ÄÞ¸¶Á¦°Å
function unNumberFormat(num) {
	return (num.replace(/\,/g,""));
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
			$('#RI').append($('<option value="">¸® °Ë»ö</option>'));
			if( json == false ) return;
			for(cd in json){
				document.getElementById('RI').options[i++] = new Option(json[cd],cd);
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
			$('#RI2').append($('<option value="">¸® °Ë»ö</option>'));
			for(cd in json){
				document.getElementById('RI2').options[i++] = new Option(json[cd],cd);
			}
		},'json');
	});	

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
	

	$('.number2').numeric();
	
});