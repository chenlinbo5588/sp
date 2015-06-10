<!DOCTYPE html>
<html dir="ltr">

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no" />
   <title>联系 ArcGIS API for JavaScript</title>
   <link rel="stylesheet" type="text/css" href="{base_url('js/arcgis_js_api/library/3.13/3.13/dijit/themes/tundra/tundra.css')}" />
   <link rel="stylesheet" type="text/css" href="{base_url('js/arcgis_js_api/library/3.13/3.13/esri/css/esri.css')}" />
   <script type="text/javascript" src="{base_url('js/arcgis_js_api/library/3.13/3.13/init.js')}"></script>
   
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
         display: block;
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
          display: block;
		  position: absolute;
		  z-index: 2;
		  top: 50px;
		  left: 74px;
      }
   </style>
   <script>
      var map,tb;
      
      function showSearchError(){
      
      }
      
      require([
        "esri/map",
        "esri/symbols/SimpleFillSymbol", 
        "esri/symbols/SimpleMarkerSymbol", "esri/symbols/SimpleLineSymbol",
        "esri/symbols/PictureFillSymbol", "esri/symbols/CartographicLineSymbol",
        "esri/symbols/TextSymbol",  "esri/symbols/Font",
        "esri/renderers/SimpleRenderer",
        "esri/InfoTemplate",
        "esri/layers/GraphicsLayer","esri/graphic", 
        "dojo/dom", "dojo/on",
        "esri/tasks/query", "esri/tasks/QueryTask",
        "esri/tasks/FindTask", "esri/tasks/FindParameters",
        "dojo/domReady!"

      ], function (
        Map,
        SimpleFillSymbol,
        SimpleMarkerSymbol, SimpleLineSymbol,
        PictureFillSymbol, CartographicLineSymbol, 
        TextSymbol,Font,
        SimpleRenderer ,
        InfoTemplate ,
        GraphicsLayer,Graphic,
        dom, on,
        query,QueryTask,
        FindTask,FindParameters ) {
         map = new Map("mapDiv");
         var map2D = new esri.layers.ArcGISTiledMapServiceLayer("http://yr140307-hosv/ArcGIS/rest/services/2d/MapServer", { id: "2d"});
         var searchLayer = new GraphicsLayer({ id : "seachLayer" });
         
         
         map.addLayer(map2D);
         map.addLayer(searchLayer);
         
         map2D.on("load" , function(){
            console.log("map2D loaded");
         });
         
         map.on("load", mapLoad);
         
         /*
         map.on("click", function(evt) {
		  map.infoWindow.setTitle("经纬度");
		  map.infoWindow.setContent("经度 : " + evt.mapPoint.y + "<br/>纬度: " + evt.mapPoint.x);
		  map.infoWindow.show(evt.screenPoint,map.getInfoWindowAnchor(evt.screenPoint));
		});
        */
         
         var showResults = function(obj){
            console.log(obj);
           
            var symbol = new SimpleFillSymbol();
            symbol.setColor(new dojo.Color([150, 150, 150, 0.5]));
            symbol.setOutline(new SimpleLineSymbol(
                esri.symbol.SimpleLineSymbol.STYLE_SOLID, 
                new dojo.Color([255,0,0, 0])
                )
            );
            
            {literal}
            var template = new InfoTemplate();
            template.setTitle("<b>${qAddress}</b>");
            template.setContent("hello");
            template.setContent(getTextContent);
            {/literal}
            
            function getTextContent(graphic){
		        return "<p>hahshahs</p>";
		    }
      
            map.getLayer("seachLayer").clear();
            
            for(var i = 0; i < obj.length; i++){
                console.log(i);
                var symbol = new SimpleMarkerSymbol({
				  "color": [255,0,0,64],
				  "size": 12,
				  "angle": 0,
				  "xoffset": 0,
				  "yoffset": 0,
				  "type": "esriSMS",
				  "style": "esriSMSCircle",
				  "outline": {
				    "color": [0,0,0,255],
				    "width": 1,
				    "type": "esriSLS",
				    "style": "esriSLSSolid"
				  }
				});
                
                var infoTemplate = new InfoTemplate();
                
                infoTemplate.setTitle(obj[i].value);
                infoTemplate.setContent("aaaa");
                
                var grapth = new Graphic(obj[i].feature.geometry, symbol);
                grapth.setInfoTemplate(infoTemplate);
                map.getLayer("seachLayer").add(grapth);
                //dojo.byId("result_list").append();
                
                
            }
         };
         
         var searchBtn = dojo.byId("searchBtn");
         
		 dojo.connect(searchBtn, "onclick", function() {
		    var find = new FindTask("http://yr140307-hosv/ArcGIS/rest/services/2d/MapServer");
	        var params = new FindParameters();
	         params.contains = true;
	         //params.layerDefinitions = [""];
	          
	         params.layerIds = [0];
	         params.returnGeometry = true;
	         params.searchFields = ["title"];
	         params.searchText = dojo.byId("keywords").value;
	         find.execute(params, showResults);
	         
		 });
	     
	     
	     function mapLoad(evt){
	        console.log("map loaded");
	        console.log(evt);
	     }
	        
         function addGraphic(evt) {
          //deactivate the toolbar and clear existing graphics 
          //tb.deactivate(); 
          //map.enableMapNavigation();

          // figure out which symbol to use
          var symbol;
          if ( evt.geometry.type === "point" || evt.geometry.type === "multipoint") {
            symbol = markerSymbol;
            
          } else if ( evt.geometry.type === "line" || evt.geometry.type === "polyline") {
            symbol = lineSymbol;
          }
          else {
            symbol = fillSymbol;
          }

          map.graphics.add(new Graphic(evt.geometry, symbol));
        }
	        
      });
      
      
      
   </script>
</head>

<body>
   <div id="search">
        <input type="text" name="keywords" id="keywords" value=""/><input id="searchBtn" type="button" name="button" value="查找"/>
   </div>
   <div id="search_result">
      <ul id="result_list">
      </ul>
   </div>
   
   <div id="mapDiv"></div>
</body>

</html>