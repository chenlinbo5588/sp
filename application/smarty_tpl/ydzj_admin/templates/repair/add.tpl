{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {if $info['id']}
  {form_open_multipart(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open_multipart(site_url($uri_string),'id="infoform"')}
  {/if}
  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
  <input type="hidden" name="lng" value="{$info['lng']}"/>
  <input type="hidden" name="lat" value="{$info['lat']}"/>
    <table class="table tb-type2">
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
        
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="hidden" name="old_pic" value="{if $info['photos']}{$info['photos']}{/if}"/>
          	<span class="type-file-show">
          		<img class="show_image" src="{resource_url('img/preview.png')}">
          		<div class="type-file-preview">{if !empty($info['photos'])}<img src="{resource_url($info['photos'])}">{/if}</div>
            </span>
            <span class="type-file-box"><input type='text' name='photos' value="{if $info['photos']}{$info['photos']}{/if}" id='photos' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="repair_logo" type="file" class="type-file-file" id="repair_logo" size="30" hidefocus="true" nc_type="change_repair_logo">
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">品牌LOGO尺寸要求宽度为150像素，高度为50像素、比例为3:1的图片；支持格式gif,jpg,png</span></td>
        </tr>
        
       	<tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="submit" name="tijiao" value="保存" class="msbtn"/>
          	{if $gobackUrl}
	    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
	    	{/if}
          </td>
        </tr>
      </tbody>
    </table>
  </form>
  <script type="text/javascript">
  	var submitUrl = [new RegExp("{$uri_string}")],searchAddressUrl = "{admin_site_url('house/getAddress')}";

	$(function(){
		$("#repair_logo").change(function(){
			$("#photos").val($(this).val());
		});
	})

  </script>
  <script type="text/javascript" src="{resource_url('js/wuye/repair.js',true)}"></script>
{include file="common/main_footer.tpl"}