{include file="common/my_header.tpl"}
   {config_load file="person.conf"}
   {include file="common/fancybox.tpl"}
   {include file="common/arcgis_common.tpl"}
   {include file="common/swfupload.tpl"}
   
   <style>
      #mapDiv {
         height: 780px;
         width: 100%;
         margin: 0;
         padding: 0;
      }
   </style>
   <script>
      var map, scalebar,measurement, panWorkLayer, toolbar,editToolbar,buildingInfoDlg;
      
      $(function(){
      		buildingInfoDlg = $("#buildingInfoDlg" ).dialog({
		        autoOpen: false,
		        width: '98%',
		        modal: false,
		        open:function(){
		        	
		        }
		    });
		    
		    $('.fancybox').fancybox();
      });
      
	var showDetail = function(evt){
		buildingInfoDlg.find(".loading_bg").show();
		buildingInfoDlg.dialog('open');
		buildingInfoDlg.find(".dlgContent").load('{site_url('house/detail?hid=')}' + evt.graphic.attributes.hid,function(){
			buildingInfoDlg.find(".loading_bg").hide();
		});
	}
  
      
      require([
        "esri/map","esri/dijit/Scalebar","esri/dijit/Legend","esri/dijit/Measurement","esri/toolbars/edit","esri/toolbars/draw","esri/Color",
        "esri/dijit/editing/Editor","esri/geometry/Point","esri/geometry/Polyline","esri/geometry/Polygon","esri/geometry/Circle",
        "esri/layers/GraphicsLayer","esri/graphic", 
        "esri/symbols/SimpleMarkerSymbol","esri/symbols/SimpleLineSymbol","esri/symbols/SimpleFillSymbol",
        "esri/symbols/TextSymbol","esri/symbols/Font", "esri/symbols/PictureMarkerSymbol",
        "esri/layers/FeatureLayer","esri/tasks/GeometryService",
        "esri/tasks/query",
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
        Map,Scalebar,Legend, Measurement,Edit,Draw, Color,
        Editor,Point, Polyline, Polygon,Circle,
        GraphicsLayer,Graphic,
        SimpleMarkerSymbol, SimpleLineSymbol, SimpleFillSymbol,
        TextSymbol,Font, PictureMarkerSymbol,
        FeatureLayer,GeometryService,
        Query,
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
        
        esriConfig.defaults.io.proxyUrl = "{base_url('proxy.php')}";
        esriConfig.defaults.io.alwaysUseProxy = false;
        
        var searchPicSym = new PictureMarkerSymbol('{resource_url('img/new.png')}', 32, 32);
        var hightLightPicSym = new PictureMarkerSymbol('{resource_url('img/new2.png')}', 32, 32); 
        
        esriConfig.defaults.geometryService = new GeometryService("{config_item('arcgis_server')}{$mapUrlConfig['工具']['几何']}");
        
        var sym = new SimpleMarkerSymbol();
        sym.setColor(new Color([255,0,0]));
        
        var resizeTimer;
        map = new Map("mapDiv",{ showLabels : true ,logo: false });
        
        //var layerWx = new esri.layers.ArcGISTiledMapServiceLayer("{config_item('arcgis_server')}{$mapUrlConfig['基本要素']['底图']}");
        var layerWx = new esri.layers.ArcGISDynamicMapServiceLayer("{config_item('arcgis_server')}{$mapUrlConfig['基本要素']['底图']}");
        var allFeatureMap = new esri.layers.ArcGISDynamicMapServiceLayer("{config_item('arcgis_server')}{$mapUrlConfig['基本要素']['存量建筑要素']}");
        
        panWorkLayer = new GraphicsLayer({ id : "panWork" });
        
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
	        displayOnPan:false
	    });
	    
	    buildingLayer.setDefinitionExpression("village_id = {$profile['basic']['village_id']}");
	    
	    dojo.connect(buildingLayer, "onLoad", function(){
	    	dojo.connect(buildingLayer, "onClick", showDetail);
		});
		
        dojo.connect(villageLayer, "onLoad", function(){
        	map.setExtent(villageLayer.fullExtent);
		});
		
		
		var layersNeedAdd = [layerWx,villageLayer,buildingLayer,panWorkLayer];
        map.addLayers(layersNeedAdd);
		
		$(".panTool button").bind("click",activateTool);
        
        map.on("load", createToolbar);
        
		function activateTool(event) {
           
          if($(this).html() == '清空'){
            map.getLayer("panWork").clear();
            return ;
          } 
          var tool = $(this).attr("data-sharp").toUpperCase().replace(/ /g, "_");
          toolbar.activate(Draw[tool]);
          map.hideZoomSlider();
        }

        function createToolbar(themap) {
          // 工具
          toolbar = new Draw(map, { showTooltips : true } );
          toolbar.on("draw-end", addToMap);
          
          //编辑
          editToolbar = new Edit(map);
          
          map.getLayer("panWork").on("click", function(evt) {
            event.stop(evt);
            //console.log(evt.graphic);
            activateToolbar(evt.graphic);
          });
          
          //deactivate the toolbar when you click outside a graphic
          map.on("click", function(evt){
            //console.log(evt.mapPoint);
            editToolbar.deactivate();
          });
          
        }

        function addToMap(evt) {
          //console.log(evt);
          var symbol;
          toolbar.deactivate();
          map.showZoomSlider();
          
          switch (evt.geometry.type) {
            case "point":
            case "multipoint":
              symbol = new SimpleMarkerSymbol();
              break;
            case "polyline":
              symbol = new SimpleLineSymbol();
              break;
            default:
              symbol = new SimpleFillSymbol();
              break;
          }
          var graphic = new Graphic(evt.geometry, symbol);
          map.getLayer("panWork").add(graphic);
          
          //map.graphics.add(graphic);
        }
        
        function activateToolbar(graphic) {
          var tool = 0;
          //tool = Edit.MOVE | Edit.EDIT_VERTICES | Edit.SCALE | Edit.ROTATE; 
          tool = Edit.MOVE | Edit.SCALE | Edit.ROTATE; 
          
          // enable text editing if a graphic uses a text symbol
          if ( graphic.symbol.declaredClass === "esri.symbol.TextSymbol" ) {
            tool = tool | Edit.EDIT_TEXT;
          }

          var options = {
            allowAddVertices : true,
            allowDeleteVertices : true,
            uniformScaling : true
          }
          
          editToolbar.activate(tool, graphic, options);
        }
		
	    dojo.connect(map, 'onLoad', function(theMap) {
	      
	      dojo.connect(dijit.byId('mapDiv'), 'resize', function() {
	        clearTimeout(resizeTimer);
	        resizeTimer = setTimeout(function() {
	          map.resize();
	          map.reposition();
	         }, 500);
	       });
	       
	       measurement = new Measurement({ map: map }, dojo.byId('measurementDiv'));
		   measurement.startup();
		   
	       legend = new Legend({
			    map: map,
			    arrangement:esri.dijit.Legend.ALIGN_LEFT,
			    layerInfos:[{ layer : allFeatureMap ,title:'存量建筑要素图例' }]
			  }, "legendDiv");
			  legend.startup();
		  
	    });
	    
	    var getQuery = function(){
        	var query = new Query();
            //query.outFields = ["*"];
            
            //空间搜索 最后一次指定的范围
            if(panWorkLayer.graphics.length > 0){
                query.geometry = panWorkLayer.graphics[panWorkLayer.graphics.length - 1].geometry ;
            }
            
            var keywords = $.trim($("#searchText").val());
            keywords = keywords.replace("'",'');
            
            
            if(keywords.length){
            	query.where = "id_no  like '%" + keywords +  "%' or name like '%" + keywords + "%'";
            }
            
            query.returnGeometry = true;
            return query;
        };
        
        
        var searchingLock = false;
        
        
        var searchTask = function(){
            if(searchingLock){
                return;
            }
            
            //buildingLayer.clearSelection();
            //buildingLayer.selectFeatures(getQuery(),FeatureLayer.SELECTION_NEW,queryTaskExecuteCompleteHandler,queryTaskErrorHandler);
            buildingLayer.queryFeatures(getQuery(),queryTaskExecuteCompleteHandler,queryTaskErrorHandler);
            
            //锁定
            searchingLock = true;
            $("#searchText").addClass("txt-loading");
            
            function queryTaskExecuteCompleteHandler(queryResults){
               console.log(queryResults);
               //results = queryResults;
               results = queryResults.features;
               
               $("#search_result").html('').show();
               
               var itemAr = [];
               
		       $("#folderText").html('收起结果(' + results.length + ')');

               for(var i = 0; i < results.length; i++){
                   var item = $("<li>" +  results[i].attributes.name + "</li>");
                   
                   var bindFunc = function(data){
                        //console.log(data);
                        $(item).bind("click",function(){
                            //var textSymbol =  new TextSymbol(data.attributes.name).setColor(new Color([255,255,0])).setAlign(Font.ALIGN_START).setFont(new Font("12pt").setWeight(Font.WEIGHT_BOLD)) ;
                            var pointlocation = new Point(data.geometry);
                            var graphic1 = new Graphic(pointlocation,searchPicSym,data.attributes);
			    			map.setScale(500);
                            map.centerAt(pointlocation);
                       });
	                   
                   }
                   
                   bindFunc(results[i]);
                   
                   $("#search_result").append(item);
               }
               
               map.getLayer("panWork").clear();
               $("#resultWrap").show();
               
               searchingLock = false;
               $("#searchText").removeClass("txt-loading");
               //console.log(itemAr);
               
            }

            function queryTaskErrorHandler(queryError){
               searchingLock = false;
               $("#searchText").removeClass("txt-loading");
               $("#resultWrap").hide();
               
               //console.log("error", queryError.error.details);
            }
        };
        
        $("#searchText").bind("keyup",function(e){
            //console.log(e);
            if($.trim($(this).val()) =='' && 8 == e.keyCode){
                $("#resultWrap").hide();
				$("#search_result").html('');
                //buildingLayer.clearSelection();
            }
        
            if($.trim($(this).val()) !='' && 13 == e.keyCode){
                searchTask();
            }
        });
        
        $("#searchBtn").bind("click",function(){
            searchTask();
        });
        
        $("#folderText").bind("click",function(){
            var text = $(this).html();
		    if(/收起/.test(text)){
				text = text.replace('收起','展开');
				$(this).html(text);
				$("#search_result").slideUp();
		    }else if(/展开/.test(text)){
				text = text.replace('展开','收起');
				$(this).html(text);
				$("#search_result").slideDown();
		    }
        });
        
      });
   </script>
   <div id="mapWrap">
   		<div id="mapsearch">
	        <input type="text" name="keyword" id="searchText" class="" style="height: 28px;width: 300px;" value="" placeholder="请输入关键字(如户主名称，身份证号码)"/>
	        <input type="button" name="search" id="searchBtn" class="master_btn" value="查询"/>
	    </div>
	    <div id="resultWrap">
			<div id="folderText"></div>
			<ul id="search_result"></ul>
	    </div>
		<div id="toolbox">
		  <div id="legendDiv"></div>
		  <div class="measureTool">
		  	<h4>测量工具</h4>
	   	  	<div id="measurementDiv"></div>
	   	  </div>
	   	  <div class="panTool">
		      <h4>画笔:</h4>
		      <button data-sharp="Line">线</button>
		      <button data-sharp="Polyline">多段线</button>
		      <button data-sharp="Freehand Polyline">自由多段线</button>
		      <button data-sharp="Polygon">面</button>
		      <button data-sharp="Freehand Polygon">自由面</button>
		      
		      <button data-sharp="Circle">圆形</button>
		      <button data-sharp="Ellipse">椭圆行</button>
		      <button id="clearBtn">清空</button>
	      </div>
	   </div>
   	   <div id="mapDiv"></div>
   </div>
   <div id="buildingInfoDlg" title="详情" style="display:none;">
   		<div class="loading_bg">加载中...</div>
   		<div class="dlgContent"></div>
   </div>
{include file="common/my_footer.tpl"}
