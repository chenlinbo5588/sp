{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {if $info['id']}
  {form_open_multipart(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open_multipart(site_url($uri_string),'id="infoform"')}
  {/if}
	<input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
    <table class="table tb-type2 mgbottom">
      <tbody>
		<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#repair_type#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="repair_type" >
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
          <td colspan="2" class="required">{#yezhu_name#}:</td>
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
          <td colspan="2" class="required"><label class="validation" for="name">{#status#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="status" >
	          <option value="">请选择...</option>
	          {foreach from=$repairStatus key=key item=item}
	          <option value="{$key}" {if $item == '已受理' && empty($info['status'])} selected {else if $info['status'] == $key} selected{/if}>{$item}</option>
              {/foreach}
	        </select>
          </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#worker_name#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['worker_name']|escape}" name="worker_name" id="worker_name" class="txt"></td>
          <td class="vatop tips"><label id="error_worker_name"></label>{form_error('worker_name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#worker_mobile#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['worker_mobile']|escape}" name="worker_mobile" id="worker_mobile" class="txt"></td>
          <td class="vatop tips"><label id="error_worker_mobile"></label>{form_error('worker_mobile')}</td>
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
						<span><a class="delLink" data-id="{$item['id']}" href="javascript:void(0);">删除</a></span>
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
  {include file="common/uploadify.tpl"}
  {include file="common/fancybox.tpl"}
  <script type="text/javascript">
  	var submitUrl = [new RegExp("{$uri_string}")],searchAddressUrl = "{admin_site_url('house/getAddress')}";
  	
	var uploadUrls = {
  		img :  {
  			uploadUrl : '{admin_site_url($moduleClassName|cat:"/addimg")}?id={$info['id']}',
  			deleteUrl : '{admin_site_url($moduleClassName|cat:"/delimg")}?id={$info['id']}'
  		}
  	};
  </script>
  <script type="text/javascript" src="{resource_url('js/wuye/repair.js',true)}"></script>
{include file="common/main_footer.tpl"}	