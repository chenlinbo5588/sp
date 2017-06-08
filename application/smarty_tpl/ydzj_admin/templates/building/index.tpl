{include file="common/main_header.tpl"}
   <link rel="stylesheet" href="{resource_url('css/zq.css')}"/>
   {include file="common/fancybox.tpl"}
   {include file="common/arcgis_common.tpl"}
   <style type="text/css">
	.loca {
		position:static;
	}
   </style>
   <script type="text/javascript" src="{resource_url('js/ajaxfileupload/ajaxfileupload.js')}"></script>
   <script>
      var map, scalebar,measurement, panWorkLayer, searchLayer ,toolbar,editToolbar, geomTask,undoManager;
      var buildingInfoDlg;
      
      $(function(){
      		buildingInfoDlg = $("#buildingInfoDlg" ).dialog({
		        autoOpen: false,
		        width: '98%',
		        modal: false,
		        open:function(){
		        	
		        }
		    });
      });
    

      require([
        "esri/map","esri/dijit/Scalebar","esri/dijit/Legend","esri/toolbars/edit","esri/toolbars/draw","esri/Color",
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
        Map,Scalebar,Legend, Edit,Draw, Color,
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
        
        var resizeTimer;
        map = new Map("mapDiv",{ zoom: 1, showLabels : true,scalebarUnit: "dual" });
        var layerZq = new esri.layers.ArcGISDynamicMapServiceLayer("http://{config_item('arcgis_server_ip')}/arcgis/rest/services/basemapzq/MapServer");
        //var layerZq = new esri.layers.ArcGISTiledMapServiceLayer("http://{config_item('arcgis_server_ip')}/arcgis/rest/services/basemapzq/MapServer");
        var allFeatureMap = new esri.layers.ArcGISDynamicMapServiceLayer("http://{config_item('arcgis_server_ip')}/arcgis/rest/services/zqwj/cljz/MapServer");

	    dojo.connect(map, 'onLoad', function(theMap) {
	      map.graphics.enableMouseEvents();
	      dojo.connect(dijit.byId('mapDiv'), 'resize', function() {
	        clearTimeout(resizeTimer);
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
       
        map.on("layers-add-result", initEditing);
        //var nullSymbol = new SimpleMarkerSymbol().setSize(0);
        
        //存量建筑
        var buildingUrl = "http://{config_item('arcgis_server_ip')}/ArcGIS/rest/services/zqwj/cljz/MapServer/1";
        //农转用
        var nzyUrl = "http://{config_item('arcgis_server_ip')}/ArcGIS/rest/services/zqwj/cljz/MapServer/2";
        var jbntUrl = "http://{config_item('arcgis_server_ip')}/ArcGIS/rest/services/zqwj/cljz/MapServer/3";
        var villageUrl = "http://{config_item('arcgis_server_ip')}/ArcGIS/rest/services/zqwj/cljz/MapServer/4";
        
        {literal}
        var buildingLayer = new FeatureLayer(buildingUrl,{
          showLabels:true,
          mode: FeatureLayer.MODE_SELECTION,
          outFields: ["OBJECTID","bh","name","village","illegal_de","wf_wjmj"],
          id : "building"
        });
        
        //buildingLayer.setRenderer(new SimpleRenderer(normalSym));
        
     	var nzyLayer = new FeatureLayer(nzyUrl,{
          showLabels:true,
          mode: FeatureLayer.MODE_SNAPSHOT,
          outFields: ["OBJECTID","XMMC"],
          id : "nzy"
        });
        var jbntLayer = new FeatureLayer(jbntUrl,{
          mode: FeatureLayer.MODE_SNAPSHOT,
          outFields: ["OBJECTID"],
          id : "jbnt"
        });
        var villageLayer = new FeatureLayer(villageUrl,{
          mode: FeatureLayer.MODE_SNAPSHOT,
          outFields: ["OBJECTID","XZQMC"],
          id : "village"
        });
        
     	
     	var layersNeedAdd = [layerZq,jbntLayer,nzyLayer,villageLayer,buildingLayer,panWorkLayer,searchLayer];
        map.addLayers(layersNeedAdd);
        
        function initEditing (event) {
          var featureLayerInfos = arrayUtils.map(event.layers, function (layer) {
            return {
              "featureLayer": layer.layer
            };
          });
          
          var settings = {
            map: map,
            layerInfos: featureLayerInfos,
            toolbarVisible: true,
            showAttachments: true,
            //enableUndoRedo:true,
            //undoManager:undoManager,
            createOptions: {
                polygonDrawTools: [ 
                  esri.dijit.editing.Editor.CREATE_TOOL_FREEHAND_POLYGON,
                  esri.dijit.editing.Editor.CREATE_TOOL_AUTOCOMPLETE
                ]
              },
            toolbarOptions: {
             reshapeVisible: true,
             cutVisible: true,
             mergeVisible: true
            }
          };
          var params = {
            settings: settings
          };
          var editorWidget = new Editor(params, 'editorDiv');
          editorWidget.startup();

          //snapping defaults to Cmd key in Mac & Ctrl in PC.
          //specify "snapKey" option only if you want a different key combination for snapping
          map.enableSnapping();
        }
        {/literal}
        var showDetail = function(evt){
        	buildingInfoDlg.find(".loading_bg").show();
        	buildingInfoDlg.dialog('open');
        	buildingInfoDlg.find(".dlgContent").load('{admin_site_url('building/edit?id=')}' + evt.graphic.attributes.OBJECTID,function(){
        		buildingInfoDlg.find(".loading_bg").hide();
        	});
        }
        
        dojo.connect(buildingLayer, "onClick", showDetail);
        dojo.connect(searchLayer, "onClick", showDetail);
        
        
        var getQuery = function(){
        	var query = new Query();
            //query.outFields = ["OBJECTID","bh","name","village"];
            
            //空间搜索 最后一次指定的范围
            if(panWorkLayer.graphics.length > 0){
                query.geometry = panWorkLayer.graphics[panWorkLayer.graphics.length - 1].geometry ;
            }
            
            var village = $("select[name=village]").val();
            var keywords = $.trim($("#searchText").val());
            
            keywords = keywords.replace("'",'');
            
            if(keywords.length){
            	query.where = "bh like '%" + keywords +  "%' or name like '%" + keywords + "%' or id_card like '%" + keywords + "%'";
            }
            
            if(village && village != ''){
            	if(query.where){
            		query.where = "village ='" + village + "' AND (" + query.where + ")";
            	}else{
            		query.where = "village ='" + village + "'";
            	}
            }
            
            if(!query.where){
            	query.where = '1=1';
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
               searchLayer.clear();
               //results = queryResults.features;
               
               results = queryResults;
               $("#search_result").html('').show();
               var itemAr = [];
               
	       if(results.length){
		   $("#folderText").html('收起结果(' + results.length + ')');
	       }

               for(var i = 0; i < results.length; i++){
               	   /*
                   var graphic = new Graphic(results[i].geometry,searchPicSym, results[i].attributes);
                   
                   if(typeof(results[i].attributes.bh) == "undefined"){
                        //console.log(results[i].attributes);
                        continue;
                   }
                   */
                   var item = $("<li><div>【" + results[i].attributes.bh + " 】" +  results[i].attributes.name + " " + results[i].attributes.village + "</div></li>");
                   
                   var bindFunc = function(data){
                        //console.log(data);
                        $(item).bind("click",function(){
                            //var textSymbol =  new TextSymbol(data.attributes.name).setColor(new Color([255,255,0])).setAlign(Font.ALIGN_START).setFont(new Font("12pt").setWeight(Font.WEIGHT_BOLD)) ;
                            var pointlocation = new Point(data.geometry);
                            var graphic1 = new Graphic(pointlocation,searchPicSym,data.attributes);
                            //searchLayer.add(graphic1);
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


        
        var tjTask = function(){
        
        	var countStatDef = new StatisticDefinition();
		    countStatDef.statisticType = "count";
		    countStatDef.onStatisticField = "bh"
		    countStatDef.outStatisticFieldName = "bhcount";
		    
        	var jzw_ydmjStatDef = new StatisticDefinition();
		    jzw_ydmjStatDef.statisticType = "sum";
		    jzw_ydmjStatDef.onStatisticField = "jzw_ydmj";
		    jzw_ydmjStatDef.outStatisticFieldName = "jzw_ydmj";
		    
		    var jzw_jzzdmjStatDef = new StatisticDefinition();
		    jzw_jzzdmjStatDef.statisticType = "sum";
		    jzw_jzzdmjStatDef.onStatisticField = "jzw_jzzdmj";
		    jzw_jzzdmjStatDef.outStatisticFieldName = "jzw_jzzdmj";
		    
		    var jzw_jzmjStatDef = new StatisticDefinition();
		    jzw_jzmjStatDef.statisticType = "sum";
		    jzw_jzmjStatDef.onStatisticField = "jzw_jzmj";
		    jzw_jzmjStatDef.outStatisticFieldName = "jzw_jzmj";
		    
		    var sp_ydmjStatDef = new StatisticDefinition();
		    sp_ydmjStatDef.statisticType = "sum";
		    sp_ydmjStatDef.onStatisticField = "sp_ydmj";
		    sp_ydmjStatDef.outStatisticFieldName = "sp_ydmj";
		    
		    var sp_jzmjStatDef = new StatisticDefinition();
		    sp_jzmjStatDef.statisticType = "sum";
		    sp_jzmjStatDef.onStatisticField = "sp_jzmj";
		    sp_jzmjStatDef.outStatisticFieldName = "sp_jzmj";
		    
		    var wfmjStatDef = new StatisticDefinition();
		    wfmjStatDef.statisticType = "sum";
		    wfmjStatDef.onStatisticField = "wf_wjmj";
		    wfmjStatDef.outStatisticFieldName = "wf_wjmj";
		    
		    var queryParams = getQuery();
		    queryParams.outStatistics = [countStatDef,jzw_ydmjStatDef,jzw_jzzdmjStatDef,jzw_jzmjStatDef,sp_ydmjStatDef,sp_jzmjStatDef,wfmjStatDef];
    		buildingLayer.queryFeatures(queryParams, function(results){
    			//console.log(results);
    			var stats = results.features[0].attributes;
    			
    			$("#countResult").html(stats.bhcount);
    			$("#ydmjResult").html(stats.jzw_jzmj);
    			$("#jzzdmjResult").html(stats.jzw_jzzdmj);
    			$("#jzmjResult").html(stats.jzmj);
    			$("#spydmjResult").html(stats.sp_ydmj);
    			$("#spjzmjResult").html(stats.sp_jzmj);
    			$("#wfjzmjResult").html(stats.wf_wjmj);
    			$("#statictsHTML").show();
    			
    			searchTask();
    		});
        };
        
        
        $("#tjBtn").bind("click",function(){
            tjTask();
        });
        
        //画笔
        $("#panbox button").bind("click",activateTool);
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

   <div id="panbox">
   	  <div id="measurementDiv"></div>
   	  <div id="legendDiv"></div>
      <span>画笔:</span>
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
   
   <div id="search">
        <select name="village" style="height:33px;">
            <option value="">全部</option>
	    <option value="陈家村">陈家村</option>
	    <option value="叶家村">叶家村</option>
	    {*
            <option value="巴里村">巴里村</option>
            <option value="柴家村">柴家村</option>
            <option value="东埠头村">东埠头村</option>
            <option value="古窑浦村">古窑浦村</option>
            <option value="洪魏村">洪魏村</option>
            <option value="厉家村">厉家村</option>
            <option value="裘家村">裘家村</option>
            <option value="戎家村">戎家村</option>
            <option value="五姓点村">五姓点村</option>
            <option value="长溪村">长溪村</option>
	    *}
        </select>
        <input type="text" name="keyword" id="searchText" class="" style="height: 28px;width: 300px;" value="" placeholder="请输入关键字(如编号，户主名称，身份证号码)"/>
        <input type="button" name="search" id="searchBtn" class="msbtn" value="查询"/>
        <input type="button" name="tj" id="tjBtn" class="msbtn btndisabled" value="统计"/>
   </div>
   <div id="resultWrap">
	<div id="folderText"></div>
	<ul id="search_result"></ul>
   </div>
   <div id="statictsHTML">
      <div><strong>调查存量建筑个数: </strong><span class="stats" id="countResult"></span></div>
      <div><strong>建筑物用地面积合计(M<sup>2</sup>): </strong><span class="stats" id="ydmjResult"></span></div>
      <div><strong>建筑物占地面积合计(M<sup>2</sup>): </strong><span class="stats" id="jzzdmjResult"></span></div>
      <div><strong>建筑物建筑面积合计(M<sup>2</sup>): </strong><span class="stats" id="jzmjResult"></span></div>
      <div><strong>审批用地面积合计(M<sup>2</sup>): </strong><span class="stats" id="spydmjResult"></span></div>
      <div><strong>审批建筑面积合计(M<sup>2</sup>): </strong><span class="stats" id="spjzmjResult"></span></div>
      <div><strong>违法面积合计(M<sup>2</sup>): </strong><span class="stats" id="wfjzmjResult"></span></div>
    </div>  
   <div id="mapDiv"></div>
   {*
   <div id="villageDiv">
   		<ul class="clearfix">
			<li><label><input type="radio" value="巴里村" name="village"/>巴里村</label></li>
			<li><label><input type="radio" value="柴家村" name="village"/>柴家村</label></li>
			<li><label><input type="radio" value="陈家村" name="village"/>陈家村</label></li>
			<li><label><input type="radio" value="东埠头村" name="village"/>东埠头村</label></li>
			<li><label><input type="radio" value="古窑浦村" name="village"/>古窑浦村</label></li>
			<li><label><input type="radio" value="洪魏村" name="village"/>洪魏村</label></li>
			<li><label><input type="radio" value="厉家村" name="village"/>厉家村</label></li>
			<li><label><input type="radio" value="裘家村" name="village"/>裘家村</label></li>
			<li><label><input type="radio" value="戎家村" name="village"/>戎家村</label></li>
			<li><label><input type="radio" value="五姓点村" name="village"/>五姓点村</label></li>
			<li><label><input type="radio" value="叶家村" name="village"/>叶家村</label></li>
			<li><label><input type="radio" value="长溪村" name="village"/>长溪村</label></li>
		</ul>
   </div>
   *}
   <div id="buildingInfoDlg" title="详情" style="display:none;">
   		<div class="loading_bg">加载中...</div>
   		<div class="dlgContent"></div>
   </div>
{include file="common/main_footer.tpl"}