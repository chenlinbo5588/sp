{include file="common/main_header_navs.tpl"}
  {config_load file="worker.conf"}
  {if $info['id']}
  {form_open_multipart(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open_multipart(site_url($uri_string),'id="infoform"')}
  <input type="hidden" name="worker_id" value="{$info['worker_id']}"/>
  {/if}
  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
    <table class="table tb-type2 mgbottom">
      <tbody>
        {include file="./basic_info.tpl"}
        <tr>
          <td colspan="2" class="required">{#people_photo#}: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="hidden" name="old_pic" value="{if $info['old_pic']}{$info['old_pic']}{/if}"/>
          	<span class="type-file-show">
          		<img class="show_image" src="{resource_url('img/preview.png')}">
          		<div class="type-file-preview">{if $info['avatar_m']}<img src="{resource_url($info['avatar_m'])}"/>{else if $info['avatar_b']}<img src="{resource_url($info['avatar_b'])}"/>{elseif $info['avatar']}<img src="{resource_url($info['avatar'])}"/>{/if}</div>
            </span>
            <span class="type-file-box"><input type='text' name='worker_pic_txt' value="{if $info['avatar']}{$info['avatar']}{/if}" id='worker_pic_txt' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="worker_pic" type="file" class="type-file-file" id="worker_pic" size="30" hidefocus="true" nc_type="change_brand_logo">
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">工作人员照片。</span></td>
        </tr>
       	<tr>
          <td colspan="2" class="required">{#other_photo#}:<span class="orange">【JPG格式:文件大小2M以内】</span></td>
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
       			<li id="{$item['image_aid']}" class="picture">
       				<input type="hidden" name="file_id[]" value="{$item['image_aid']}" />
       				<div class="size-64x64">
       					<span class="thumb"><i></i>
       						<a class="fancybox" href="{resource_url($item['image_b'])}" data-fancybox-group="gallery">
       							<img src="{resource_url($item['image_m'])}" alt="" width="64px" height="64px"/>
   							</a>
   						</span>
					</div>
					<p>
						<span><a href="javascript:del_file_upload('{$item['image_aid']}');">删除</a></span>
					</p>
				</li>
       			{/foreach}
       			</ul>
       		</td>
       	</tr>
        <tr>
          <td colspan="2" class="required"><label>备注: </label>{form_error('remark')}</td>
        </tr>
        <tr>
        	<td colspan="2" ><textarea id="remark" name="remark" style="width:100%;height:300px;visibility:hidden;">{$info['remark']}</textarea></td>
        	
        </tr>
      </tbody>
    </table>
    <div class="fixedOpBar">
    	<input type="submit" name="tijiao" value="保存" class="msbtn"/>
    	{if $gobackUrl}
    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
    	{/if}
    </div>
  </form>
  {include file="common/ke.tpl"}
  {include file="common/uploadify.tpl"}
  {include file="common/fancybox.tpl"}
  <script type="text/javascript">
  	var province_idcard = {$province_idcard},
  		remarkEditor,
  		KEUploadUrl = commonUploadUrl + '?mod={$moduleClassName}',
  		deleteImgUrl = '{admin_site_url($moduleClassName|cat:"/delimg")}?mod={$moduleClassName}',
  		uploadUrl = '{admin_site_url($moduleClassName|cat:"/addimg")}?mod={$moduleClassName}&id={$info['id']}';
	
	$(function(){
		$( ".datepicker" ).datepicker();
		$('.fancybox').fancybox();
		
		
		$("#worker_pic").change(function(){
			$("#worker_pic_txt").val($(this).val());
		});
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
{include file="common/main_footer.tpl"}