{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {if $info['id']}
  {form_open(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open(site_url($uri_string),'id="infoform"')}
  {/if}
  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
  <input type="hidden" name="yezhu_id" value=""/>
    <table class="table tb-type2">
      <tbody>

        <tr class="noborder">
          <td colspan="2" class="required">{#address#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" readonly value="{$info['address']|escape}" name="address" id="address" class="txt"></td>
          <td class="vatop tips">{form_error('address')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">原{#yezhu_name#}: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text"  value="{$info['yezhu_name']|escape}" readonly name="yuan_yezhu_name" id="yuan_yezhu_name" class="txt">
          </td>
          <td class="vatop tips"><label class="errtip"   id="error_yuan_yezhu_name"></label>{form_error('yuan_yezhu_name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">原{#mobile#}: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="{$info['mobile']|escape}"} readonly name="old_mobile" id="old_mobile" class="txt">
          </td>
          <td class="vatop tips"><label class="errtip"  id="error_old_mobile"></label>{form_error('old_mobile')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="mobile">{#mobile#}: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="" name="mobile" id="mobile" class="txt">
          </td>
          <td class="vatop tips"><label class="errtip"  id="error_mobile"></label>{form_error('mobile')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="yezhu_name">新{#yezhu_name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text"  value="" name="yezhu_name" id="yezhu_name" class="txt"></td>
          <td class="vatop tips">{form_error('yezhu_name')}</td>
        </tr>


        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="submit" name="tijiao" value="变更" class="msbtn"/>
          	{if $gobackUrl}
	    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
	    	{/if}
          </td>
        </tr>
      </tbody>
    </table>
  </form>
  <script>
  	var submitUrl = [new RegExp("{$uri_string}")],searchYezhuUrl = "{admin_site_url('house/getYezhuInfo')}";
  	
  </script>
    <script type="text/javascript" src="{resource_url('js/wuye/yezhu_change.js',true)}"></script>
{include file="common/main_footer.tpl"}