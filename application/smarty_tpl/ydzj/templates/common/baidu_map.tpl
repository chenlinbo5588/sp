<script type="text/javascript">  
function loadScript() {  
  var script = document.createElement("script");  
  script.src = "http://api.map.baidu.com/api?v=2.0&ak=qkNnEXk6nC3jcPTM8mv3dcE8&callback=initialize";  
  document.body.appendChild(script);  
}

function getCurrentLocation(map,callbackFunction){
    var geolocation = new BMap.Geolocation();
    geolocation.getCurrentPosition(function(r){
        if(this.getStatus() == BMAP_STATUS_SUCCESS){
            var mk = new BMap.Marker(r.point);
            map.addOverlay(mk);
            map.panTo(r.point);
            
            callbackFunction(r);
            //alert('您的位置：'+r.point.lng+','+r.point.lat);
        }
        else {
            callbackFunction();
            alert('获取位置信息失败'+this.getStatus());
        }        
    },{ enableHighAccuracy: true })
}

</script>