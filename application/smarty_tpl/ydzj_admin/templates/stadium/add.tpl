{include file="common/main_header.tpl"}
{config_load file="stadium.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('stadium/index')}"><span>{#manage#}</span></a></li>
      	<li><a href="{admin_site_url('stadium/add')}" {if !$info['id']}class="current"{/if}><span>新增</span></a></li>
      	{if $info['id']}<li><a href="{admin_site_url('stadium/edit?id=')}{$info['id']}" class="current"><span>编辑</span></a></li>{/if}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  
  <div id="mapDiv" style="display:none" >
  	<div class="tip_warning">点击地图标注场馆所在具体位置</div>
    <div id="map" style="height:400px;"></div>
  </div>
  {if $info['id']}
  {form_open(admin_site_url('stadium/edit'),'id="add_form"')}
  {else}
  {form_open(admin_site_url('stadium/add'),'id="add_form"')}
  {/if}
  	<input type="hidden" name="id" value="{$info['id']}"/>
  	<input type="hidden" name="longitude" value="{$info['longitude']}"/>
    <input type="hidden" name="latitude" value="{$info['latitude']}"/>
    <input type="hidden" name="province" value="{$info['province']}"/>
    <input type="hidden" name="city" value="{$info['city']}"/>
    <input type="hidden" name="district" value="{$info['district']}"/>
    <input type="hidden" name="street" value="{$info['street']}"/>
    <input type="hidden" name="street_number" value="{$info['street_number']}"/>
    <table class="table tb-type2">
      <tbody>
      	<tr class="noborder">
      		<td colspan="2" class="required"><label class="validation">{#title#}{#short_name#}</label></td>
      	</tr>
      	<tr class="noborder">
	        <td class="vatop rowform">
	          	<input type="text" class="txt" value="{$info['name']|escape}" name="name" id="name" placeholder="请输入{#title#}{#short_name#}" class="txt">
	        </td>
	        <td class="vatop tips">{form_error('name')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label>{#title#}{#full_name#}</label></td>
      	</tr>
      	<tr class="noborder">
	        <td class="vatop rowform">
	          	<input type="text" class="txt" value="{$info['full_name']|escape}" name="full_name" id="full_name" placeholder="请输入{#title#}{#full_name#}" class="txt">
	        </td>
	        <td class="vatop tips">{form_error('full_name')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label class="validation">{#title#}{#address#}</label></td>
      	</tr>
        <tr class="noborder">
	        <td class="vatop rowform">
	          	<input type="text" class="txt" value="{$info['address']|escape}" name="address" id="address" placeholder="请输入{#title#}{#address#}" class="txt">
	        </td>
	        <td class="vatop tips"><a href="javascript:void(0);" id="locationOnMap">开始地图标注</a> <strong id="tip_address">{form_error('address')}</strong></td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label>{#owner#}</label></td>
      	</tr>
        <tr class="noborder">
	        <td class="vatop rowform">
	          	<input type="text" class="txt" value="{$info['owner_username']|escape}" name="owner_username" placeholder="请输入{#title#}{#owner#}" class="txt">
	        </td>
	        <td class="vatop tips">{form_error('owner_username')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label>{#contact#}</label></td>
      	</tr>
        <tr class="noborder">
	        <td class="vatop rowform">
	          	<input type="text" class="txt" value="{$info['contact']|escape}" name="contact" placeholder="请输入{#title#}{#contact#}" class="txt">
	        </td>
	        <td class="vatop tips">{form_error('contact')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label>{#owner#}{#mobile#}</label></td>
      	</tr>
        <tr class="noborder">
	        <td class="vatop rowform">
	        
	          	<input type="text" class="txt" value="{$info['mobile']|escape}" name="mobile" id="mobile" placeholder="请输入{#title#}{#mobile#}" class="txt">
	        </td>
	        <td class="vatop tips">{form_error('mobile')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label>{#owner#}备用{#mobile#}</label></td>
      	</tr>
        <tr class="noborder">
	        <td class="vatop rowform">
	          	<input type="text" class="txt" value="{$info['mobile2']|escape}" name="mobile2" id="mobile2" placeholder="请输入{#title#}{#mobile2#}" class="txt">
	        </td>
	        <td class="vatop tips">{form_error('mobile2')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label>{#tel#}</label></td>
      	</tr>
        <tr class="noborder">
	        <td class="vatop rowform">
	          	<input type="text" class="txt" value="{$info['tel']|escape}" name="tel" id="tel" placeholder="请输入{#tel#}" class="txt">
	        </td>
	        <td class="vatop tips">{form_error('mobile')}</td>
        </tr>
      	<tr class="noborder">
      		<td colspan="2" class="required"><label class="validation" >{#category_name#}</label></td>
      	</tr>
      	<tr class="noborder">
	        <td class="vatop rowform">
	            {foreach from=$allMetaList['场地类型'] item=item}
	            <label><input type="checkbox" name="category_name[]" value="{$item['name']}" {if is_array($info['category_name']) && in_array($item['name'],$info['category_name'])}checked{/if}/>{$item['name']}</label>
	            {/foreach}
	        </td>
	        <td class="vatop tips">{form_error('category_name[]')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label class="validation">{#ground_type#}</label></td>
      	</tr>
      	<tr class="noborder">
	        <td class="vatop rowform">
	          	{foreach from=$allMetaList['地面材质'] item=item}
	            <label><input type="checkbox" name="ground_type[]" value="{$item['name']}" {if is_array($info['ground_type']) && in_array($item['name'],$info['ground_type'])}checked{/if}/>{$item['name']}</label>
	            {/foreach}
	        </td>
	        <td class="vatop tips">{form_error('ground_type[]')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label class="validation">{#charge_type#}</label></td>
      	</tr>
      	<tr class="noborder">
	        <td class="vatop rowform">
	          	{foreach from=$allMetaList['收费类型'] item=item}
	            <label><input type="checkbox" name="charge_type[]" value="{$item['name']}" {if is_array($info['charge_type']) && in_array($item['name'],$info['charge_type'])}checked{/if}/>{$item['name']}</label>
	            {/foreach}
	        </td>
	        <td class="vatop tips">{form_error('charge_type[]')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label class="validation">{#open_type#}</label></td>
      	</tr>
      	<tr class="noborder">
	        <td class="vatop rowform">
	          	{foreach from=$allMetaList['开放类型'] item=item}
	            <label><input type="radio" name="open_type" value="{$item['name']}" {if $info['open_type'] == $item['name']}checked{/if}/>{$item['name']}</label>
	            {/foreach}
	        </td>
	        <td class="vatop tips">{form_error('open_type')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label class="validation">{#owner_type#}</label></td>
      	</tr>
      	<tr class="noborder">
	        <td class="vatop rowform">
	          	{foreach from=$allMetaList['权属类型'] item=item}
	            <label><input type="radio" name="owner_type" value="{$item['name']}" {if $info['owner_type'] == $item['name']}checked{/if}/>{$item['name']}</label>
	            {/foreach}
	        </td>
	        <td class="vatop tips">{form_error('owner_type')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label class="validation">{#support_sports#}</label></td>
      	</tr>
        <tr class="noborder">
	        <td class="vatop rowform">
	          	{foreach from=$sportsList item=item}
	            <label><input type="checkbox" name="support_sports[]" value="{$item['name']}" {if is_array($info['support_sports']) && in_array($item['name'],$info['support_sports'])}checked{/if}/>{$item['name']}</label>
	            {/foreach}
	        </td>
	        <td class="vatop tips">{form_error('support_sports[]')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">{#title#}主图:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <input type="hidden" name="aid" id="aid" value="{$info['aid']}"/>
            <div class="upload"><input type='text' readonly="readonly" class="txt" name='avatar' id='avatar' value="{$info['avatar']}"/><input type="button" id="uploadButton" value="浏览" /></div>
            </td>
          <td class="vatop tips">{form_error('avatar')} 支持格式jpg或者PNG 最小尺寸 <strong class="hightlight">{$imageConfig['h']['width']}x{$imageConfig['h']['height']}</strong></td>
        </tr>
        <tr>
        	<td colspan="2" class="required"><div id="preview">{if $info['avatar']}<img width="{$imageConfig['h']['width']}" height="{$imageConfig['h']['height']}" src="{resource_url($info['avatar'])}"/>{/if}</div></td>
        </tr>
        <tr>
          <td colspan="2" class="required">{#title#}其他展示图片上传(JPG格式 , 用于显示在详情页面):</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" id="divComUploadContainer"><input type="file" multiple="multiple" id="fileupload" name="fileupload" /></td>
        </tr>
        <tr>
       		<td colspan="2">
       			<ul id="thumbnails" class="thumblists">
       			{foreach from=$fileList item=item}
       			<li id="{$item['id']}" class="picture"><input type="hidden" name="file_id[]" value="{$item['id']}" /><div class="size-64x64"><span class="thumb"><i></i><img src="{resource_url($item['file_url'])}" alt="" width="64px" height="64px"/></span></div><p><span><a href="javascript:insert_editor('{resource_url($item['file_url'])}');">插入</a></span><span><a href="javascript:del_file_upload('{$item['id']}');">删除</a></span></p></li>
       			{/foreach}
       			</ul>
       		</td>
       	</tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>{#remark#}</label></td>
        </tr>
        <tr class="noborder">
          <td><textarea name="remark" style="height:100px;width:100%;"></textarea></td>
          <td class="vatop tips">{form_error('remark')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required">开启状态: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="status1" {if $info['status'] == 1}class="cb-enable selected"{else}class="cb-enable"{/if}><span>是</span></label>
            <label for="status0" {if $info['status'] == 1}class="cb-disable"{else}class="cb-disable selected"{/if}><span>否</span></label>
            <input id="status1" name="status" {if $info['status'] == 1}checked{/if} value="1" type="radio">
            <input id="status0" name="status" {if $info['status'] == 0}checked{/if} value="0" type="radio"></td>
          <td class="vatop tips">{form_error('status')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{if $info['displayorder']}{$info['displayorder']}{else}255{/if}" name="displayorder" class="txt"></td>
          <td class="vatop tips">{form_error('displayorder')} 数字范围为0~255，数字越小越靠前</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
  {include file="common/fileupload.tpl"}
  {include file="common/ke.tpl"}
  {include file="common/baidu_map.tpl"}
  <script>
  	var stadiumPic = "{resource_url('img/basketball.png')}";
	{if $info['longitude']}
	var longitude = "{$info['longitude']}";
	var latitude = "{$info['latitude']}";
	{/if}
  </script>
  <script type="text/javascript" src="{resource_url('js/stadium/map_loc.js')}"></script>
  <script type="text/javascript">
  	KindEditor.ready(function(K) {
		var uploadbutton = K.uploadbutton({
			button : K('#uploadButton')[0],
			fieldName : 'imgFile',
			extraParams : { formhash : formhash,min_width :{$imageConfig['h']['width']},min_height: {$imageConfig['h']['height']} },
			url : '{admin_site_url("common/pic_upload")}?mod=stadium',
			afterUpload : function(data) {
				refreshFormHash(data);
				if (data.error === 0) {
					K('#avatar').val(data.url);
					K('#aid').val(data.id);
					
					K('#preview').html('<img width="{$imageConfig['h']['width']}" height="{$imageConfig['h']['height']}" src="' + data.url + '"/>');
					
				} else {
					alert(data.msg);
				}
			},
			afterError : function(str) {
				alert('自定义错误信息: ' + str);
			}
		});
		uploadbutton.fileBox.change(function(e) {
			uploadbutton.submit();
		});
	});
	
	
	function del_file_upload(file_id)
	{
	    if(!window.confirm('您确定要删除吗?')){
	        return;
	    }
	    $.getJSON('{admin_site_url("goods/delimg")}?mod=stadium&file_id=' + file_id + "&id=" + $("input[name=id]").val(), function(result){
	    	refreshFormHash(result.data);
	        if(result){
	            $('#' + file_id).remove();
	        }else{
	            alert('删除失败');
	        }
	    });
	}
	
	$(function(){
		loadScript();
		
		$("#locationOnMap").bind('click',function(){
			$("#mapDiv").slideToggle();
		});
		
		// 图片上传
	    $('#fileupload').each(function(){
	        $(this).fileupload({
	            dataType: 'json',
	            url: '{admin_site_url("stadium/addimg")}?mod=stadium',
	            done: function (e,data) {
	            	refreshFormHash(data.result);
	                if(data.result.error == 0){
	                	add_uploadedfile(data.result);
	                }
	            }
	        });
	    });
	    
	    function add_uploadedfile(file_data)
		{
		    var newImg = '<li id="' + file_data.id + '" class="picture"><input type="hidden" name="file_id[]" value="' + file_data.id + '" /><div class="size-64x64"><span class="thumb"><i></i><img src="' + file_data.url + '" alt="" width="64px" height="64px"/></span></div><p><span><a href="javascript:insert_editor(\'' + file_data.url + '\');">插入</a></span><span><a href="javascript:del_file_upload(\'' + file_data.id + '\');">删除</a></span></p></li>';
		    $('#thumbnails').prepend(newImg);
		}
	})
  </script>
{include file="common/main_footer.tpl"}