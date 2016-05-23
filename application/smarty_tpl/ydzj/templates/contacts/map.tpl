{include file="common/header_main_nav.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			<div class="sideNav">
				<ul class="sideItem">
					<li class="itemTitle"><h3><a href="{$sideTitleUrl}">{$sideTitle}</a></h3></li>
					{foreach from=$sideNavs item=item key=key}
					<li><a href="{$item}">{$key}</a></li>
					{/foreach}
				</ul>
			</div>
			<div class="contentArea">
				<div class="breadcrumb">{$breadcrumb}</div>
				<div class="bd" id="articleInfo">
					<h2>杭州标度环保技术有限公司</h2>
					<address><label>公司地址:</label><span>XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX</span></address>
					<div id="mapDiv" style="height:400px;"></div>
				</div>
			</div>
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
			var point = new BMap.Point(120.085741,30.324261);
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
			  title : "海底捞王府井店" , // 信息窗口标题
			  enableMessage:true,//设置允许信息窗发送短息
			  message:"亲耐滴，晚上一起吃个饭吧？戳下面的链接看下地址喔~"
			}
			
			var point = new BMap.Point(120.086945,30.319117);
			
			var marker = new BMap.Marker(point);  // 创建标注
			map.addOverlay(marker); 
			var infoWindow = new BMap.InfoWindow("地址：北京市东城区王府井大街88号乐天银泰百货八层", opts);  
			map.openInfoWindow(infoWindow,point); 
	    }
	    
		$(function(){
			init();
			{*
			map.addEventListener("click",function(e){
				console.log(e.point.lng);
				console.log(e.point.lat);
				//alert(e.point.lng + "," + e.point.lat);
			});
			*}
		});
	</script>
{include file="common/footer.tpl"}