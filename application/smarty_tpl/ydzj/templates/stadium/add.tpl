{include file="common/header.tpl"}
{include file="common/baidu_map.tpl"}
{$message}
<div class="handle_area">
{form_open_multipart(site_url('stadium/add'),'id="stadiumForm"')}
    <input type="hidden" name="longitude" value="{$smarty.post['longitude']}"/>
    <input type="hidden" name="latitude" value="{$smarty.post['latitude']}"/>
    <input type="hidden" name="has_coordinates" value="{$smarty.post['has_coordinates']}"/>
    <input type="hidden" name="other_image_count" value="1"/>
    <div class="row">
        <label class="required side_lb">场馆名称</label>
        <input type="text" class="at_txt" name="title" value="{set_value('title')}" placeholder="请输入场馆名称"/>
    </div>
    {form_error('title')}
    <div class="row">
        <label class="required side_lb">场馆地址</label>
        <input type="text" class="at_txt disabled" name="address" readonly="readonly" value="{set_value('address')}" placeholder="请在地图上标注位置"/>
    </div>
    {form_error('address')}
    <div class="row" id="mapDiv">
        <button type="button" class="at_txt" style="width:100%;" name="getAddress">获取当前位置地址</button>
        <div style="padding:10px;"><div id="map" style="height:320px"></div></div>
    </div>
    
    <div class="row" style="padding:10px 0;">
    	<label class="side_lb">馆主设置</label>
    	<label style="margin:0 20px 0 0;"><input type="radio" name="is_mine" value="y" {set_radio('is_mine','y',true)}/>我是馆主</label>
        <label><input type="radio" name="is_mine" value="n" {set_radio('is_mine','n')}/>我不是馆主</label>
    </div>
    
    <div class="row notmine" style="display:none;">
        <label class="required side_lb">联系人</label>
        <input type="text" class="at_txt" name="contact" value="{set_value('contact')}" placeholder="请输入联系人名称"/>
    </div>
    <div class="row notmine" style="display:none;">
        <label class="required side_lb">手机号码</label>
        <input type="text" class="at_txt" name="mobile" value="{set_value('mobile')}" placeholder="请输入联系人手机号码"/>
    </div>
    
    
    {foreach from=$allMetaGroups key=key item=item}
    <div class="row">
        <label class="required side_lb">{$key}</label>
        <select class="at_txt" name="{if $key == '地面材质'}ground_type{else if $key == '收费类型'}charge_type{else if $key == '场地类型'}stadium_type{/if}">
            {foreach from=$item item=list}
            <option value="{$list['name']}" {if $smarty.post[$key] == $list['name']}selected{/if}>{$list['name']}</option>
            {/foreach}
        </select>
    </div>
    {/foreach}
    <div class="row">
        <label class="required side_lb vtop" >备注</label>
        <textarea class="at_txt" style="height:100px;" name="remark" placeholder="如：场馆开放时间 周一至周五:早8点至晚10点">{set_value('remark')}</textarea>
    </div>
    <div class="row">
        <label class="required side_lb">封面照片</label>
        <input type="file" name="cover_img" />
    </div>
    
    <div id="fileArea">
        <div class="row">
            <label class="side_lb">其它照片<em>1</em></label>
            <input type="file" name="other_img1" />
        </div>
    </div>
    <div class="row" id="moreFileWrap" style="margin-top:20px;">
    	<label class="side_lb"></label><input type="button" class="primaryBtn grayed at_txt" id="moreFile" value="添加文件选择"/>
   	</div>
    <div class="row" style="margin-top:20px;"><button type="submit" class="primaryBtn" name="submit">保存</button></div>
</form>
</div>

<script type="sp-template" id="addFileTpl">
    <div class="row">
        <label class="side_lb">其它照片<em></em></label>
        <input type="file" name="other_img" />
    </div>
</script>

<script>


</script>
<script src="{base_url('js/stadium.js')}" type="text/javascript"></script>
{include file="common/footer.tpl"}