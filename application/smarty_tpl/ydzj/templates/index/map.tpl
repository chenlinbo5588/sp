<!DOCTYPE html>
<html dir="ltr">

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no" />
   <title>联系 ArcGIS API for JavaScript</title>
   <link rel="stylesheet" type="text/css" href="{base_url('js/arcgis_js_api/library/3.13/3.13/dijit/themes/tundra/tundra.css')}" />
   <link rel="stylesheet" type="text/css" href="{base_url('js/arcgis_js_api/library/3.13/3.13/esri/css/esri.css')}" />
   <script type="text/javascript" src="{base_url('js/arcgis_js_api/library/3.13/3.13/init.js')}"></script>
   <script type="text/javascript" src="{base_url('js/jquery-1.11.3.min.js')}"></script>
   <style>
      html,
      body,
      #mapDiv {
         height: 100%;
         width: 100%;
         margin: 0;
         padding: 0;
      }
      #search {
         display: none;
         position: absolute;
         z-index: 2;
         top: 20px;
         left: 74px;
      }
      
      #search_result {
          background:#fff;
          border:1px solid black;
          height:100%;
          width:200px;
          display: none;
		  position: absolute;
		  z-index: 2;
		  top: 50px;
		  left: 74px;
      }
      
      
      #header {
        position:absolute;
        right:0px;
        width:150px;
        background:#fff;
        z-index:100;
      }
      
   </style>
   <script>
      var map, toolbar,editToolbar, geomTask;

      require([
        "esri/map", 
        "esri/toolbars/edit",
        "esri/toolbars/draw",

        "esri/layers/GraphicsLayer","esri/graphic", 
        
        "esri/geometry/Point",
        "esri/geometry/Polyline",
        "esri/geometry/Polygon",
        "esri/geometry/Circle",
        
        
        "esri/layers/FeatureLayer",
        
        "esri/Color",
        "esri/symbols/SimpleMarkerSymbol",
        "esri/symbols/SimpleLineSymbol",
        "esri/symbols/SimpleFillSymbol",
        "esri/renderers/SimpleRenderer",
        "esri/InfoTemplate",
        
        "esri/dijit/Search",
        "esri/tasks/query", 
        "esri/tasks/FindTask", "esri/tasks/FindParameters",
        
        "dojo/_base/event",
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
        Map, Edit,Draw, 
        GraphicsLayer,Graphic,
        
        Point, Polyline, Polygon,Circle,
        FeatureLayer,
        Color,
        SimpleMarkerSymbol, SimpleLineSymbol, SimpleFillSymbol,
        SimpleRenderer,
        InfoTemplate ,
        Search,
        Query,
        FindTask,FindParameters,

        event, dom, domStyle,Menu,
        parser, registry
      ) {
        //parser.parse();

        map = new Map("mapDiv");
        var map2D = new esri.layers.ArcGISTiledMapServiceLayer("http://yr140307-hosv/ArcGIS/rest/services/2d/MapServer", { id: "2d"});
        var searchLayer = new GraphicsLayer({ id : "seachLayer" });
        var pipeNodeLayer = new GraphicsLayer({ id: "pipeNodeLayer" });
         
        map.addLayer(map2D);
        map.addLayer(searchLayer);
        map.addLayer(pipeNodeLayer);
        
        {literal}
        
        //点属性
        var featureLayer = new FeatureLayer("http://yr140307-hosv/ArcGIS/rest/services/2d/MapServer/1",{
          infoTemplate: new InfoTemplate("单位一级点属性:", "${*}"),
          outFields: ["*"],
          id : "dx2jd"
        });
        
        var symbol = new SimpleMarkerSymbol(
          SimpleMarkerSymbol.STYLE_CIRCLE, 
          12, 
          new SimpleLineSymbol(
            SimpleLineSymbol.STYLE_NULL, 
            new Color([247, 34, 101, 0.9]), 
            1
          ),
          new Color([207, 34, 171, 0.5])
        );
        featureLayer.setSelectionSymbol(symbol); 
        
        //make unselected features invisible
        var nullSymbol = new SimpleMarkerSymbol().setSize(0);
        featureLayer.setRenderer(new SimpleRenderer(nullSymbol));
        
        
        var suixiLayer =  new FeatureLayer("http://yr140307-hosv/ArcGIS/rest/services/2d/MapServer/17",{
          infoTemplate: new InfoTemplate("水系属性:", "${*}"),
          outFields: ["*"],
          id : "suixi"
        });
        
        
        var lineSymbol = new SimpleLineSymbol(
            SimpleLineSymbol.STYLE_SOLID,
            new Color([51,51,154,0.8]),
            3
        );
        suixiLayer.setRenderer(lineSymbol);
        
        
        // 道路层
        var template = new InfoTemplate();
        template.setTitle("<b>${PName}</b>");
        template.setContent(getTpl());

        function getTpl(){
            return $("#pipeEdit").html();
            //return "${FID} ${PName}";
        }
        
        var daoluFeatureLayer = new FeatureLayer("http://yr140307-hosv/ArcGIS/rest/services/2d/MapServer/22",{
          infoTemplate:template,
          outFields: ["*"],
          id : "daolu"
        });
        
        {/literal}
        // selection symbol used to draw the selected census block points within the buffer polygon
        
        
        
        
        map.addLayer(featureLayer);
        
        var search = new Search({
          map: map,
          enableSuggestions : true,
          autoSelect:false,
          sources: []
        },"search");
        
        {literal}
        search.on("load", function () {

            var sources = search.sources;
            sources.push({
               featureLayer: featureLayer,
               enableLabel: true,
               name:"单位商铺",
               placeholder: "单位名称",
               searchFields: ["title"],
               displayField: "title",
               maxResults: 10,
               maxSuggestions: 6,
               exactMatch: false,
               outFields: ["*"],
               //Create an InfoTemplate and include three fields
               infoTemplate: null,
               enableSuggestions: true,
               minCharacters: 0

            });
            
            sources.push({
               featureLayer: suixiLayer,
               enableLabel: true,
               name:"河流水系",
               placeholder: "河流水系名称",
               searchFields: ["PName"],
               displayField: "PName",
               maxResults: 10,
               maxSuggestions: 6,
               exactMatch: false,
               outFields: ["*"],
               //Create an InfoTemplate and include three fields
               infoTemplate: null,
               enableSuggestions: true,
               minCharacters: 0

            });
            
            
            //Set the sources above to the search widget
            search.set("sources", sources);
         });
        
         
        search.startup();
        
        search.on("search-results",function(response){
            console.log("找到" + response.numResults + "个记录");
            console.log(response);
            map.graphics.clear();
            
            
            //注意下标0 表示第一个datasource 的返回结果
            var searchResult = response.results;
            
            
            for(var el in searchResult){
			     for(var j = 0 ; j < searchResult[el].length ; j++){
			        if(el == 0){
			             var graphic = new Graphic(searchResult[el][j].feature.geometry,symbol);
			        }else{
			             var graphic = new Graphic(searchResult[el][j].feature.geometry,lineSymbol);
			        }

                    graphic.setInfoTemplate(new InfoTemplate("属性", "${FID}"));
                    graphic.setAttributes(searchResult[el][j].feature.attributes);
                    map.graphics.add(graphic);
                }
			}
            
        });
        
        
        search.on("select-result",function(evt){
            console.log(evt);
        });
        
         {/literal}
        
        var sls = new SimpleLineSymbol(
		    SimpleLineSymbol.STYLE_SOLID,
		    new Color([255,0,0]),
		    3
		  );
        
        daoluFeatureLayer.setRenderer(new SimpleRenderer(sls));
        map.addLayer(daoluFeatureLayer);
        
        
        map.on("load", createToolbar);
        
        
        
        /**
         * 范围搜索
         */
        var circleSymb = new SimpleFillSymbol(
          SimpleFillSymbol.STYLE_NULL,
          new SimpleLineSymbol(
            SimpleLineSymbol.STYLE_SHORTDASHDOTDOT,
            new Color([105, 105, 105]),
            2
          ), new Color([255, 255, 0, 0.25])
        );
        var circle;
         /*
         map.on("click", function(evt){
          circle = new Circle({
            center: evt.mapPoint,
            radius: 100
            
          });
          map.graphics.clear();
          map.infoWindow.hide();
          var graphic = new Graphic(circle, circleSymb);
          map.graphics.add(graphic);

          var query = new Query();
          query.geometry = circle.getExtent();
          //use a fast bounding box query. will only go to the server if bounding box is outside of the visible map
          featureLayer.queryFeatures(query, selectInBuffer);
        });
*/
        function selectInBuffer(response){
          var feature;
          var features = response.features;
          var inBuffer = [];
          //filter out features that are not actually in buffer, since we got all points in the buffer's bounding box
          for (var i = 0; i < features.length; i++) {
            feature = features[i];
            if(circle.contains(feature.geometry)){
              inBuffer.push(feature.attributes[featureLayer.objectIdField]);
            }
          }
          var query = new Query();
          query.objectIds = inBuffer;
          //use a fast objectIds selection query (should not need to go to the server)
          featureLayer.selectFeatures(query, FeatureLayer.SELECTION_NEW, function(results){
            var r = "";
            r = "<b>The total Census Block population within the buffer is <i>" + results.length + "</i>.</b>";
            console.log(results);
            console.log("找到" + results.length + "个结果");
          });
        }
        
        
         
        //画笔
        $("#header button").bind("click",activateTool);
        
        function activateTool(event) {
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
          
          
          map.getLayer("pipeNodeLayer").on("click", function(evt) {
            event.stop(evt);
            console.log(evt.graphic);
            activateToolbar(evt.graphic);
          });
          
          //deactivate the toolbar when you click outside a graphic
          map.on("click", function(evt){
            console.log(evt.mapPoint);
            editToolbar.deactivate();
          });
          
        }

        function addToMap(evt) {
          console.log(evt);
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
          
          map.getLayer("pipeNodeLayer").add(graphic);
          
          //map.graphics.add(graphic);
        }
        
        function activateToolbar(graphic) {
          var tool = 0;
          
          tool = Edit.MOVE | Edit.EDIT_VERTICES | Edit.SCALE | Edit.ROTATE; 
          
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
</head>

<body>
    
   <div id="header">
      <span>画笔:<br /></span>
      <button data-sharp="Point">点</button>
      <button data-sharp="Multi Point">Multi Point</button>
      <button data-sharp="Line">Line</button>
      <button data-sharp="Polyline">Polyline</button>
      <button data-sharp="Polygon">Polygon</button>
      <button data-sharp="Freehand Polyline">Freehand Polyline</button>
      <button data-sharp="Freehand Polygon">Freehand Polygon</button>
      <!--The Arrow,Triangle,Circle and Ellipse types all draw with the polygon symbol-->
      <button data-sharp="Arrow">Arrow</button>
      <button data-sharp="Triangle">Triangle</button>
      <button data-sharp="Circle">Circle</button>
      <button data-sharp="Ellipse">Ellipse</button>
    </div>
   
   <script type="infoTemplate" id="pipeEdit">
        <form name="editPipeLine" action="{site_url('pipe/edit')}" method="post">
            {literal}
            <input type="hidden" name="fid" value="${FID}"/>
            
            <label>管线名称<input type="text" name="pipeName" value="${PName}"/></label>{/literal}
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
            <label>年份<input type="text" name="pipeYear"/></label>
        </form>
   </script>
   
   <div id="search"></div>
   <div id="mapDiv"></div>

</body>

</html>