<!DOCTYPE html>
<html dir="ltr">

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no" />
   <title>联系 ArcGIS API for JavaScript</title>
   <link rel="stylesheet" type="text/css" href="{base_url('js/arcgis_js_api/library/3.13/3.13/dijit/themes/claro/claro.css')}" />
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
         left: 320px;
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
      
      #templatePickerPane {
        width: 225px;
        overflow: hidden;
      }

      #panelHeader {
        background-color: #92A661;
        border-bottom: solid 1px #92A860;
        color: #FFF;
        font-size: 18px;
        height: 24px;
        line-height: 22px;
        margin: 0;
        overflow: hidden;
        padding: 10px 10px 10px 10px;
      }

      #map {
        margin-right: 5px;
        padding: 0;
      }

      .esriEditor .templatePicker {
        padding-bottom: 5px;
        padding-top: 5px;
        height: 500px;
        border-radius: 0px 0px 4px 4px;
        border: solid 1px #92A661;
      }

      .dj_ie .infowindow .window .top .right .user .content, .dj_ie .simpleInfoWindow .content {
        position: relative;
      }
      
      
      
   </style>
   <script>
      var map, toolbar,editToolbar, geomTask,undoManager;


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
        "esri/symbols/TextSymbol",
        "esri/renderers/SimpleRenderer",
        "esri/InfoTemplate",
        
        "esri/dijit/Search",
        "esri/dijit/editing/Editor",
        "esri/dijit/editing/TemplatePicker",
        "esri/dijit/AttributeInspector",
        
        "esri/tasks/query", 
        "esri/tasks/GeometryService",
        "esri/tasks/FindTask", "esri/tasks/FindParameters",
        
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
        Map, Edit,Draw, 
        
        
        GraphicsLayer,Graphic,
        Point, Polyline, Polygon,Circle,
        FeatureLayer,
        
        Color,
        SimpleMarkerSymbol, SimpleLineSymbol, SimpleFillSymbol,TextSymbol,
        SimpleRenderer,
        InfoTemplate ,
        
        Search,
        Editor,
        TemplatePicker,
        AttributeInspector,
        Query,
        GeometryService,
        FindTask,FindParameters,

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
        
        //specify the number of undo operations allowed using the maxOperations parameter
        undoManager = new esri.UndoManager({ maxOperations: 1 });

        //listen for the undo/redo button click events
        dojo.connect(dojo.byId('undo'), 'onclick', function(e) {
          undoManager.undo();
        });
        
        
        
        
        esriConfig.defaults.io.proxyUrl = "{site_url('myproxy')}";
        //This service is for development and testing purposes only. We recommend that you create your own geometry service for use within your applications
        esriConfig.defaults.geometryService = new GeometryService("http://yr140307-hosv/ArcGIS/rest/services/Geometry/GeometryServer");

        map = new Map("mapDiv");
        var map2D = new esri.layers.ArcGISDynamicMapServiceLayer("http://yr140307-hosv/ArcGIS/rest/services/mypipe/MapServer", { id: "2d"});
        var panWorkLayer = new GraphicsLayer({ id : "panWork" });
        map.addLayer(map2D);
        map.addLayer(panWorkLayer);
        
        map.on("layers-add-result", initEditing);
       
        {literal}
        var nullSymbol = new SimpleMarkerSymbol().setSize(0);
        var symbol = new SimpleMarkerSymbol(
          SimpleMarkerSymbol.STYLE_CIRCLE, 
          10, 
          new SimpleLineSymbol(
            SimpleLineSymbol.STYLE_NULL, 
            new Color([247, 34, 101, 0.9]), 
            1
          ),
          new Color([255, 0, 0, 0.5])
        );
        
        
        // 店铺 点
        var dianpuNodeLayer = new FeatureLayer("http://yr140307-hosv/ArcGIS/rest/services/ws/FeatureServer/0",{
          //infoTemplate: new InfoTemplate("店铺属性:", "${*}"),
          //infoTemplate: dianpuInfoTemplate,
          mode: FeatureLayer.MODE_ONDEMAND,
          opacity: 0.75,
          outFields: ["*"],
          id : "dianpu"
        });
        
        
        
        // 道路
        var roadLayer =  new FeatureLayer("http://yr140307-hosv/ArcGIS/rest/services/ws/FeatureServer/1",{
          //infoTemplate: new InfoTemplate("道路属性:", "${*}"),
          mode: FeatureLayer.MODE_ONDEMAND,
          outFields: ["*"],
          id : "road"
        });
        
        
        // 河流
        var riverLayer =  new FeatureLayer("http://yr140307-hosv/ArcGIS/rest/services/ws/FeatureServer/2",{
          //infoTemplate: new InfoTemplate("河流属性:", "${*}"),
          mode: FeatureLayer.MODE_ONDEMAND,
          outFields: ["*"],
          id : "river"
        });
        
        map.addLayers([dianpuNodeLayer,roadLayer,riverLayer]);
        
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
            showAttachments: true
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
        

        var search = new Search({
          map: map,
          enableSuggestions : true,
          autoSelect:false,
          sources: []
        },"search");
        
        
        search.on("load", function () {
            var sources = search.sources;
            sources.push({
               featureLayer: dianpuNodeLayer,
               enableLabel: true,
               name:"店铺",
               placeholder: "店铺名称",
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
               featureLayer: riverLayer,
               enableLabel: true,
               name:"河流水系",
               placeholder: "河流水系名称",
               searchFields: ["name"],
               displayField: "name",
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
            
            searchResult = response.results;
            for(var el in searchResult){
			     for(var j = 0 ; j < searchResult[el].length ; j++){
			        if(el == 0){
			             var graphic = new Graphic(searchResult[el][j].feature.geometry,symbol);
			        }else{
			             var graphic = new Graphic(searchResult[el][j].feature.geometry,lineSymbol);
			        }

                    map.graphics.add(graphic);
                }
			}
       
        });
        
        
        search.on("select-result",function(evt){
            console.log(evt);
        });
        
         {/literal}
     
        
        map.on("load", createToolbar);
        
        
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
          
          
          map.getLayer("panWork").on("click", function(evt) {
            event.stop(evt);
            console.log(evt.graphic);
            activateToolbar(evt.graphic);
          });
          
          //deactivate the toolbar when you click outside a graphic
          map.on("click", function(evt){
            //console.log(evt.mapPoint);
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
          
          map.getLayer("panWork").add(graphic);
          
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

<body class="claro">
    
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
   
   <div id="search"></div>
   <div id="mainWindow" data-dojo-type="dijit/layout/BorderContainer" data-dojo-props="design:'headline',gutters:false" style="width:100%; height:100%;">
      <div id="mapDiv" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region:'center'">
      </div>
	  <div data-dojo-type="dijit/layout/ContentPane" id="templatePickerPane" data-dojo-props="region:'left'">
	    <div id="panelHeader">
	      图例
	    </div>
	    <div style="padding:10px;" id="editorDiv">
	    </div>
	    
	  </div>
	</div>

</body>

</html>