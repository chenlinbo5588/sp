$.loadingbar({ autoHide: true ,text:"更新中,请稍后..."});
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
    //marker.setAnimation(BMAP_ANIMATION_BOUNCE);
	map.addOverlay(marker);           				 // 将标注添加到地图中
}


function initialize() {
  map = new BMap.Map('map');  
  map.centerAndZoom(new BMap.Point(121.272, 30.175), 14);
  
  //var top_left_control = new BMap.ScaleControl({ anchor: BMAP_ANCHOR_TOP_LEFT });// 左上角，添加比例尺
  //var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
  var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}); 
  var geoc = new BMap.Geocoder();
  
  //map.addControl(top_left_control);
  map.addControl(top_right_navigation);    
  
  
  if(typeof(longitude) != "undefined"){
	  addMyIcon(longitude,latitude);
  }
  
  map.addEventListener("click",function(e){
	  $("input[name=longitude]").val(e.point.lng);
	  $("input[name=latitude]").val(e.point.lat);
	  
	  map.clearOverlays();
	  
	  var point = new BMap.Point(e.point.lng, e.point.lat);
	  var myIcon = new BMap.Icon(stadiumPic, new BMap.Size(32,32));
	  var marker = new BMap.Marker(point,{icon:myIcon});  // 创建标注
	  //marker.setAnimation(BMAP_ANIMATION_BOUNCE);
	  
	  map.addOverlay(marker);           				 // 将标注添加到地图中
	  
	  geoc.getLocation(point, function(rs){
			var addComp = rs.addressComponents;
			$("input[name=province]").val(addComp.province);
			$("input[name=city]").val(addComp.city);
			$("input[name=district]").val(addComp.district);
			$("input[name=street]").val(addComp.street);
			$("input[name=streetNumber]").val(addComp.streetNumber);
			
			$("#tip_address").html('');
			$("input[name=address]").removeClass("validation_error").val(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
			
			
			//alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
		});
	  //alert(e.point.lng + "," + e.point.lat);
  });
  
  //getCurrentLocation(map,setJW);
  
  /*
  $("button[name=getAddress]").bind("click",function(){
       //获取当前地址
      getCurrentLocation(map,setJW);
  });
  */
  
}



$(function(){
	
	if(typeof(hash) != "undefined"){
		location.hash = hash;
	}
	
	$("#markerOnMap").bind("click",function(e){
		var w = $(document).width(),h = $(document).height();
		
		$("#mapDiv").css({width:w + 'px',height:h + 'px'}).show();
		$("#map").css({height:h + 'px'});
		
		$("#saveBtn").hide();
		$("#closeMapBtn").show();
		
		if(typeof(map) == "undefined"){
			loadScript();
		}
	});
	
	$("input[name=closeMap]").bind("click",function(){
		$("#mapDiv").hide();
		$("#closeMapBtn").hide();
		$("#saveBtn").show();
		
	});
	
	$("select[name=is_mine]").bind("change",function(e){
		if($(this).val() == 'n'){
			$(".notmine").slideDown();
		}else{
			$(".notmine").slideUp();
		}
	});
	
	$(".photoUpload .opTrash").bind("click",function(e){
		
		//var that = $(this) , div = 
		
		
	});
	
	$("#stadiumForm").bind("submit",function(e){
		$("input.validation_error").removeClass("validation_error");
		
		if($.trim($("input[name=title]").val()) == ''){
			$("input[name=title]").addClass("validation_error").focus();
			$("#tip_title").html(FormErrorHtml('请输入场馆名称'));
			return false;
		}
		
		if($.trim($("input[name=address]").val()) == ''){
			$("input[name=address]").addClass("validation_error").focus();
			$("#tip_address").html(FormErrorHtml('请在地图上标记场馆位置'));
			return false;
		}
		
		return true;
	});
	
	
});