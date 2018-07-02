$(function(){
	
	$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
	bindAjaxSubmit("#infoform");
	
	
	$("input[name=autoFillAddress]").bind('click',function(){
		$("#address").val($("#show_address").html());
	});
	
	
	AMapUI.loadUI(['misc/PositionPicker'], function(PositionPicker) {
        map = new AMap.Map('container', {
            resizeEnable: true,
	        center: [121.266559, 30.170476],
	        zoom: initZoom
        })
        var positionPicker = new PositionPicker({
            mode: 'dragMap',
            map: map
        });
        
        
        map.on("complete", function(e) {
        	if(typeof(centerData) != "undefined"){
        		
        		var pos = new AMap.LngLat(centerData[0],centerData[1]);
        		
        		var marker = new AMap.Marker({
        			size: new AMap.Size(60,60),
        		    position: pos
        		});
        		
        		map.add(marker);
        		map.panTo(pos);
        	}
        	
        });
            

        positionPicker.on('success', function(positionResult) {
        	//console.log(positionResult);
        	$("#lnglat").html(positionResult.position.lng + "," + positionResult.position.lat);
        	
        	$("input[name=lng]").val(positionResult.position.lng);
        	$("input[name=lat]").val(positionResult.position.lat);
        	
        	//$("#address").val(positionResult.address);
        	$("#show_address").html(positionResult.address);
        	$("#nearestJunction").html(positionResult.nearestJunction);
        	$("#nearestRoad").html(positionResult.nearestRoad);
        	$("#nearestPOI").html(positionResult.nearestPOI);
            
        });
        
        positionPicker.on('fail', function(positionResult) {
        	$("#lnglat").html('');
        	//$("#address").val('');
        	$("#show_address").html('');
        	$("#nearestJunction").html('');
        	$("#nearestRoad").html('');
        	$("#nearestPOI").html('');
        	
        	$("input[name=lng]").val('');
        	$("input[name=lat]").val('');
        	
        });
        var onModeChange = function(e) {
            positionPicker.setMode(e.target.value)
        }
        var startButton = document.getElementById('start');
        var stopButton = document.getElementById('stop');
        var dragMapMode = document.getElementsByName('mode')[0];
        var dragMarkerMode = document.getElementsByName('mode')[1];
        AMap.event.addDomListener(startButton, 'click', function() {
            positionPicker.start(map.getBounds().getSouthWest())
        })
        AMap.event.addDomListener(stopButton, 'click', function() {
            positionPicker.stop();
        })
        AMap.event.addDomListener(dragMapMode, 'change', onModeChange)
        AMap.event.addDomListener(dragMarkerMode, 'change', onModeChange);
        positionPicker.start();
        map.panBy(0, 1);
    });
});