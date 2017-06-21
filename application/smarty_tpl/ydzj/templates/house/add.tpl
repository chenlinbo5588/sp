{include file="common/my_header.tpl"}
   {include file="common/swfupload.tpl"}
   {include file="common/fancybox.tpl"}
   {include file="common/arcgis_common.tpl"}
   {include file="./house_form.tpl"}
   <style>
      #mapDiv {
         height: 600px;
         width: 100%;
         margin: 0;
         padding: 0;
      }
   </style>
   {include file="./house_script.tpl"}
   <script>
      var map, pt;
      
      var point_x = "{$info['x']}";
      var point_y = "{$info['y']}";
      
      function setPointXY(e){
		    $("input[name=x]").val(e.mapPoint.x);
		    $("input[name=y]").val(e.mapPoint.y);
		    
	  }
	  
	  function createPoint(){
	  	if(point_x && point_y){
	  		return { x: point_x , y : point_y };
	  	}
	  }
	  
	  
	  
	  
	  $(function(){
	  		$(".date" ).datepicker({
	  			changeMonth: true,
      			changeYear: true
	  		});
	  		
	  		$(".Uploader").each(function(index){
                var handler = {
                    success:function(file,serverData){
                        try {
                            var progress = new FileProgress(file, this.customSettings.progressTarget);
                            progress.setComplete();
                            progress.setStatus("Complete.");
                            progress.toggleCancel(false);

                            if(typeof(this.customSettings.callback) != "undefined"){
                                this.customSettings.callback(file,serverData);
                            }
                            
                            var response = $.parseJSON(serverData);
                            
                            if(response.data.error == 0){
                            	add_uploadedfile(response.data);
                            }

                        } catch (ex) {
                            this.debug(ex);
                        }
                    }
                };

                createSwfUpload(index + 1,$(this).attr("data-url"),{ owner_id: "{$info['owner_id']}" , house_id : "{$info['hid']}" },$(this).attr("data-allowsize"),$(this).attr("data-allowfile"),handler);
            });
	  		
	  		$.loadingbar({ urls: [ new RegExp('{site_url('house/add')}'), new RegExp('{site_url('house/edit')}') ],text:"操作中,请稍后..."});
	  		
	  		bindAjaxSubmit("#user_form");
	    	
	    	$('.fancybox').fancybox();
	  });
	  
	  
      require([
        "esri/map", 
        "esri/layers/GraphicsLayer","esri/graphic", 
        "esri/Color",
        "esri/geometry/Point",
        "esri/symbols/SimpleMarkerSymbol",
        "esri/layers/FeatureLayer",
        "dojo/_base/event",
        "dojo/_base/array",
        "dojo/keys",
        "dojo/dom", 
        "dojo/dom-style", 
        "dijit/Menu",
        "dojo/parser", 
        "dijit/registry",
        "dijit/layout/BorderContainer", 
        "dijit/layout/ContentPane", 
        "dijit/form/Button", 
        "dijit/WidgetSet", 
        "dojo/domReady!"
      ], function(
        Map,
        GraphicsLayer,Graphic,
        Color,
        Point,
        SimpleMarkerSymbol,
        FeatureLayer,
        event, 
        arrayUtils,
        keys,
        dom, 
        domStyle,
        Menu,
        parser, 
        registry
      ) {
        parser.parse();
        
        var sym = new SimpleMarkerSymbol();
        sym.setColor(new Color([255,0,0]));
        
        var resizeTimer;
        map = new Map("mapDiv",{ showLabels : true, logo: false });
        
        //var layerWx = new esri.layers.ArcGISTiledMapServiceLayer("{config_item('arcgis_server')}{$mapUrlConfig['基本要素']['底图']}");
        var layerWx = new esri.layers.ArcGISDynamicMapServiceLayer("{config_item('arcgis_server')}{$mapUrlConfig['基本要素']['底图']}");
        
        var villageLayer = new FeatureLayer("{config_item('arcgis_server')}{$mapUrlConfig['基本要素']['村界']}",{
          showLabels:true,
          mode: FeatureLayer.MODE_SNAPSHOT,
          outFields: ["*"],
          id : "village"
        });
        
        buildingLayer = new FeatureLayer("{config_item('arcgis_server')}{$mapUrlConfig['编辑要素']['标准建筑点']}",{
	        showLabels:true,
	        mode: FeatureLayer.MODE_ONDEMAND,
	        outFields: ['*'],
	        id : 'building',
	        minScale:3000,
	        displayOnPan:false
	    });
	    
	    buildingLayer.setDefinitionExpression("village_id = {$profile['basic']['village_id']}");
        
        var layersNeedAdd = [layerWx,villageLayer,buildingLayer];
        map.addLayers(layersNeedAdd);
        
        dojo.connect(villageLayer, "onLoad", function(){
        	if(typeof(createPoint) == 'undefined'){
        		map.setExtent(villageLayer.fullExtent);
        	}
		});
		
		
		$("#initMap").bind('click',function(){
			map.setExtent(villageLayer.fullExtent);
		});
		
		$("#gotoBuilding").bind('click',function(){
			map.setScale(500);
            map.centerAt(pt);
		});
		
		
	    dojo.connect(map, 'onLoad', function(theMap) {
	      if(typeof(createPoint) !== 'undefined'){
	           var point = createPoint();
	           if(point){
	           	   pt = new Point([point.x, point.y],map.spatialReference);
	           	   
	           	   {if empty($info['object_id'])}
	               var graphic = new Graphic(pt, sym);
                   map.graphics.add(graphic);
                   {/if}
                   
                   map.setScale(500);
                   map.centerAt(pt);
	           }
	      }
	      
	      dojo.connect(dijit.byId('mapDiv'), 'resize', function() {
	        clearTimeout(resizeTimer);
	        resizeTimer = setTimeout(function() {
	          map.resize();
	          map.reposition();
	         }, 500);
	       });
	    });
	    
	    
	    map.on("click",function(e){
	    	
            map.graphics.clear();
            var graphic = new Graphic(e.mapPoint, sym);
            //console.log(e.mapPoint.toJson());
            map.graphics.add(graphic);
            
            if(typeof(setPointXY)){
                setPointXY(e);
            }
        });
        
      });
      
   </script>
   <p class="tip_warning">请鼠标点击图上点空间标注，使用鼠标滚轮进行放大缩小地图 <a class="warning" href="javascript:void(0);" id="initMap">点击显示全图</a> {if $info['object_id']}<a class="warning" href="javascript:void(0);" id="gotoBuilding">缩放至建筑</a>{/if}</p>
   <div class="search_con"><div id="mapDiv"></div></div>
   
{include file="common/my_footer.tpl"}

