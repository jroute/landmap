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
			var mapZoom = new nhn.api.map.ZoomControl(); // - �� ��Ʈ�� ����
			themeMapButton = new nhn.api.map.ThemeMapBtn(); // - ���������� ��ư ����
			mapTypeChangeButton = new nhn.api.map.MapTypeBtn(); // - ���� Ÿ�� ��ư ����
			var trafficButton = new nhn.api.map.TrafficMapBtn(); // - �ǽð� �������� ��ư ����
			trafficButton.setPosition({top:10, right:110}); // - �ǽð� �������� ��ư ��ġ ����
			mapTypeChangeButton.setPosition({top:10, left:50}); // - ���� Ÿ�� ��ư ��ġ ����
			themeMapButton.setPosition({top:10, right:10}); // - ���������� ��ư ��ġ ����
			mapZoom.setPosition({left:10, top:10}); // - �� ��Ʈ�� ��ġ ����.
			oMap.addControl(mapZoom);
			oMap.addControl(themeMapButton);
			oMap.addControl(mapTypeChangeButton);
			oMap.addControl(trafficButton);
			
			var oSize = new nhn.api.map.Size(28, 37);
			var oOffset = new nhn.api.map.Size(14, 37);
			var oIcon = new nhn.api.map.Icon('http://static.naver.com/maps2/icons/pin_spot2.png', oSize, oOffset);			
			
				var oMarker = new nhn.api.map.Marker(oIcon, { title : '��Ŀ : ' + oPoint.toString() });
				oMarker.setPoint(oPoint);
				oMap.addOverlay(oMarker);			
		</script>