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
          <td colspan="2"><label class="validation">{#resident_name#}:</label></td>
        </tr>
        <tr class="noborder">
        	<td colspan="2">
        		{if $info['id']}
	          	{$residentList[$info['resident_id']]['name']|escape}
	          	{else}
	          	<ul class="ulListStyle1 clearfix">
	          	{foreach from=$residentList item=item}
	          		<li {if $info['resident_id'] == $item['id']}class="selected"{/if}><label><input type="radio" name="resident_id" {if $info['resident_id'] == $item['id']}checked="checked"{/if} value="{$item['id']}"/><span>{$item['name']|escape}</span></label></li>
	          	{/foreach}
	          	</ul>
	          	{/if}
	         </td>
        </tr>
      	<tr class="noborder">
          <td colspan="2"><label class="validation" for="name">{#building_name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" class="txt"></td>
          <td class="vatop tips"><label id="error_name"></label>{form_error('name')} 例如: 吉祥新村21号楼  </td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label for="name">{#building_nickname#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['nickname']|escape}" name="nickname" id="nickname" class="txt"></td>
          <td class="vatop tips">{form_error('nickname')} 例如:望月楼</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="address">{#address#}: </label>{form_error('address')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="{$info['address']|escape}" name="address" id="address" class="txt">
          	<input type="button" name="autoFillAddress" value="自动填入"/>
          </td>
          <td class="vatop tips"><label id="error_address"></label>{form_error('address')} 请通过地图选取位置</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label for="unit_num">{#building#}{#unit_num#}: </label>{form_error('unit_num')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['unit_num']|escape}" name="unit_num" id="unit_num" class="txt"></td>
          <td class="vatop tips">{form_error('unit_num')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label for="yezhu_num">{#yezhu_num#}: </label>{form_error('yezhu_num')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['yezhu_num']|escape}" name="yezhu_num" id="yezhu_num" class="txt"></td>
          <td class="vatop tips">{form_error('yezhu_num')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label for="total_num">{#total_num#}: </label>{form_error('total_num')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['total_num']}" name="total_num" id="total_num" class="txt"></td>
          <td class="vatop tips">{form_error('total_num')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label>{#max_plies#}: </label>{form_error('max_plies')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['max_plies']|escape}" name="max_plies" id="max_plies" class="txt"></td>
          <td class="vatop tips">{form_error('max_plies')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label>{#floor_plies#}: </label>{form_error('floor_plies')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['floor_plies']|escape}" name="floor_plies" id="floor_plies" class="txt"></td>
          <td class="vatop tips">{form_error('floor_plies')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
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
	var map,initZoom = 16, submitUrl = [new RegExp("{$uri_string}")],residentJson = {$residentJson};
	
	{if $info['lng'] && $info['lat']}
	var centerData = [{$info['lng']},{$info['lat']}];
	{/if}
	
	function mapPanTo(id){
		map.panTo(new AMap.LngLat(residentJson[id]['lng'],residentJson[id]['lat']));
	}
	
	$(function(){
		$("input[name=resident_id]").bind('click',function(){
	        var checked = $(this).prop('checked');
	        var id = $(this).val();
	        if(checked){
	        	//$("input[name=name]").val($(this).parent().find('span').html());
	        	
	        	mapPanTo(id);
	        }
	    });
	    
	});
  </script>
  <script type="text/javascript" src="{resource_url('js/service/wuye.js',true)}"></script>
{include file="common/main_footer.tpl"}