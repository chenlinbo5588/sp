{include file="common/main_header.tpl"}
  {config_load file="worker.conf"}
  {include file="common/sub_nav.tpl"}
  {if $info['id']}
  {form_open_multipart(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open_multipart(site_url($uri_string|cat:'?worker_id='|cat:$info['worker_id']),'id="infoform"')}
  <input type="hidden" name="worker_id" value="{$info['worker_id']}"/>
  {/if}
  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
    <table class="table tb-type2 mgbottom">
      <tbody>
      	{include file="staff/detail_common.tpl"}
        <tr>
          <td colspan="2" class="required">{#avatar_photo#}:<span class="orange">用于显示在搜索列表页面,JPG格式,文件大小2M以内</span></td>
        </tr>
        <tr class="noborder">
          <td colspan="2">
          	<input type="file" id="avatarFile" name="avatarFile" />
          	<input type="hidden" name="avatar" value="{$info['avatar_b']}" id="avatar"/>
          	<input type="hidden" name="old_pic" value="" id="old_pic"/>
          	<div>
          		<img id="view_img" src="{if $info['avatar_m']}{resource_url($info['avatar_m'])}{/if}"/>
          	</div>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="required">{#exp_photo#}:<span class="orange">JPG格式,文件大小2M以内</span></td>
        </tr>
        <tr class="noborder">
          <td colspan="2">
          	<input type="file" id="fileupload" name="fileupload" />
          </td>
        </tr>
        <tr>
       		<td colspan="2">
       			<ul id="thumbnails" class="thumblists">
       			{foreach from=$fileList item=item}
       			<li id="{$item['image_aid']}" class="picture"><input type="hidden" name="file_id[]" value="{$item['image_aid']}" /><div class="size-64x64"><span class="thumb"><i></i><a class="fancybox" href="{resource_url($item['image_b'])}" data-fancybox-group="gallery"><img src="{resource_url($item['image_m'])}" alt="" width="64px" height="64px"/></a></span></div><p><span><a href="javascript:del_file_upload('{$item['image_aid']}');">删除</a></span></p></li>
       			{/foreach}
       			</ul>
       		</td>
       	</tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>{#bz#}: </label>{form_error('remark')}</td>
        </tr>
        <tr class="noborder">
        	<td colspan="2" ><textarea id="remark" name="remark" style="width:100%;height:300px;visibility:hidden;">{$info['remark']}</textarea></td>
        </tr>
      </tbody>
    </table>
    <div class="fixedBar">
    	<input type="submit" name="tijiao" value="保存" class="msbtn"/>
    	{if $gobackUrl}
    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
    	{/if}
    </div>
  </form>
  <div id="avatarDlg"></div>
  {include file="common/ke.tpl"}
  {include file="common/uploadify.tpl"}
  {include file="common/jcrop.tpl"}
  {include file="common/fancybox.tpl"}
  
  <script type="text/javascript">
  	
  	var province_idcard = {$province_idcard},
  		remarkEditor,
  		KEUploadUrl = commonUploadUrl + '?mod={$moduleClassName}',
  		deleteImgUrl = '{admin_site_url($moduleClassName|cat:"/delimg")}?mod={$moduleClassName}',
  		cutUrl = '{admin_site_url($moduleClassName|cat:"/pic_cut")}?mod={$moduleClassName}&id={$info['id']}',
  		uploadUrl = '{admin_site_url($moduleClassName|cat:"/addimg")}?mod={$moduleClassName}&id={$info['id']}';
	
	function call_back(resp){
	    refreshFormHash(resp);
	    $("#old_pic").val($('#avatar').val());
	    
	    $('#avatar').val(resp.picname);
	    $('#view_img').attr('src',resp.url);
	    
	    
	    $("#avatarDlg" ).dialog( "close" );
	    
	    
	}
	
	function resizeDlg(){
		$("#avatarDlg").dialog({
    	    position: { my : "center", at: "center", of: window }
      	});
	}
	
	$(function(){
		$( ".datepicker" ).datepicker();
		$('.fancybox').fancybox();
	});
	
	{if $successMessage}
		showToast('success','{$successMessage}')
	{/if}
	
	{if $redirectUrl}
		setTimeout(function(){
			location.href="{$redirectUrl}";
		},2000);
	{/if}
		
  </script>
  <script type="text/javascript" src="{resource_url('js/service/info.js',true)}"></script>
  <script type="text/javascript" src="{resource_url('js/service/staff.js',true)}"></script>
{include file="common/main_footer.tpl"}