  <style>
    #panel {
        color: #444;
        background-color: #f8f8f8;
        width: 25%;
        float:right;
        height: 100%;
    }
    
    #start,
    #stop,
    #right input {
        margin: 4px;
        margin-left: 15px;
    }
    
    .title {
        width: 100%;
        background-color: #dadada
    }
    
    button {
        border: solid 1px;
        margin-left: 15px;
        background-color: #dadafa;
    }
    
    .subtitle {
        font-weight: 600;
        padding-left: 15px;
        padding-top: 4px;
    }
  
  </style>
  <div id='panel'>
    <div>
        <div class='title'>选择模式</div>
        <input type='radio' name='mode' value='dragMap' checked>拖拽地图模式</input>
        <br/>
        <input type='radio' name='mode' value='dragMarker'>拖拽Marker模式</input>
    </div>
    <div>
        <button id='start'>开始选点</button>
        <button id='stop'>关闭选点</button>
    </div>
    <div>
        <div class='title'>选址结果</div>
        <div class='subtitle'>经纬度:</div>
        <div id='lnglat'></div>
        <div class='subtitle'>地址:</div>
        <div id='show_address'></div>
        <div class='subtitle'>最近的路口:</div>
        <div id='nearestJunction'></div>
        <div class='subtitle'>最近的路:</div>
        <div id='nearestRoad'></div>
        <div class='subtitle'>最近的POI:</div>
        <div id='nearestPOI'></div>
    </div>
  </div>
