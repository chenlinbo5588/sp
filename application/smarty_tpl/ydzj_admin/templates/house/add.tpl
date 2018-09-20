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
          <td colspan="2"><label class="validation">所在{#resident_name#}:</label><label class="errtip" id="error_resident_id"></label>{form_error('resident_id')}</td>
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
          <td colspan="2"><label class="validation">所在{#building_name#}:</label><label class="errtip" id="error_building_id"></label>{form_error('building_id')}</td>
        </tr>
        <tr class="noborder">
        	<td colspan="2">
        		<select name="building_id">
        			<option value="">请选择</option>
        			{foreach from=$buildingList item=item}
        			<option value="{$item['id']}" {if $info['building_id'] == $item['id']}selected{/if}>{$item['name']|escape}</option>
        			{/foreach}
        		</select>
	         </td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="address">{#address#}: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="{$info['address']|escape}" name="address" id="address" class="txt">
          </td>
          <td class="vatop tips"><label class="errtip" id="error_address"></label>{form_error('address')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="type_name">{#wuye_type#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="wuye_type">
	          <option value="">请选择...</option>
	          {foreach from=$wuyeTypeList item=item}
	          <option {if $info['wuye_type'] == $item['show_name']}selected{/if} value="{$item['show_name']}">{$item['show_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips"><label id="error_fee_id"></label>{form_error('name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="jz_area">{#jz_area#}: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="{$info['jz_area']|escape}" name="jz_area" id="jz_area" class="txt">
          </td>
          <td class="vatop tips"><label class="errtip"  id="error_jz_area"></label>{form_error('jz_area')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label for="wuye_expire">{#wuye_expire#}: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="{if $info['wuye_expire']}{date('Y-m-d',$info['wuye_expire'])}{/if}" name="wuye_expire" id="wuye_expire" class="datepicker txt">
          </td>
          <td class="vatop tips"><label class="errtip"  id="error_wuye_expire"></label>{form_error('wuye_expire')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label for="wuye_expire">{#nenghao_expire#}: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="{if $info['nenghao_expire']}{date('Y-m-d',$info['nenghao_expire'])}{/if}" name="nenghao_expire" id="nenghao_expire" class="datepicker txt">
          </td>
          <td class="vatop tips"><label class="errtip"  id="error_nenghao_expire"></label>{form_error('nenghao_expire')}</td>
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
	        	$.getJSON("{admin_site_url('building/getBuildingList')}?t=" + Math.random(),{ resident_id : id }, function(resp){
	        		
	        		
	        		$("select[name=building_id]").html('');
	        		var data = resp.data;
	        	
	        		if(data.length == 0){
	        			showToast('error',resp.message);
	        		}else{
	        			$("select[name=building_id]").append('<option value="">请选择</option>');
	        			for(var i = 0 ; i < data.length; i++){
		        			$("select[name=building_id]").append('<option value="' + data[i]['id'] + '">' + data[i]['name'] + '</option>');
		        		}
	        		}
	        		
				});
				
	        	mapPanTo(id);
	        }
	    });
	    
	    
	    {if empty($info['id'])}
	    $("select[name=building_id]").bind("change",function(){
	    	var addresVal = $("#address").val();
	    	
	    	$("#address").val($(this).find("option:selected").html());
	    });
	    {/if}
	});
  </script>
  <script type="text/javascript" src="{resource_url('js/service/wuye.js',true)}"></script>
{include file="common/main_footer.tpl"}