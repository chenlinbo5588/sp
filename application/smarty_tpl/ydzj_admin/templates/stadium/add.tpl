{include file="common/admin_header.tpl"}

<script>
var map;

function setJW(obj){
     $.mobile.loading( "hide" );
     //console.log(obj);
     if(typeof(obj) != "undefined"){
	     $("input[name=longitude]").val(obj.longitude);
	     $("input[name=latitude]").val(obj.latitude);
         $("input[name=has_coordinates]").val(1);
	     
	     var geoc = new BMap.Geocoder();
	     
	     geoc.getLocation(obj.point, function(rs){
	        var addComp = rs.addressComponents;
	        var address = addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber;
	        $("input[name=address]").val(address);
	        //alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
	    });
    }
    
    
}
  
function initialize() {
  map = new BMap.Map('map');  
  map.centerAndZoom(new BMap.Point(121.491, 31.233), 15);
  
  var top_left_control = new BMap.ScaleControl({ anchor: BMAP_ANCHOR_TOP_LEFT });// 左上角，添加比例尺
  var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
  
  map.addControl(top_left_control);
  map.addControl(top_left_navigation);    
  
  //getCurrentLocation(map,setJW);
  
  $("button[name=getAddress]").bind("click",function(){
	    var $this = $( this ),
	        theme = $this.jqmData( "theme" ) || $.mobile.loader.prototype.options.theme,
	        msgText = $this.jqmData( "msgtext" ) || $.mobile.loader.prototype.options.text,
	        textVisible = $this.jqmData( "textvisible" ) || $.mobile.loader.prototype.options.textVisible,
	        textonly = !!$this.jqmData( "textonly" );
	        html = $this.jqmData( "html" ) || "";
	
	    $.mobile.loading( "show", {
	            text: msgText,
	            textVisible: textVisible,
	            theme: theme,
	            textonly: textonly,
	            html: html
	    });

      getCurrentLocation(map,setJW);
  });
}

$(function(){
    $("input[name=openMap]").bind("click",function(){
        //$(this).val();
        $("#mapDiv").show();
        loadScript();
    });
    
    
    
});
</script>

{include file="common/baidu_map.tpl"}
{validation_errors()}
{$message}
{form_open_multipart(admin_site_url('stadium/add'),$formAttr)}
    <input type="hidden" name="longitude" value="{$smarty.post['longitude']}"/>
    <input type="hidden" name="latitude" value="{$smarty.post['latitude']}"/>
    <input type="hidden" name="has_coordinates" value="{$smarty.post['has_coordinates']}"/>
    <input type="hidden" name="other_image_count" value="1"/>
    <div class="ui-field-contain">
        <label class="required">场馆名称</label>
        <input type="text" name="title" value="{set_value('title')}" placeholder="请输入场馆名称"/>
        {form_error('title')}
    </div>
    
    <div class="ui-field-contain">
        <label class="required">场馆地址</label>
        <input type="text" name="address" value="{set_value('address')}" placeholder="请输入场馆地址,(地图模式下)可通过获取位置自动填充"/>
        <input type="button" data-inline="true" name="openMap" value="地图模式"/>
        {form_error('address')}
    </div>
    <div class="ui-field-contain" id="mapDiv" style="display:none;">
        <button type="button" name="getAddress" class="ui-btn ui-icon-navigation ui-btn-icon-left ui-shadow ui-corner-all" data-textonly="false" data-textvisible="true" data-msgtext="正在获取位置" data-inline="true">获取地址</button>
        <div style="padding:20px;"><div id="map" style="height:320px"></div></div>
    </div>
    
    <div class="ui-field-contain">
        <label class="required">联系人</label>
        <input type="text" name="contact" value="{set_value('contact')}" placeholder="请输入联系人名称"/>
        {form_error('contact')}
    </div>
    <div class="ui-field-contain">
        <label>手机号码</label>
        <input type="text" name="mobile" value="{set_value('mobile')}" placeholder="请输入联系人手机号码"/>
        {form_error('contact')}
    </div>
    {foreach from=$allMetaGroups key=key item=item}
    <div class="ui-field-contain">
        <label>{$key}</label>
        <select name="{if $key == '地面材质'}ground_type{else if $key == '收费类型'}charge_type{else if $key == '场地类型'}stadium_type{/if}">
            {foreach from=$item item=list}
            <option value="{$list['name']}" {if $smarty.post[$key] == $list['name']}selected{/if}>{$list['name']}</option>
            {/foreach}
        </select>
    </div>
    {/foreach}
    <div class="ui-field-contain">
        <label>备注</label>
        <textarea name="remark" placeholder="如周一至周五:早8点 - 晚10点">{set_value('remark')}</textarea>
    </div>
    <div class="ui-field-contain">
        <label>场馆封面照片</label>
        <input type="file" name="cover_img" />
    </div>
    
    <a href="javascript:void(0);" id="moreFile" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-icon-plus ui-btn-b ui-mini">添加文件选择</a>
    <div id="fileArea">
        <div class="ui-field-contain">
            <label>其它照片<em>1</em></label>
            <input type="file" name="other_img1" />
        </div>
    </div>
    <button type="submit" name="submit" class="ui-btn-active">保存</button>
</form>

<script type="sp-template" id="addFileTpl">
    <div class="ui-field-contain">
        <label>其它照片<em></em></label>
        <input type="file" name="other_img" />
    </div>
</script>
<a href="{admin_site_url('stadium')}" class="ui-btn">返回</a>
<script>

$("#moreFile").bind("click",function(event){
    var count = $("#fileArea em").size() + 1;
    var fileTpl = $($("#addFileTpl").html());
    
    fileTpl.find("input").attr("name","other_img" + count);
    fileTpl.find("em").html(count);
    
    $("input[name=other_image_count]").val(count);
    $("#fileArea").append(fileTpl);
});

</script>
{include file="common/admin_footer.tpl"}