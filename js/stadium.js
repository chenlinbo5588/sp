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
  
function initialize() {
  map = new BMap.Map('map');  
  map.centerAndZoom(new BMap.Point(121.272, 30.175), 14);
  
  //var top_left_control = new BMap.ScaleControl({ anchor: BMAP_ANCHOR_TOP_LEFT });// 左上角，添加比例尺
  //var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
  var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}); 
  var geoc = new BMap.Geocoder();
  
  //map.addControl(top_left_control);
  map.addControl(top_right_navigation);    
  
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
			
			$("input[name=address]").val(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
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
	loadScript();
	/*
	$("#moreFile").bind("click",function(event){
	    var count = $("#fileArea em").size() + 1;
	    
	    if(count > 5){
	    	alert("最多允许5张其他图片");
	    	return;
	    }
	    
	    var fileTpl = $($("#addFileTpl").html());
	    
	    fileTpl.find("input[type=file]").attr("name","other_img" + count);
	    fileTpl.find("input[type=hidden]").attr("name","other_img_txt" + count);
	    fileTpl.find("em").html(count);
	    
	    $("input[name=other_image_count]").val(count);
	    //fileTpl.insertBefore("#moreFileWrap");
	    $("#fileArea").append(fileTpl);
	});
	*/
	
	$("input[name=is_mine]").bind("click",function(e){
		if($(this).val() == 'n'){
			$(".notmine").slideDown();
		}else{
			$(".notmine").slideUp();
		}
	});
	
	
	$("#stadiumForm").bind("submit",function(e){
		return true;
		$("input.validation_error").removeClass("validation_error");
		
		if($.trim($("input[name=title]").val()) == ''){
			$("input[name=title]").addClass("validation_error").focus();
			$("#tip_title").html(FormErrorHtml('请输入场馆名称'));
			return false;
		}
		
		/*
		$("input[name=is_mine]").each(function(i,obj){
			if($(obj).prop("checked") && $(obj).val() == 'n'){
				if(regMobile.test()){
					
				}
			}
		});
		*/
		
		return true;
	});
	
	
});