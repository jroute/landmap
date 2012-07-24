<script type="text/javascript">
	if( $.browser.version == '6.0' ){
		try {document.execCommand('BackgroundImageCache', false, true);} catch(e) {}
	}
		
</script>

<script type="text/javascript" src="http://openapi.map.naver.com/openapi/naverMap.naver?ver=2.0&key=<?=$naver_api_key?>"></script>


<div id = "naver-map" style="position:absolute; border:1px solid #000; width:763px; height:456px; margin:0px; top:575px; left:245px"></div>
		<script type="text/javascript">
			var oPoint = new nhn.api.map.LatLng(36.8936490, 126.6281630);
			nhn.api.map.setDefaultPoint('LatLng');
			oMap = new nhn.api.map.Map('naver-map' ,{
						point : oPoint, 
						zoom : 10,
						enableWheelZoom : true,
						enableDragPan : true,
						enableDblClickZoom : false,
						mapMode : 0,
						activateTrafficMap : false,
						activateBicycleMap : false,
						minMaxLevel : [ 1, 14 ],
						size : new nhn.api.map.Size(763, 456)
					});
			var mapZoom = new nhn.api.map.ZoomControl(); // - 줌 컨트롤 선언
			themeMapButton = new nhn.api.map.ThemeMapBtn(); // - 자전거지도 버튼 선언
			mapTypeChangeButton = new nhn.api.map.MapTypeBtn(); // - 지도 타입 버튼 선언
			var trafficButton = new nhn.api.map.TrafficMapBtn(); // - 실시간 교통지도 버튼 선언
			trafficButton.setPosition({top:10, right:110}); // - 실시간 교통지도 버튼 위치 지정
			mapTypeChangeButton.setPosition({top:10, left:50}); // - 지도 타입 버튼 위치 지정
			themeMapButton.setPosition({top:10, right:10}); // - 자전거지도 버튼 위치 지정
			mapZoom.setPosition({left:10, top:10}); // - 줌 컨트롤 위치 지정.
			oMap.addControl(mapZoom);
			oMap.addControl(themeMapButton);
			oMap.addControl(mapTypeChangeButton);
			oMap.addControl(trafficButton);
			
			var oSize = new nhn.api.map.Size(28, 37);
			var oOffset = new nhn.api.map.Size(14, 37);
			var oIcon = new nhn.api.map.Icon('http://static.naver.com/maps2/icons/pin_spot2.png', oSize, oOffset);			
			
				var oMarker = new nhn.api.map.Marker(oIcon, { title : '마커 : ' + oPoint.toString() });
				oMarker.setPoint(oPoint);
				oMap.addOverlay(oMarker);			
		</script>