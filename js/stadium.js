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
  map.centerAndZoom(new BMap.Point(121.491, 31.233), 15);
  
  var top_left_control = new BMap.ScaleControl({ anchor: BMAP_ANCHOR_TOP_LEFT });// 左上角，添加比例尺
  var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
  
  map.addControl(top_left_control);
  map.addControl(top_left_navigation);    
  
  //getCurrentLocation(map,setJW);
  
  $("button[name=getAddress]").bind("click",function(){
       //获取当前地址
      getCurrentLocation(map,setJW);
  });
}



$(function(){
	
	loadScript();
	
	
	$("#moreFile").bind("click",function(event){
	    var count = $("#fileArea em").size() + 1;
	    var fileTpl = $($("#addFileTpl").html());
	    
	    fileTpl.find("input").attr("name","other_img" + count);
	    fileTpl.find("em").html(count);
	    
	    $("input[name=other_image_count]").val(count);
	    
	    //fileTpl.insertBefore("#moreFileWrap");
	    $("#fileArea").append(fileTpl);
	});
	
	
	
});