var map;

/**
 * 设置经纬度
 * @param obj
 */
function setJW(obj){

     //console.log(obj);
     if(typeof(obj) != "undefined"){
	     $("input[name=longitude]").val(obj.longitude);
	     $("input[name=latitude]").val(obj.latitude);
         $("input[name=has_coordinates]").val(1);
	     
	     var geoc = new BMap.Geocoder();
	     
	     geoc.getLocation(obj.point, function(rs){
	        var addComp = rs.addressComponents;
	        var address = addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber;
	        $("input[name=address]").val(address);
	        //alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
	    });
    }
}

function addMyIcon(lng,lat){
	map.clearOverlays();
	var point = new BMap.Point(lng, lat);
    var myIcon = new BMap.Icon(stadiumPic, new BMap.Size(32,32));
    var marker = new BMap.Marker(point,{icon:myIcon});  // 创建标注
    marker.setAnimation(BMAP_ANIMATION_BOUNCE);
	map.addOverlay(marker);           				 // 将标注添加到地图中
	
	map.panTo(point);
}


function initialize() {
  map = new BMap.Map('map');  
  map.centerAndZoom(new BMap.Point(121.263186, 30.190465), 12);
  
  //var top_left_control = new BMap.ScaleControl({ anchor: BMAP_ANCHOR_TOP_LEFT });// 左上角，添加比例尺
  //var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
  var top_left_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_LEFT, type: BMAP_NAVIGATION_CONTROL_SMALL}); 
  var geoc = new BMap.Geocoder();
  
  //map.addControl(top_left_control);
  map.addControl(top_left_navigation);    
  
  map.enableScrollWheelZoom();   //启用滚轮放大缩小，默认禁用
  map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
  
  if(typeof(longitude) != "undefined"){
	  addMyIcon(longitude,latitude);
  }
  
  map.addEventListener("click",function(e){
	  $("input[name=longitude]").val(e.point.lng);
	  $("input[name=latitude]").val(e.point.lat);
	  
	  var point = new BMap.Point(e.point.lng, e.point.lat);
	  
	  addMyIcon(e.point.lng, e.point.lat);
	  
	  /*
	  var myIcon = new BMap.Icon(stadiumPic, new BMap.Size(32,32));
	  var marker = new BMap.Marker(point,{icon:myIcon});  // 创建标注
	  //marker.setAnimation(BMAP_ANIMATION_BOUNCE);
	  
	  map.addOverlay(marker);           				 // 将标注添加到地图中
	  */
	  
	  geoc.getLocation(point, function(rs){
			var addComp = rs.addressComponents;
			$("input[name=province]").val(addComp.province);
			$("input[name=city]").val(addComp.city);
			$("input[name=district]").val(addComp.district);
			$("input[name=street]").val(addComp.street);
			$("input[name=street_number]").val(addComp.streetNumber);
			
			$("#tip_address").html('');
			$("input[name=address]").removeClass("validation_error").val(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
			
			
			//alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
		});
	  //alert(e.point.lng + "," + e.point.lat);
  });
}