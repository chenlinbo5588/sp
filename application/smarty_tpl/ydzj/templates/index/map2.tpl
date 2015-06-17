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
      
      #footer {
        background-color: white;
        color: #808080;
        font-size: 10pt; 
        height: 81px;
        padding: 0 8px 8px 8px;
        text-align: center; 
      }
      .templatePicker {
        border: 1px solid #808080;
        border-radius: 0;
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
        "esri/symbols/TextSymbol",
        "esri/renderers/SimpleRenderer",
        "esri/InfoTemplate",
        
        "esri/dijit/Search",
        "esri/dijit/editing/Editor",
        "esri/dijit/editing/TemplatePicker",
        "esri/dijit/AttributeInspector",
        
        "esri/tasks/query", 
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

        map = new Map("mapDiv");
        var map2D = new esri.layers.ArcGISDynamicMapServiceLayer("http://yr140307-hosv/ArcGIS/rest/services/mypipe/MapServer", { id: "2d"});
        var panWorkLayer = new GraphicsLayer({ id : "panWork" });
        
        map.infoWindow.resize(400,300);
        
        map.addLayer(map2D);
        map.addLayer(panWorkLayer);

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
        /*
        //点属性
        var pipeNodeLayer = new FeatureLayer("http://yr140307-hosv/ArcGIS/rest/services/mypipe/MapServer/0",{
          //infoTemplate: new InfoTemplate("取水点属性:", "${*}"),
          outFields: ["*"],
          id : "pipenode"
        });
        
        
        //pipeNodeLayer.setSelectionSymbol(symbol); 
        //make unselected features invisible
        
        //pipeNodeLayer.setRenderer(new SimpleRenderer(nullSymbol));
        pipeNodeLayer.setRenderer(new SimpleRenderer(symbol));
        
        
        
        var dianpuInfoTemplate = new InfoTemplate();
        var dianpuDetail = function(){
        
            var html = '<label>名称:<input type="text" name="title" value="${title}"/>';
            html += '<input type="button" name="submit" value="保存"/>';
            return html;
        };
        
        dianpuInfoTemplate.setTitle("店铺${title}");
        dianpuInfoTemplate.setContent(dianpuDetail());
        */
        
        // 店铺
        var dianpuNodeLayer = new FeatureLayer("http://yr140307-hosv/ArcGIS/rest/services/mydianpu/FeatureServer/0",{
          infoTemplate: new InfoTemplate("店铺属性:", "${*}"),
          //infoTemplate: dianpuInfoTemplate,
          opacity: 0.75,
          outFields: ["*"],
          id : "dianpu"
        });
        
        /*
        //dianpuNodeLayer.setRenderer(new SimpleRenderer(nullSymbol));
        dianpuNodeLayer.setRenderer(new SimpleRenderer(new SimpleMarkerSymbol(
          SimpleMarkerSymbol.STYLE_CIRCLE, 
          12, 
          new SimpleLineSymbol(
            SimpleLineSymbol.STYLE_SOLID, 
            new Color([0, 0, 0, 0.9]), 
            1
          ),
          //fill color
          new Color([56, 128, 128])
        )));
        
        
        dianpuNodeLayer.setSelectionSymbol(
          new SimpleMarkerSymbol().setSize(12).setOutline(new SimpleLineSymbol(
            SimpleLineSymbol.STYLE_DOT, 
            new Color([0, 0, 255, 0.9]), 
            1
          )).setColor(new Color([0, 56, 12]))
        );
       */
        
        
        
        // 水系
        var suixiLayer =  new FeatureLayer("http://yr140307-hosv/ArcGIS/rest/services/mypipe/MapServer/1",{
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
        map.addLayer(dianpuNodeLayer);
        map.addLayer(suixiLayer);

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
        </form>
   </script>
   
   <div id="search"></div>
   <div id="mapDiv"></div>
    <div id="footer" class="roundedCorners" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region:'bottom'">
      <div id="editorDiv"></div>
    </div>

</body>

</html>