{include file="common/header.tpl"}
{$feedback}
<link rel="stylesheet" href="{resource_url('css/swiper/swiper.min.css')}">
<div id="stadiumDetail" {if $inManageMode}class="handle_area"{/if}>
{if $inManageMode}
{include file="common/baidu_map.tpl"}
	{form_open_multipart(site_url($formTarget),'id="stadiumForm"')}
	{if $inPost}
    <input type="hidden" name="longitude" value="{$smarty.post['longitude']}"/>
    <input type="hidden" name="latitude" value="{$smarty.post['latitude']}"/>
    <input type="hidden" name="province" value="{$smarty.post['province']}"/>
    <input type="hidden" name="city" value="{$smarty.post['city']}"/>
    <input type="hidden" name="district" value="{$smarty.post['district']}"/>
    <input type="hidden" name="street" value="{$smarty.post['street']}"/>
    <input type="hidden" name="street_number" value="{$smarty.post['streetNumber']}"/>
    {else}
    <input type="hidden" name="longitude" value="{$stadium['basic']['longitude']}"/>
    <input type="hidden" name="latitude" value="{$stadium['basic']['latitude']}"/>
    <input type="hidden" name="province" value="{$stadium['basic']['dname1']}"/>
    <input type="hidden" name="city" value="{$stadium['basic']['dname2']}"/>
    <input type="hidden" name="district" value="{$stadium['basic']['dname3']}"/>
    <input type="hidden" name="street" value="{$stadium['basic']['dname4']}"/>
    <input type="hidden" name="street_number" value="{$stadium['basic']['streetNumber']}"/>
    {/if}
    
    <div class="row">
        <label class="required side_lb">权属</label>
        <select name="owner_type" class="at_txt">
        {if $inPost}
            {foreach from=$stadiumOwnerList item=item}
            <option value="{$item['name']}" {set_select('owner_type',$item['id'])}>{$item['name']}</option>
            {/foreach}
        {else}
            {foreach from=$stadiumOwnerList item=item}
            <option value="{$item['name']}" {if $stadium['basic']['owner_type'] == $item['name']}selected{/if}>{$item['name']}</option>
            {/foreach}
         {/if}
        </select>
    </div>
    <div class="row">
        <label class="required side_lb">开放程度</label>
        <select name="open_type" class="at_txt">
        {if $inPost}
            {foreach from=$openType key=key item=item}
            <option value="{$key}" {set_select('open_type',$key)}>{$item}</option>
            {/foreach}
        {else}
            {foreach from=$openType key=key item=item}
            <option value="{$key}" {if $stadium['basic']['open_type'] == $key}selected{/if}>{$item}</option>
            {/foreach}
        {/if}
        </select>
    </div>
    <div class="row">
        <label class="required side_lb">场馆名称</label>
        <input type="text" class="at_txt" name="title" {if $inPost}value="{set_value('title')}"{else}value="{$stadium['basic']['title']|escape}"{/if} placeholder="请输入场馆名称"/>
    </div>
    <div id="tip_title">{form_error('title')}</div>
    <div class="row">
        <label class="required side_lb">场馆地址</label>
        <input type="text" class="at_txt disabled" style="width:50%" name="address" readonly="readonly" {if $inPost}value="{set_value('address')}"{else}value="{$stadium['basic']['address']|escape}"{/if} placeholder="请在地图上标注位置"/>
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
        {if $inPost}
           <option value="y" {set_select('is_mine','y')}>我是权属人</option>
           <option value="n" {set_select('is_mine','n')}>我不是权属人，我知道联系方式</option>
           <option value="unknow"  {set_select('is_mine','unknow')}>我不是权属人，不知道联系方式</option>
        {else}
           <option value="y" {if $stadium['basic']['owner_uid'] == $profile['basic']['uid']}selected{/if}>我是权属人</option>
           <option value="n" {if $stadium['basic']['owner_uid'] != $profile['basic']['uid'] && ($stadium['basic']['mobile'] || $stadium['basic']['tel'])}selected{/if}>我不是权属人，我知道联系方式</option>
           <option value="unknow" {if $stadium['basic']['owner_uid'] != $profile['basic']['uid'] && $stadium['basic']['mobile'] == '' && $stadium['basic']['tel'] == '' }selected{/if}>我不是权属人，不知道联系方式</option>
        {/if}
        </select>
    </div>
    
    {if $inPost}
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
    {else}
        <div class="row notmine" {if $stadium['baisc']['owner_uid'] != $profile['basic']['uid']}style="display:none;"{/if}>
            <label class="required side_lb">联系人</label>
            <input type="text" class="at_txt" name="contact" value="{set_value('contact')}" placeholder="请输入联系人名称"/>
        </div>
        <div id="tip_contact">{form_error('contact')}</div>
        <div class="row notmine" {if $stadium['baisc']['owner_uid'] != $profile['basic']['uid']}style="display:none;"{/if}>
            <label class="required side_lb">手机号码</label>
            <input type="text" class="at_txt" name="mobile" value="{set_value('mobile')}" placeholder="如:13800880088"/>
        </div>
        <div id="tip_mobile">{form_error('mobile')}</div>
        <div class="row notmine" {if $stadium['baisc']['owner_uid'] != $profile['basic']['uid']}style="display:none;"{/if}>
            <label class="required side_lb">座机号码</label>
            <input type="text" class="at_txt" name="tel" value="{set_value('tel')}" placeholder="如:0574-63006300"/>
        </div>
        <div id="tip_tel">{form_error('tel')}</div>
    {/if}
    
    <div class="row">
        <label class="required side_lb vtop" >备注</label>
        <textarea class="at_txt" style="height:100px;" name="remark" placeholder="如：场馆开放时间 周一至周五:早8点至晚10点">{if $inPost}{set_value('remark')}{else}{$stadium['basic']['remark']|escape}{/if}</textarea>
    </div>
    
    {foreach from=$maxOtherFile item=item}
    <div class="row photoUpload">
        <label class="side_lb">{if $item == 0}封面照片{else}其它照片<em>{$item}</em>{/if}</label>
        <input type="hidden" value="{$fileUpload['other_img'][$item]['url']}" name="other_img{$item}_url" id="url{$item}"/>
        <input type="hidden" value="{$fileUpload['other_img'][$item]['aid']}" name="other_img{$item}_aid"/>
        <input type="file" name="other_img{$item}" />
        
    </div>
    {if $item == 0}
    <div class="row form_error" id="tip_img{$item}">{$img_error0}</div>
    <a name="cover_error"></a>
    {/if}
    <div class="row img_preview">
        {if $fileUpload['other_img'][$item]['preview']}
        <img class="nature" src="{resource_url($fileUpload['other_img'][$item]['preview'])}"/>
        <a href="javascript:void(0)" class="link_btn grayed opTrash" data-id="url{$item}">删除照片</a>
        {/if}
    </div>
    {/foreach}
	
	<div class="row" id="submitFixedWrap" >
	    <div class="row fl col2">
	        <input class="master_btn fr" id="saveBtn" type="submit" name="submit" value="保存"/>
	        <input type="button" id="closeMapBtn" name="closeMap" class="master_btn fr" style="display:none;" value="关闭地图"/>
	    </div>
	    <div class="row fl col2">
	        <a class="link_btn grayed" href="{$editUrl}"}>{$mangeText}</a>
	    </div>
	</div>
	</form>
	<script>
	var stadiumPic = "{resource_url('img/basketball.png')}";
	{if $inPost}
		{if $smarty.post['longitude']}
	var longitude = "{$smarty.post['longitude']}";
	var latitude = "{$smarty.post['latitude']}";
		{/if}
	{else}
	var longitude = "{$stadium['basic']['longitude']}";
	var latitude = "{$stadium['basic']['latitude']}";
	{/if}
	
	{if $img_error0}
	var hash = "cover_error";
	{/if}
	</script>
	<script src="{resource_url('js/stadium/stadium.js')}" type="text/javascript"></script>
{else}
    
    <div class="swiper-container">
        <div class="swiper-wrapper">
            {foreach from=$stadium['photos'] item=item}
            <div class="swiper-slide"><img src="{resource_url($item['avatar_big'])}" alt="{$stadium['basic']['title']} photo"/></div>
            {/foreach}
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>
    
    <div class="row bordered pd5">
        <div class="row"><label class="side_lb">权属：</label><span>{$stadium['basic']['owner_type']}</span></div>
        <div class="row"><label class="side_lb">名称：</label><span>{$stadium['basic']['title']|escape}</span></div>
        <div class="row"><label class="side_lb">开放程度：</label><span>{$openType[$stadium['basic']['open_type']]}</span></div>
        <div class="row"><label class="side_lb">地址：</label><span>{$stadium['basic']['dname1']}{$stadium['basic']['dname2']}{$stadium['basic']['dname3']}{$stadium['basic']['dname4']}</span><a class="" href="{site_url('stadium/map/'|cat:$stadium['basic']['id'])}"><i class="fa fa-map-marker fa-2x"></i></a></div>
        <div class="row">
            <label class="side_lb">联系人：</label>
            <span>{$stadium['basic']['contact']|escape}</span>
        </div>
        <div class="row">
            <label class="side_lb">联系号码：</label>
            <span>{mask_mobile($stadium['basic']['mobile']|escape)} {$stadium['basic']['tel']|escape}</span>
        </div>
    </div>
</div>

<div class="row">
    <h3 class="subTitle">产品列表</h3>
    <table class="fulltable">
        <colgroup>
            <col style="witdh:20%;"/>
            <col style="witdh:40%;"/>
            <col style="witdh:10%;"/>
            <col style="witdh:20%;"/>
            <col style="witdh:10%;"/>
        </colgroup>
        <thead>
            <tr>
                <th>日期</th>
                <th>A队</th>
                <th>B队</th>
                <th>比分</th>
                <th>胜负</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>15/6/27</td>
                <td><a href="javascript:void(0);">野狼部落</a></td>
                <td><a href="javascript:void(0);">野狼部落</a></td>
                <td>98:87</td>
                <td>胜</td>
            </tr>
            <tr>
                <td>15/6/27</td>
                <td><a href="javascript:void(0);">野狼部落</a></td>
                <td><a href="javascript:void(0);">野狼部落</a></td>
                <td>98:87</td>
                <td>胜</td>
            </tr>
            <tr>
                <td>15/6/27</td>
                <td><a href="javascript:void(0);">野狼部落</a></td>
                <td><a href="javascript:void(0);">野狼部落</a></td>
                <td>98:87</td>
                <td>胜</td>
            </tr>
        </tbody>
    </table>

	<script src="{resource_url('js/swiper/swiper.min.js')}"></script>
	<script>
	var swiper = new Swiper('.swiper-container', {
	    pagination: '.swiper-pagination',
	    paginationClickable: true,
	    loop: true
	});
	</script>
	
	<div class="row" id="submitFixedWrap" >
	    {if $canManager}
	    <div class="row col">
	        <a class="link_btn" href="{$editUrl}"}>{$mangeText}</a>
	    </div>
	    {/if}
	</div>
{/if}
</div>

{include file="common/footer.tpl"}