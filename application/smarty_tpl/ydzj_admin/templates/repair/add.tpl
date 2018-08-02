{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {if $info['id']}
  {form_open_multipart(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open_multipart(site_url($uri_string),'id="infoform"')}
  {/if}

    <table class="table tb-type2 mgbottom">
      <tbody>
		<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#repair_type#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="repair_type" id="repair_type">
	          <option value="">请选择...</option>
	          {foreach from=$repairType key=key item=item}
	          <option value="{$key}" {if $info['repair_type'] == $key}selected{/if}>{$item}</option>
              {/foreach}
	        </select>
          </td>
          <td class="vatop tips"><label id="error_repair_type"></label>{form_error('repair_type')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="address">地址:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['address']|escape}" name="address" id="address" class="txt"></td>
          <td class="vatop tips"><label id="error_address"></label>{form_error('address')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#yezhu_name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['yezhu_name']|escape}" name="yezhu_name" id="yezhu_name" class="txt"></td>
          <td class="vatop tips"><label id="error_yezhu_name"></label>{form_error('yezhu_name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">{#mobile#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['mobile']|escape}" name="mobile" id="mobile" class="txt"></td>
          <td class="vatop tips"><label id="error_mobile"></label>{form_error('mobile')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="remark">{#remark#}:</label><label id="error_remark"></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea style="height:150px" name="remark" id="remark">{$info['remark']|escape}</textarea></td>
          <td class="vatop tips">{form_error('remark')}</td>
        </tr>
        
         <tr>
          <td colspan="2" class="required">{#photos#}:<span class="orange">【JPG格式:文件大小2M以内】</span></td>
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

  <script type="text/javascript">
  	var submitUrl = [new RegExp("{$uri_string}")],searchAddressUrl = "{admin_site_url('house/getAddress')}",
		uploadUrl = '{admin_site_url($moduleClassName|cat:"/addimg")}?mod={$moduleClassName}&id={$info['id']}',
		deleteImgUrl = '{admin_site_url($moduleClassName|cat:"/delimg")}?mod={$moduleClassName}';
	
  </script>
  <script type="text/javascript" src="{resource_url('js/wuye/repair.js',true)}"></script>
  <script type="text/javascript" src="{resource_url('js/service/info.js',true)}"></script>
{include file="common/main_footer.tpl"}	