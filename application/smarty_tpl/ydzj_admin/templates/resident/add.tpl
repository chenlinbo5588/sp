{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {if $info['id']}
  {form_open(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open(site_url($uri_string),'id="infoform"')}
  {/if}
  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
  <input type="hidden" name="lng" value="{$info['lng']}"/>
  <input type="hidden" name="lat" value="{$info['lat']}"/>
    <table class="table tb-type2">
      <tbody>
      	<tr class="noborder">
          <td colspan="2"><label class="validation" for="name">{#resident_name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" class="txt"></td>
          <td class="vatop tips"><label class="errtip" id="error_name"></label>{form_error('name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="address">{#address#}: </label>{form_error('address')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="{$info['address']|escape}" name="address" id="address" class="txt">
          	<input type="button" name="autoFillAddress" value="自动填入"/>
          </td>
          <td class="vatop tips"><label class="errtip" id="error_address"></label>{form_error('address')} 请通过地图选取位置</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="yezhu_cnt">{#yezhu_num#}: </label>{form_error('yezhu_num')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['yezhu_num']}" name="yezhu_num" id="yezhu_num" class="txt"></td>
          <td class="vatop tips"><label class="errtip" id="error_yezhu_num"></label>{form_error('yezhu_num')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="total_num">{#total_num#}: </label>{form_error('total_num')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['total_num']}" name="total_num" id="total_num" class="txt"></td>
          <td class="vatop tips"><label class="errtip" id="error_total_num"></label>{form_error('total_num')}</td>
        </tr>
        
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="vacant_discount">{#vacant_discount#}: </label>{form_error('vacant_discount')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['vacant_discount']}" name="vacant_discount" id="vacant_discount" class="txt"></td>
          <td class="vatop tips"><label class="errtip" id="error_total_num"></label>{form_error('vacant_discount')} 输入1~100的整数，80代表八折,100表示不打折</td>
        </tr>
  
        <tr>
          <td colspan="2"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{if $info['displayorder']}{$info['displayorder']}{else}255{/if}" name="displayorder" id="displayorder" class="txt"></td>
          <td class="vatop tips">{form_error('displayorder')} 数字范围为0~255，数字越小越靠前</td>
        </tr>
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
  
  <div id="container" style="float:left;width:74%;height:600px;"></div>
  {include file="common/map_loc.tpl"}
  {include file="common/gaode_map.tpl"}
  <script type="text/javascript">
	var map,initZoom = 13, submitUrl = [new RegExp("{$uri_string}")];
	{if $info['lng'] && $info['lat']}
	var centerData = [{$info['lng']},{$info['lat']}];
	initZoom = 16;
	{/if}
  </script>
  <script type="text/javascript" src="{resource_url('js/service/wuye.js',true)}"></script>
{include file="common/main_footer.tpl"}