{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {if $info['id']}
  {form_open(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open(site_url($uri_string),'id="infoform"')}
  {/if}
  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
    <table class="table tb-type2">
      <tbody>
         <tr class="noborder">
          <td colspan="2"><label class="validation">{#resident_name#}:</label>{form_error('resident_id')}</td>
        </tr>
        <tr class="noborder">
        	<td colspan="2">
	          	<ul class="ulListStyle1 clearfix">
	          	{foreach from=$residentList item=item}
	          		<li {if $info['resident_id'] == $item['id']}class="selected"{/if}><label><input type="radio" name="resident_id" value="{$item['id']}" {if $info['resident_id'] == $item['id']}checked="checked"{/if}/><span>{$item['name']|escape}</span></label></li>
	          	{/foreach}
	          	</ul>
	         </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="type_name">{#type_name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="name">
	          <option value="">请选择...</option>
	          {foreach from=$feeNameList item=item}
	          <option {if $info['name'] == $item['show_name']}selected{/if} value="{$item['show_name']}">{$item['show_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips"><label id="error_fee_id"></label>{form_error('name')}</td>
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
          <td class="vatop tips"><label id="error_fee_id"></label>{form_error('biliing_type')}</td>
        </tr>
    	<tr class="noborder">
          <td colspan="2"><label class="validation" for="year">{#year#}: </label></td>
        </tr>
   		<tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="{$info['year']|escape}" name="year" id="year" class="txt">
          </td>
          <td class="vatop tips"><label id="error_fee_year"></label>{form_error('year')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="price">{#price#}: </label></td>
        </tr>
   		<tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="{$info['price']|escape}" name="price" id="price" class="txt">
          </td>
          <td class="vatop tips"><label id="error_price"></label>{form_error('price')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="price">{#billing_style#}: </label></td>
        </tr>
   		<tr class="noborder">
          <td class="vatop rowform">
          	<select name="billing_style">
	          <option value="">请选择...</option>
	          {foreach from=$billingStyleList item=item}
	          <option {if $info['billing_style'] == $item['show_name']}selected{/if} value="{$item['show_name']}">{$item['show_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips"><label id="error_fee_type"></label>{form_error('fee_type')}</td>
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
  <script type="text/javascript">
	var submitUrl = [new RegExp("{$uri_string}")];
	
	$(function(){
		$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
		bindAjaxSubmit("#infoform");
	});
  </script>
{include file="common/main_footer.tpl"}