{include file="common/admin_header.tpl"}

<script>
var map;

function setJW(obj){
     $.mobile.loading( "hide" );
     //console.log(obj);
     if(typeof(obj) != "undefined"){
	     $("input[name=longitude]").val(obj.longitude);
	     $("input[name=latitude]").val(obj.latitude);
	     
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
	    var $this = $( this ),
	        theme = $this.jqmData( "theme" ) || $.mobile.loader.prototype.options.theme,
	        msgText = $this.jqmData( "msgtext" ) || $.mobile.loader.prototype.options.text,
	        textVisible = $this.jqmData( "textvisible" ) || $.mobile.loader.prototype.options.textVisible,
	        textonly = !!$this.jqmData( "textonly" );
	        html = $this.jqmData( "html" ) || "";
	
	    $.mobile.loading( "show", {
	            text: msgText,
	            textVisible: textVisible,
	            theme: theme,
	            textonly: textonly,
	            html: html
	    });

      getCurrentLocation(map,setJW);
  });
}
</script>

{include file="common/baidu_map.tpl"}

{form_open(admin_site_url('stadium/add'))}
    <input type="hidden" name="longitude" value=""/>
    <input type="hidden" name="latitude" value=""/>
    <label>场馆名称</label>
    <input type="text" name="title" value="{$smarty.post.title}" placeholder="请输入场馆名称"/>
    
    <label>场馆地址(可通过获取位置自动填充)</label>
    <input type="text" name="address" value="{$smarty.post.address}" placeholder="请输入场馆地址"/>
    <button type="button" name="getAddress" class="ui-btn ui-icon-navigation ui-btn-icon-left ui-shadow ui-corner-all" data-textonly="false" data-textvisible="true" data-msgtext="正在获取位置" data-inline="true">获取地址</button>
    <div style="padding:0 20px;"><div id="map" style="height:320px"></div></div>
    
    <label>联系人</label>
    <input type="text" name="contact" value="{$smarty.post.contact}" placeholder="请输入联系人名称"/>
    <label>联系人手机号码</label>
    <input type="text" name="mobile" value="{$smarty.post.mobile}" placeholder="请输入联系人手机号码"/>
    <label>联系人手机号码</label>
    <input type="text" name="mobile" value="{$smarty.post.mobile}" placeholder="请输入联系人手机号码"/>

</form>

<a href="{admin_site_url('stadium')}" class="ui-btn">返回</a>
{include file="common/admin_footer.tpl"}