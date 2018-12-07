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
          <td colspan="2"><label class="validation">{#resident_name#}:</label><label class="errtip" id="error_resident_id"></label>{form_error('resident_id')}</td>
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
          <td colspan="2"><label class="validation" for="name">{#parking_name#}: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="{$info['name']|escape}" name="name" id="name" class="txt">
          </td>
          <td class="vatop tips"><label class="errtip" id="error_name"></label>{form_error('name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="type_name">{#parking_type#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="parking_type">
	          <option value="">请选择...</option>
	          {foreach from=$parkingTypeList item=item}
	          <option {if $info['parking_type'] == $item['show_name']}selected{/if} value="{$item['show_name']}">{$item['show_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips"><label id="error_fee_id"></label>{form_error('parking_type	')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="address">{#address#}: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['address']|escape}" name="address" id="address" class="txt"></td>
          <td class="vatop tips"><label id="error_address"></label>{form_error('address')}</td>
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
        {if !$info['id']}
			 <tr class="noborder">
	          <td colspan="2"><label class="validation" for="parking_expire">{#parking_expire#}: </label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">
	          	<input type="text" value="{if $info['expire']}{date('Y-m-d',$info['expire'])}{/if}" name="expire" id="expire" class="datepicker txt">
	          </td>
	          <td class="vatop tips"><label class="errtip"  id="error_expire"></label>{form_error('expire')}</td>
	        </tr>
        {/if}
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

  <script type="text/javascript">
	var submitUrl = [new RegExp("{$uri_string}")],searchAddressUrl = "{admin_site_url('house/getAddress')}";
	
	$(function(){
		$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
		
		bindAjaxSubmit("#infoform");
		
		$( ".datepicker" ).datepicker({
	    	changeYear: true
	    });
	    $( "#address" ).autocomplete({
			source: function( request, response ) {
				
				$.ajax( {
		            url: searchAddressUrl,
		            dataType: "json",
		            data: {
		              term: request.term,
		              resident_id:$("input[name=resident_id]:checked").val(),
		            },
		            success: function( data ) {
		              response( data );
		            }
		          } 
				);
	        },
			minLength: 2,
	    });
	});
  </script>
{include file="common/main_footer.tpl"}