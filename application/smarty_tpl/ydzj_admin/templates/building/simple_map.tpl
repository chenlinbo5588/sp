   {include file="common/arcgis_common.tpl"}
   <style>
      #mapDiv {
         height: 100%;
         width: 100%;
         margin: 0;
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
      var map;
      
      require([
        "esri/map", 
        "esri/layers/GraphicsLayer","esri/graphic", 
        "esri/Color",
        "esri/geometry/Point",
        "esri/symbols/SimpleMarkerSymbol",
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
        map = new Map("mapDiv");
        
        //var layerWx = new esri.layers.ArcGISTiledMapServiceLayer("http://{config_item('arcgis_server_ip')}/arcgis/rest/services/basemapzq/MapServer");
        var layerWx = new esri.layers.ArcGISDynamicMapServiceLayer("http://{config_item('arcgis_server_ip')}/arcgis/rest/services/basemapzq/MapServer");
        map.addLayer(layerWx);

	    dojo.connect(map, 'onLoad', function(theMap) {
	      
	      if(typeof(createPoint)){
	           var point = createPoint();
	           if(point){
	               var graphic = new Graphic(new Point([point.x, point.y]), sym);
                   map.graphics.add(graphic);
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
   <div id="mainWindow" data-dojo-type="dijit/layout/BorderContainer" data-dojo-props="design:'headline',gutters:false" style="width:100%; height:100%;">
      <div id="mapDiv" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region:'center'">
      </div>
    </div>

