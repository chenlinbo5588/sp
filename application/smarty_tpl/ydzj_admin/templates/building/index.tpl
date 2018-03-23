{include file="common/main_header.tpl"}
   <link rel="stylesheet" href="{resource_url('css/zq.css',true)}"/>
   {include file="common/fancybox.tpl"}
   {include file="common/arcgis_common.tpl"}
   <style type="text/css">
	.loca {
		position:static;
	}
   </style>
   <script type="text/javascript" src="{resource_url('js/ajaxfileupload/ajaxfileupload.js')}"></script>
   <script>
      var map,resizeTimer, scalebar,measurement, panWorkLayer, searchLayer ,toolbar,editToolbar, geomTask,undoManager;
      var markerInterval, markerDirection = true;
      var buildingInfoDlg;
      
      var villageJson = {$villageListJson};
      
      var showDetail = function(evt){
      		buildingInfoDlg.find(".dlgContent").html('');
			buildingInfoDlg.find(".loading_bg").show();
			buildingInfoDlg.dialog('open');
			buildingInfoDlg.find(".dlgContent").load('{admin_site_url('building/detail?hid=')}' + evt.graphic.attributes.hid,function(){
				buildingInfoDlg.find(".loading_bg").hide();
			});
		}
      
      $(function(){
      		buildingInfoDlg = $("#buildingInfoDlg" ).dialog({
		        autoOpen: false,
		        width: '80%',
		        modal: false,
		        open:function(){
		        	
		        }
		    });
		    
		    $('.fancybox').fancybox();
      });

      require([
        "esri/map","esri/InfoTemplate","esri/dijit/Scalebar","esri/dijit/Legend","esri/toolbars/edit","esri/toolbars/draw","esri/Color",
        "esri/dijit/editing/Editor","esri/geometry/Point","esri/geometry/Polyline","esri/geometry/Polygon","esri/geometry/Circle",
        "esri/symbols/SimpleMarkerSymbol","esri/symbols/SimpleLineSymbol","esri/symbols/SimpleFillSymbol",
        "esri/symbols/TextSymbol","esri/symbols/Font", "esri/symbols/PictureMarkerSymbol",
 
        "esri/layers/GraphicsLayer","esri/graphic", "esri/layers/LabelClass",
        "esri/layers/FeatureLayer","esri/InfoTemplate","esri/dijit/InfoWindow","esri/dijit/PopupTemplate",
        "esri/tasks/query",
        "esri/tasks/StatisticDefinition",
        "esri/renderers/SimpleRenderer","esri/renderers/UniqueValueRenderer",
        
        "esri/SpatialReference",
        "esri/tasks/GeometryService",
        
        "dojo/_base/event",
        "dojo/_base/array",
        "dojo/keys",
        "dojo/dom", 
        "dojo/dom-construct",
        "dojo/dom-style", 
        "dojo/parser", 
        "dijit/registry",
        "esri/tasks/GeometryService",
        "esri/dijit/Measurement",
        

        "dijit/layout/BorderContainer", 
        "dijit/layout/ContentPane", 
        "dojo/domReady!"
      ], function(
        Map,InfoTemplate,Scalebar,Legend, Edit,Draw, Color,
        Editor,Point, Polyline, Polygon,Circle,
        SimpleMarkerSymbol, SimpleLineSymbol, SimpleFillSymbol,
        TextSymbol,Font, PictureMarkerSymbol,
        GraphicsLayer,Graphic,LabelClass,
        FeatureLayer,InfoTemplate ,InfoWindow,PopupTemplate,
        Query,
        StatisticDefinition,
        SimpleRenderer,UniqueValueRenderer,
        SpatialReference,
        GeometryService,
        event, 
        arrayUtils,
        keys,
        dom, 
        domConstruct,
        domStyle,
        parser, 
        registry,
        GeometryService,
        Measurement
      ) {
        parser.parse();
        
        //specify the number of undo operations allowed using the maxOperations parameter
        //undoManager = new esri.UndoManager({ maxOperations: 1 });
  
        esriConfig.defaults.io.proxyUrl = "{base_url('proxy.php')}";
        esriConfig.defaults.io.alwaysUseProxy = false;
        
        var searchPicSym = new PictureMarkerSymbol('{resource_url('img/new.png')}', 32, 32);
        searchPicSym.setOffset(0,20);
        var hightLightPicSym = new PictureMarkerSymbol('{resource_url('img/new2.png')}', 32, 32); 
        
        var hightLightSym = new SimpleMarkerSymbol();
        hightLightSym.setColor(new Color([255,0,0]));
        var normalSym = new SimpleMarkerSymbol();
        normalSym.setColor(new Color([0,255,0])).setSize(10);
        
        //This service is for development and testing purposes only. We recommend that you create your own geometry service for use within your applications
        // ArcGIS 10.1+
        esriConfig.defaults.geometryService = new GeometryService("http://{config_item('arcgis_server_ip')}/arcgis/rest/services/Utilities/Geometry/GeometryServer");
        // ArcGIS 10.0
        //esriConfig.defaults.geometryService = new GeometryService("http://{config_item('arcgis_server_ip')}/arcgis/rest/services/Geometry/GeometryServer");
        
        map = new Map("mapDiv",{ zoom: 1, showLabels : true,scalebarUnit: "dual" });
        
        var layerZq = new esri.layers.{config_item('basemapType')}("{config_item('arcgis_server')}{$mapUrlConfig['基本要素']['底图']}");
        //var layerZq = new esri.layers.ArcGISDynamicMapServiceLayer("{config_item('arcgis_server')}{$mapUrlConfig['基本要素']['底图']}");
        
        //var allFeatureMap = new esri.layers.ArcGISDynamicMapServiceLayer("http://{config_item('arcgis_server_ip')}/arcgis/rest/services/zqwj/cljz/MapServer");
        var allFeatureMap = new esri.layers.ArcGISDynamicMapServiceLayer("{config_item('arcgis_server')}{$mapUrlConfig['基本要素']['存量建筑要素']}");
        
	    dojo.connect(map, 'onLoad', function(theMap) {
	      map.graphics.enableMouseEvents();
	      dojo.connect(dijit.byId('mapDiv'), 'resize', function() {
	      	if(resizeTimer){
	      		clearTimeout(resizeTimer);
	      	}
	        
	        resizeTimer = setTimeout(function() {
	          map.resize();
	          map.reposition();
	         }, 500);
	       });
	       
	       var legend = new Legend({
			    map: map,
			    arrangement:esri.dijit.Legend.ALIGN_LEFT,
			    layerInfos:[{ layer : allFeatureMap ,title:'存量建筑图例' }]
			  }, "legendDiv");
			  
		  	legend.startup();
		  
	        measurement = new Measurement({
			    map: map
			}, dojo.byId('measurementDiv'));
			
			measurement.startup();
			
			scalebar = new Scalebar({
	          map: map,
	          // "dual" displays both miles and kilometers
	          // "english" is the default, which displays miles
	          // use "metric" for kilometers
	          scalebarUnit: "dual"
	        });
  
	    });
	    
        panWorkLayer = new GraphicsLayer({ id : "panWork" });
        searchLayer = new GraphicsLayer({ id : 'search'});
       
        //map.on("layers-add-result", initEditing);
        //var nullSymbol = new SimpleMarkerSymbol().setSize(0);
        
        //存量建筑
        //var buildingUrl = "http://{config_item('arcgis_server_ip')}/ArcGIS/rest/services/zqwj/cljz/MapServer/1";
        var buildingUrl = "{config_item('arcgis_server')}{$mapUrlConfig['编辑要素']['标准建筑点']}";
        //农转用
        //var nzyUrl = "http://{config_item('arcgis_server_ip')}/ArcGIS/rest/services/zqwj/cljz/MapServer/2";
        //var jbntUrl = "http://{config_item('arcgis_server_ip')}/ArcGIS/rest/services/zqwj/cljz/MapServer/3";
        var villageUrl = "{config_item('arcgis_server')}{$mapUrlConfig['基本要素']['村界']}";
        
        {literal}
        var buildingLayer = new FeatureLayer(buildingUrl,{
          showLabels:true,
          mode: FeatureLayer.MODE_SELECTION,
          outFields: ['*'],
          id : "building"
        });
        
        var villageLayer = new FeatureLayer(villageUrl,{
          mode: FeatureLayer.MODE_SNAPSHOT,
          outFields: ['*'],
          id : "village"
        });
        
        
		var infoTemplate = new InfoTemplate();
          infoTemplate.setTitle("宗地 ${QLRMC}");
          infoTemplate.setContent("<table><tr><td>土地证号:</td><td>${TDZH}</td></tr>" + 
          "<tr><td>土地坐落:</td><td>${TDZL}</td></tr>" + 
          "<tr><td>实测面积:</td><td>${SCMJ}</td></tr>" + 
          "<tr><td>建筑面积:</td><td>${JZMJ}</td></tr>" + 
          "<tr><td>建筑物占地面积:</td><td>${JZWZDMJ}</td></tr>" + 
          "<tr><td>建筑物容积率:</td><td>${JZRJL}</td></tr>" + 
          "<tr><td>建筑密度:</td><td>${JZMD}</td></tr></table>");
		
         {/literal}
                                  
		zdLayer = new FeatureLayer("{config_item('arcgis_server')}{$mapUrlConfig['基本要素']['宗地']}",{
	        mode: FeatureLayer.MODE_ONDEMAND,
	        outFields: ['OBJECTID','QLRMC','TDZL','TDZH','SCMJ','JZMJ','JZWZDMJ','JZRJL','JZMD'],
	        infoTemplate: infoTemplate,
	        minScale:2000,
	        id : 'zd'
	    });
	    
        {literal}
     	//var layersNeedAdd = [layerZq,jbntLayer,nzyLayer,villageLayer,buildingLayer,panWorkLayer,searchLayer];
     	var layersNeedAdd = [layerZq,villageLayer,zdLayer,buildingLayer,panWorkLayer,searchLayer];
        map.addLayers(layersNeedAdd);
        {/literal}
        
        
        dojo.connect(buildingLayer, "onClick", showDetail);
        //dojo.connect(searchLayer, "onClick", showDetail);
        
        var getQuery = function(){
        	var query = new Query();
            //空间搜索 最后一次指定的范围
            if(panWorkLayer.graphics.length > 0){
                query.geometry = panWorkLayer.graphics[panWorkLayer.graphics.length - 1].geometry ;
            }
            
            var village = $("select[name=village]").val();
            var keywords = $.trim($("#searchText").val());
            
            keywords = keywords.replace("'",'');
            
            if(keywords.length){
            	query.where = "name like '%" + keywords + "%' or id_no like '%" + keywords + "%'";
            }
            
            if(village && village != ''){
            	if(query.where){
            		query.where = "village_id =" + village + " AND (" + query.where + ")";
            	}else{
            		query.where = "village_id =" + village + "";
            	}
            }
            
            if(!query.where){
            	query.where = "1=1";
            }
            
            
            query.returnGeometry = true;
            return query;
        };
        
        
        var searchingLock = false;
        var searchTask = function(){
            if(searchingLock){
                return;
            }
            
            buildingLayer.clearSelection();
            //buildingLayer.queryFeatures(query,queryTaskExecuteCompleteHandler,queryTaskErrorHandler);
            buildingLayer.selectFeatures(getQuery(),FeatureLayer.SELECTION_NEW,queryTaskExecuteCompleteHandler,queryTaskErrorHandler);
            
            //锁定
            searchingLock = true;
            $("#searchText").addClass("txt-loading");
            
            function queryTaskExecuteCompleteHandler(queryResults){
               //console.log("complete", queryResults);
               //searchLayer.clear();
               //results = queryResults.features;
               
               results = queryResults;
               $("#search_result").html('').show();
               var itemAr = [];
               
	       	   $("#folderText").html('收起结果(' + results.length + ')');

               for(var i = 0; i < results.length; i++){
               	   /*
                   var graphic = new Graphic(results[i].geometry,searchPicSym, results[i].attributes);
                   if(typeof(results[i].attributes.bh) == "undefined"){
                        //console.log(results[i].attributes);
                        continue;
                   }
                   */
                   
                   var item ;
                   
                   if(typeof(villageJson[results[i].attributes.village_id]) != 'undefined'){
                   		item = $("<li>【" + villageJson[results[i].attributes.village_id].xzqmc + "】" +  results[i].attributes.name +  "</li>");
                   }else{
                   		item = $("<li>" +  results[i].attributes.name + "</li>");
                   }
                   
                   var bindFunc = function(data){
                        //console.log(data);
                        $(item).bind("click",function(){
                            //var textSymbol =  new TextSymbol(data.attributes.name).setColor(new Color([255,255,0])).setAlign(Font.ALIGN_START).setFont(new Font("12pt").setWeight(Font.WEIGHT_BOLD)) ;
                            var pointlocation = new Point(data.geometry);
                            var graphic1 = new Graphic(pointlocation,searchPicSym,data.attributes);
                            searchLayer.add(graphic1);
                            
                            if(markerInterval){
                            	searchPicSym.setOffset(0,20);
                            	clearInterval(markerInterval);
                            }
                            
                            markerInterval = setInterval(function(){
                            	if(30 == searchPicSym.yoffset){
                            		markerDirection = !markerDirection;
                            	}else if(20 == searchPicSym.yoffset){
                            		markerDirection = true;
                            	}
                            	
                            	if(markerDirection){
                            		searchPicSym.setOffset(0,searchPicSym.yoffset + 1);
                            	}else{
                            		searchPicSym.setOffset(0,searchPicSym.yoffset - 1);
                            	}
                            	
                            	graphic1.setSymbol(searchPicSym);
                            },30);
                            
                            
			    			map.setScale(500);
                            map.centerAt(pointlocation);
                       });
	                   
                   }
                   
                   bindFunc(results[i]);
                   
                   $("#search_result").append(item);
                   //searchLayer.add(graphic);
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
                $("#statictsHTML").hide();
                searchLayer.clear();
                buildingLayer.clearSelection();
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

        
        $("#tjBtn").bind("click",function(){
            tjTask();
        });
        
        //画笔
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
        
      });
   </script>

   <div id="toolbox">
   	  <div id="legendDiv"></div>
   	  <h4>测量工具:</h4>
   	  <div id="measurementDiv"></div>
   	  <div class="panTool">
	      <h4>画笔:</h4>
	      {*
	      <button data-sharp="Point">点</button>
	      <button data-sharp="Multi Point">多点</button>
	      *}
	      <button data-sharp="Line">线</button>
	      <button data-sharp="Polyline">多段线</button>
	      <button data-sharp="Freehand Polyline">自由多段线</button>
	      <button data-sharp="Polygon">面</button>
	      <button data-sharp="Freehand Polygon">自由面</button>
	      {*
	      <!--The Arrow,Triangle,Circle and Ellipse types all draw with the polygon symbol-->
	      <button data-sharp="Arrow">箭头</button>
	      <button data-sharp="Triangle">三角形</button>
	      *}
	      <button data-sharp="Circle">圆形</button>
	      <button data-sharp="Ellipse">椭圆行</button>
	
	      <button id="clearBtn">清空</button>
	    </div>
   </div>
   
   <div id="search">
        <select name="village" style="height:33px;">
            <option value="">全部</option>
            {foreach from=$villageList item=item}
	    	<option value="{$item['id']}">{$item['xzqmc']|escape}</option>
	    	{/foreach}
        </select>
        <input type="text" name="keyword" id="searchText" class="" style="height: 28px;width: 300px;" value="" placeholder="请输入关键字(户主名称，身份证号码)"/>
        <input type="button" name="search" id="searchBtn" class="msbtn" value="查询"/>
   </div>
   <div id="resultWrap">
	<div id="folderText">收起</div>
	<ul id="search_result"></ul>
   </div>
   <div id="mapDiv"></div>
   <div id="buildingInfoDlg" title="详情" style="display:none;">
   		<div class="loading_bg">加载中...</div>
   		<div class="dlgContent"></div>
   </div>
{include file="common/main_footer.tpl"}