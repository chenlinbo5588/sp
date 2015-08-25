{include file="common/header.tpl"}
{include file="common/baidu_map.tpl"}
{$feedback}
<div id="stadium" class="handle_area">
{form_open_multipart(site_url('stadium/add'),'id="stadiumForm"')}
    <input type="hidden" name="longitude" value="{$smarty.post['longitude']}"/>
    <input type="hidden" name="latitude" value="{$smarty.post['latitude']}"/>
    <input type="hidden" name="province" value="{$smarty.post['province']}"/>
    <input type="hidden" name="city" value="{$smarty.post['city']}"/>
    <input type="hidden" name="district" value="{$smarty.post['district']}"/>
    <input type="hidden" name="street" value="{$smarty.post['street']}"/>
    <input type="hidden" name="street_number" value="{$smarty.post['streetNumber']}"/>
    <div class="row">
        <label class="required side_lb">场馆分类</label>
        <select name="category_id" class="at_txt">
            {foreach from=$sportsCategoryList item=item}
            <option value="{$item['id']}" {set_select('category_id',$item['id'])}>{$item['name']}</option>
            {/foreach}
        </select>
    </div>
    <div class="row">
        <label class="required side_lb">场馆名称</label>
        <input type="text" class="at_txt" name="title" value="{set_value('title')}" placeholder="请输入场馆名称"/>
    </div>
    <div id="tip_title">{form_error('title')}</div>
    <div class="row">
        <label class="required side_lb">场馆地址</label>
        <input type="text" class="at_txt disabled" name="address" readonly="readonly" value="{set_value('address')}" placeholder="请在地图上标注位置"/>
    </div>
    {form_error('address')}
    <div class="row">
        <div class="warning">请点击地图上标注场馆所在具体位置</div>
    </div>
    <div class="row" id="mapDiv" style="padding:10px;">
        <div id="map" style="height:300px"></div>
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
        <label class="required side_lb">联系手机</label>
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
    
    {foreach from=$maxOtherFile item=item}
    <div class="row">
        <label class="side_lb">{if $item == 0}封面照片{else}其它照片<em>{$item}</em>{/if}</label>
        <input type="hidden" value="{$fileUpload['other_img'][$item]['url']}" name="other_img{$item}_url"/>
        <input type="hidden" value="{$fileUpload['other_img'][$item]['id']}" name="other_img{$item}_id"/>
        <input type="file" name="other_img{$item}" />
    </div>
    <div class="row img_preview">{if $fileUpload['other_img'][$item]['preview']}<img class="nature" src="{base_url($fileUpload['other_img'][$item]['preview'])}"/>{/if}</div>
    {/foreach}
    
    {*
    <div class="row" id="moreFileWrap" style="margin-top:20px;">
    	<label class="side_lb"></label><input type="button" class="primaryBtn grayed at_txt" id="moreFile" value="+添加照片选择"/>
   	</div>
   	*}
   	
   	<div class="row" id="submitFixedWrap" >
        <div class="row col" ><button type="submit" class="primaryBtn" name="submit">保存</button></div>
    </div>
</form>
</div>

{* 暂时不用
<script type="sp-template" id="addFileTpl">
    <div class="row">
        <label class="side_lb">其它照片<em></em></label>
        <input type="hidden" value="" name="other_img_txt"/>
        <input type="file" name="other_img" />
    </div>
</script>
*}
<script>
var stadiumPic = "{base_url('img/basketball.png')}";
{if $smarty.post['longitude']}
var longitude = "{$smarty.post['longitude']}";
var latitude = "{$smarty.post['latitude']}";
{/if}

</script>
<script src="{base_url('js/stadium.js')}" type="text/javascript"></script>
{include file="common/footer.tpl"}