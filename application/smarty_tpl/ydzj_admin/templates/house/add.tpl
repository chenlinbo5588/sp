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
          <td colspan="2"><label class="validation">所在{#resident_name#}:</label></td>
        </tr>
        <tr class="noborder">
        	<td colspan="2">
	          	<ul class="ulListStyle1 clearfix">
	          	{foreach from=$residentList item=item}
	          		<li {if $info['resident_id'] == $item['id']}class="selected"{/if}><label><input type="radio" name="resident_id" {if $info['resident_id'] == $item['id']}checked="checked"{/if} value="{$item['id']}"/><span>{$item['name']|escape}</span></label></li>
	          	{/foreach}
	          	</ul>
	         </td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation">所在{#building_name#}:</label></td>
        </tr>
        <tr class="noborder">
        	<td colspan="2">
	          	<ul class="ulListStyle1 clearfix">
	          	{foreach from=$buildingList item=item}
	          		<li {if $info['building_id'] == $item['id']}class="selected"{/if}><label><input type="radio" name="building_id" {if $info['building_id'] == $item['id']}checked="checked"{/if} value="{$item['id']}"/><span>{$item['name']|escape}</span></label></li>
	          	{/foreach}
	          	</ul>
	         </td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="address">{#address#}: </label>{form_error('address')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="{$info['address']|escape}" name="address" id="address" class="txt">
          </td>
          <td class="vatop tips"><label id="error_address"></label>{form_error('address')}</td>
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
	        
	        	
	        
	        	$("input[name=name]").val($(this).parent().find('span').html());
	        	
	        	mapPanTo(id);
	        }
	    });
	    
	    
	    
	});
  </script>
  <script type="text/javascript" src="{resource_url('js/service/wuye.js',true)}"></script>
{include file="common/main_footer.tpl"}