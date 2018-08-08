{include file="common/main_header_navs.tpl"}
  {config_load file="worker.conf"}
  {if $info['id']}
  {form_open_multipart(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open_multipart(site_url($uri_string|cat:'?worker_id='|cat:$info['worker_id']),'id="infoform"')}
  <input type="hidden" name="worker_id" value="{$info['worker_id']}"/>
  <input type="hidden" name="worker_type" value="{$info['worker_type']}"/>
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
          	{if $editable}
          	<input type="file" id="avatarFile" name="avatarFile" />
          	{/if}
          	<input type="hidden" name="avatar" value="{$info['avatar_b']}" id="avatar"/>
          	<input type="hidden" name="old_pic" value="" id="old_pic"/>
          	<div>
          		<img id="view_img" src="{if $info['avatar_m']}{resource_url($info['avatar_m'])}{/if}"/>
          	</div>
          </td>
        </tr>
        <tr>
          <td colspan="2">{#exp_photo#}:<span class="orange">JPG格式,文件大小2M以内</span></td>
        </tr>
        {if $editable}
        <tr class="noborder">
          <td colspan="2">
          	<input type="file" id="fileupload" />
          </td>
        </tr>
        {/if}
        <tr>
       		<td colspan="2">
       			<ul id="imageList" class="thumblists">
       			{foreach from=$imgList item=item}
       			<li id="img{$item['id']}" class="picture"><input type="hidden" name="img_file_id[]" value="{$item['id']}" /><div class="size-64x64"><span class="thumb"><i></i><a class="fancybox" href="{resource_url($item['image_b'])}" data-fancybox-group="gallery"><img src="{resource_url($item['image_m'])}" alt="" width="64px" height="64px"/></a></span></div><p><span>{if $editable}<a class="delLink" data-id="{$item['id']}" href="javascript:void(0);">删除</a>{/if}</span></p></li>
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
    <div class="fixedOpBar">
    	{if $editable}
    	<input type="submit" name="tijiao" value="保存" class="msbtn"/>
    	{/if}
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
  		cutUrl = '{admin_site_url($moduleClassName|cat:"/pic_cut")}?mod={$moduleClassName}&id={$info['id']}';
  		
	var uploadUrls = {
  		img :  {
  			uploadUrl : '{admin_site_url($moduleClassName|cat:"/addimg")}?id={$info['id']}',
  			deleteUrl : '{admin_site_url($moduleClassName|cat:"/delimg")}?id={$info['id']}'
  		}
  	};
	
	{if $successMessage}
		showToast('success','{$successMessage}')
	{/if}
	
	{if $redirectUrl}
		setTimeout(function(){
			location.href="{$redirectUrl}";
		},2000);
	{/if}
	
  </script>
  <script type="text/javascript" src="{resource_url('js/avatar_upload.js',true)}"></script>
  <script type="text/javascript" src="{resource_url('js/service/staff.js',true)}"></script>
{include file="common/main_footer.tpl"}