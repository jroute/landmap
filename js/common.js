

	zoom  = 10
function setZoom(){
	zoom++;
	if( zoom > 14 ) zoom = 14;
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
	

		
		
		
	$('#zoom').click(function(){
		setZoom();
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
	
	$('.number').keyup(function(){
		$(this).val(numberFormat(unNumberFormat($(this).val())));
	});
	
	
});