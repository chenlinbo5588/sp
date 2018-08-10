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
      	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="worker_type">{#worker_type#}:</label></td>
        </tr>
      	<tr class="noborder">
          <td class="vatop rowform">
          	<select name="worker_type" id="worker_type">
	          <option value="">请选择...</option>
	          {foreach from=$workerTypeList item=item}
	          <option {if $info['worker_type'] == $item['id']}selected{/if} value="{$item['id']}">{$item['show_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('worker_type')}</td>
        </tr>
        {include file="./basic_info.tpl"}
        <tr>
          <td colspan="2" class="required">{#avatar_photo#}:<span class="orange">用于显示在搜索列表页面,JPG格式,文件大小{config_item('max_upload_size')}M以内</span></td>
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
        
       	<tr class="noborder">
          <td colspan="2" class="required">{#other_photo#}:<span class="orange">【JPG格式:文件大小{config_item('max_upload_size')}M以内】</span></td>
        </tr>
        {if $editable}
        <tr class="noborder">
          <td colspan="2">
          	<input type="file" id="fileupload" name="fileupload" />
          </td>
        </tr>
        {/if}
        <tr>
       		<td colspan="2">
       			<ul id="imageList" class="thumblists">
       			{foreach from=$imgList item=item}
       			<li id="img{$item['id']}" class="picture">
       				<input type="hidden" name="img_file_id[]" value="{$item['id']}" />
       				<div class="size-64x64">
       					<span class="thumb"><i></i>
       						<a class="fancybox" href="{resource_url($item['image_b'])}" data-fancybox-group="gallery">
       							<img src="{resource_url($item['image_m'])}" alt="" width="64px" height="64px"/>
   							</a>
   						</span>
					</div>
					<p>
						<span>{if $editable}<a data-id="{$item['id']}" class="delLink" href="javascript:void(0);">删除</a>{/if}</span>
					</p>
				</li>
       			{/foreach}
       			</ul>
       		</td>
       	</tr>
       	<tr>
          <td colspan="2" class="required">{#other_file#}:<span class="orange">【PDF, DOC, DOCX:文件大小{config_item('max_upload_size')}M以内】</span></td>
        </tr>
        {if $editable}
        <tr class="noborder">
          <td colspan="2">
          	<input type="file" id="other_fileupload" name="other_fileupload" data-allowFile="*.doc;*.docx;*.pdf;" data-appendId="#thumbnails"  />
          </td>
        </tr>
        {/if}                          
        <tr>
       		<td colspan="2">
       			<table>
       				<thead>
       					<tr>
       						<th>文件名称</th>
       						<th>文件大小</th>
       						<th>操作</th>
       					</tr>
       				</thead>
       				<tbody id="fileList">
       			{foreach from=$fileList item=item}
		       			<tr id="file{$item['id']}">
		       				<td><input type="hidden" name="file_id[]" value="{$item['id']}" /><a target="_blank" href="{resource_url($item['file_url'])}">{$item['title']|escape}</a></td>
		       				<td>{byte_format($item['file_size'])}</td>
							<td>{if $editable}<a data-id="{$item['id']}" class="delLink" href="javascript:void(0);">删除</a>{/if}</td>
						</tr>
       			{/foreach}
       				</tbody>
       			</table>
       		</td>
       	</tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>备注: </label>{form_error('remark')}</td>
        </tr>
        <tr>
        	<td colspan="2"><textarea id="remark" name="remark" style="width:100%;height:300px;visibility:hidden;">{$info['remark']}</textarea></td>
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
  {include file="common/fancybox.tpl"}
  {include file="common/uploadify.tpl"}
  {include file="common/jcrop.tpl"}
  <script type="text/javascript">
  	var province_idcard = {$province_idcard}, remarkEditor, editable = "{$editable}";
  	var cutUrl = '{admin_site_url($moduleClassName|cat:"/pic_cut")}?mod={$moduleClassName}&id={$info['id']}';
  	
  	var uploadUrls = {
  		img :  {
  			uploadUrl : '{admin_site_url($moduleClassName|cat:"/addimg")}?id={$info['id']}',
  			deleteUrl : '{admin_site_url($moduleClassName|cat:"/delimg")}?id={$info['id']}'
  		},
  		file : {
  			uploadUrl : '{admin_site_url($moduleClassName|cat:"/addfile")}?id={$info['id']}',
  			deleteUrl : '{admin_site_url($moduleClassName|cat:"/delfile")}?id={$info['id']}'
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
  <script type="text/javascript" src="{resource_url('js/service/worker.js',true)}"></script>
{include file="common/main_footer.tpl"}