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
        {include file="common/yezhu_add.tpl"}
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="submit" name="tijiao" value="确定" class="msbtn"/>
          	{if $gobackUrl}
	    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
	    	{/if}
          </td>
        </tr>
      </tbody>
    </table>
  </form>
  <script>
  	var submitUrl = [new RegExp("{$uri_string}")],searchYezhuUrl = "{admin_site_url('yezhu/getYezhuInfo?resident_id=')}{$info['resident_id']}";
  	
  </script>
    <script type="text/javascript" src="{resource_url('js/wuye/yezhu_change.js',true)}"></script>
{include file="common/main_footer.tpl"}