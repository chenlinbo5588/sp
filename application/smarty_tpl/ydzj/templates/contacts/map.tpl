{include file="common/website_header.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			{if !$isMobile}{include file="common/side_nav.tpl"}{/if}
			<div class="contentArea">
				<div class="breadcrumb">{$breadcrumb}</div>
				<div class="bd" id="articleInfo">
					<h2>{$siteSetting['site_name']|escape}</h2>
					<address><label>公司地址:</label><span>{$siteSetting['company_address']|escape}</span></address>
					<div id="mapDiv" style="height:400px;"></div>
				</div>
			</div>
			{if $isMobile}{include file="common/side_nav.tpl"}{/if}
		</div>
	</div>
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=qkNnEXk6nC3jcPTM8mv3dcE8"></script>
	<script>
		var map ;
		
		function loadJScript() {
			var script = document.createElement("script");
			script.type = "text/javascript";
			script.src = "http://api.map.baidu.com/api?v=2.0&ak=qkNnEXk6nC3jcPTM8mv3dcE8&callback=init";
			document.body.appendChild(script);
		}
		
		function init() {
			map = new BMap.Map("mapDiv");
			var point = new BMap.Point(121.563487,29.821112);
			map.centerAndZoom(point,16);
			map.addControl(new BMap.MapTypeControl());
			map.enableScrollWheelZoom(true);
			
			var top_left_control = new BMap.ScaleControl({ anchor: BMAP_ANCHOR_BOTTOM_LEFT });
			map.addControl(top_left_control);
			var navigationControl = new BMap.NavigationControl({
		    	// 靠左上角位置
		    	anchor: BMAP_ANCHOR_TOP_LEFT,
		    	// LARGE类型
		    	type: BMAP_NAVIGATION_CONTROL_LARGE,
		    	// 启用显示定位
		    	enableGeolocation: false
		  	});
		  	
		    map.addControl(navigationControl);
			addCompany();
		}
	    
	    function addCompany(){
	    	var opts = {
			  width : 200,     // 信息窗口宽度
			  height: 100,     // 信息窗口高度
			  title : "{$siteSetting['site_name']|escape}" , // 信息窗口标题
			  enableMessage:true,//设置允许信息窗发送短息
			  message:"亲耐滴，晚上一起吃个饭吧？戳下面的链接看下地址喔~"
			}
			
			var point = new BMap.Point(121.563487,29.821112);
			
			var marker = new BMap.Marker(point);  // 创建标注
			map.addOverlay(marker); 
			var infoWindow = new BMap.InfoWindow("地址：{$siteSetting['company_address']|escape}", opts);  
			map.openInfoWindow(infoWindow,point); 
	    }
	    
		$(function(){
			init();
			
			{*
			var myGeo = new BMap.Geocoder();
			// 将地址解析结果显示在地图上,并调整地图视野
			myGeo.getPoint("浙江省宁波市鄞州区学士路298号", function(point){
				console.log(point);
				if (point) {
					map.centerAndZoom(point, 16);
					map.addOverlay(new BMap.Marker(point));
				}else{
					alert("您选择地址没有解析到结果!");
				}
			}, "宁波市");
			
			map.addEventListener("click",function(e){
				console.log(e.point.lng);
				console.log(e.point.lat);
				//alert(e.point.lng + "," + e.point.lat);
			});
			*}
		});
	</script>
{include file="common/website_footer.tpl"}