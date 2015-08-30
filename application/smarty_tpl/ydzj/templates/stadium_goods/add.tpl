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
        <label class="required side_lb">权属</label>
        <select name="owner_type" class="at_txt">
            {foreach from=$stadiumOwnerList item=item}
            <option value="{$item['id']}" {set_select('owner_type',$item['id'])}>{$item['name']}</option>
            {/foreach}
        </select>
    </div>
    <div class="row">
        <label class="required side_lb">名称</label>
        <input type="text" class="at_txt" name="title" value="{set_value('title')}" placeholder="请输入名称"/>
    </div>
    <div id="tip_title">{form_error('title')}</div>
    <div class="row">
        <label class="required side_lb">地址</label>
        <input type="text" class="at_txt disabled" style="width:50%" name="address" readonly="readonly" value="{set_value('address')}" placeholder="请在地图上标注位置"/>
        <i class="fa fa-map-marker fa-lg" style="cursor:pointer" id="markerOnMap">开始标注</i>
    </div>
    <div id="tip_address">{form_error('address')}</div>
    <div class="row" id="mapDiv" style="display:none;">
        
        <div class="warning">点击地图标注场馆所在具体位置</div>
        <div id="map"><i class="fa fa-spinner fa-spin"></i></div>
    </div>
    
    <div class="row" style="padding:10px 0;">
    	<label class="side_lb">权属设置</label>
    	<select name="is_mine" class="at_txt">
    	   <option value="y" {set_select('is_mine','y')}>我是权属人</option>
    	   <option value="n" {set_select('is_mine','n')}>我不是权属人，我知道联系方式</option>
    	   <option value="unknow"  {set_select('is_mine','unknow')}>我不是权属人，不知道联系方式</option>
    	</select>
    </div>
    
    <div class="row notmine" {if $smarty.post['is_mine'] != 'n'}style="display:none;"{/if}>
        <label class="required side_lb">联系人</label>
        <input type="text" class="at_txt" name="contact" value="{set_value('contact')}" placeholder="请输入联系人名称"/>
    </div>
    <div id="tip_contact">{form_error('contact')}</div>
    <div class="row notmine" {if $smarty.post['is_mine'] != 'n'}style="display:none;"{/if}>
        <label class="required side_lb">手机号码</label>
        <input type="text" class="at_txt" name="mobile" value="{set_value('mobile')}" placeholder="如:13800880088"/>
    </div>
    <div id="tip_mobile">{form_error('mobile')}</div>
    <div class="row notmine" {if $smarty.post['is_mine'] != 'n'}style="display:none;"{/if}>
        <label class="required side_lb">座机号码</label>
        <input type="text" class="at_txt" name="tel" value="{set_value('tel')}" placeholder="如:0574-63006300"/>
    </div>
    <div id="tip_tel">{form_error('tel')}</div>
    <div class="row">
        <label class="required side_lb vtop" >备注</label>
        <textarea class="at_txt" style="height:100px;" name="remark" placeholder="如：场馆开放时间 周一至周五:早8点至晚10点">{set_value('remark')}</textarea>
    </div>
    
    {foreach from=$maxOtherFile item=item}
    <div class="row photoUpload">
        <label class="side_lb">{if $item == 0}封面照片{else}其它照片<em>{$item}</em>{/if}</label>
        <input type="hidden" value="{$fileUpload['other_img'][$item]['url']}" name="other_img{$item}_url"/>
        <input type="hidden" value="{$fileUpload['other_img'][$item]['id']}" name="other_img{$item}_id"/>
        <input type="file" name="other_img{$item}" />
        {if $fileUpload['other_img'][$item]['url']}<a class="opTrash"><i class="fa fa-trash"></i> 删除</a>{/if}
    </div>
    {if $item == 0}
    <div class="row form_error" id="tip_img{$item}">{$img_error0}</div>
    <a name="cover_error"></a>
    {/if}
    <div class="row img_preview">{if $fileUpload['other_img'][$item]['preview']}<img class="nature" src="{base_url($fileUpload['other_img'][$item]['preview'])}"/>{/if}</div>
    {/foreach}
   	
   	<div class="row" id="submitFixedWrap" >
        <div class="row col" ><button id="saveBtn" type="submit" class="primaryBtn" name="submit">保存</button>
        <input type="button" id="closeMapBtn" name="closeMap" class="primaryBtn" style="display:none;" value="关闭地图"/></div>
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

{if $img_error0}
var hash = "cover_error";
{/if}

</script>
<script src="{base_url('js/stadium.js')}" type="text/javascript"></script>
{include file="common/footer.tpl"}