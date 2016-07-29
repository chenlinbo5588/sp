{include file="common/main_header.tpl"}
{config_load file="stadium.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('stadium/index')}"><span>{#manage#}</span></a></li>
      	<li><a href="{admin_site_url('stadium/add')}" {if !$info['id']}class="current"{/if}><span>新增</span></a></li>
      	{if $info['id']}<li><a href="{admin_site_url('stadium/edit?id=')}{$info['id']}" class="current"><span>编辑</span></a></li>{/if}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {if $info['id']}
  {form_open(admin_site_url('stadium/edit'),'id="add_form"')}
  {else}
  {form_open(admin_site_url('stadium/add'),'id="add_form"')}
  {/if}
  	<input type="hidden" name="id" value="{$info['id']}"/>
  	<input type="hidden" name="longitude" value="{$info['longitude']}"/>
    <input type="hidden" name="latitude" value="{$info['latitude']}"/>
    <input type="hidden" name="province" value="{$info['province']}"/>
    <input type="hidden" name="city" value="{$info['city']}"/>
    <input type="hidden" name="district" value="{$info['district']}"/>
    <input type="hidden" name="street" value="{$info['street']}"/>
    <input type="hidden" name="street_number" value="{$info['streetNumber']}"/>
    <table class="table tb-type2">
      <tbody>
      	<tr class="noborder">
      		<td colspan="2" class="required"><label class="validation">{#title#}{#short_name#}</label></td>
      	</tr>
      	<tr class="noborder">
	        <td class="vatop rowform">
	          	<input type="text" class="txt" value="{$info['name']|escape}" name="name" id="name" placeholder="请输入{#title#}{#short_name#}" class="txt">
	        </td>
	        <td class="vatop tips">{form_error('name')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label>{#title#}{#full_name#}</label></td>
      	</tr>
      	<tr class="noborder">
	        <td class="vatop rowform">
	          	<input type="text" class="txt" value="{$info['full_name']|escape}" name="full_name" id="full_name" placeholder="请输入{#title#}{#full_name#}" class="txt">
	        </td>
	        <td class="vatop tips">{form_error('full_name')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label class="validation">{#title#}{#address#}</label></td>
      	</tr>
        <tr class="noborder">
	        <td class="vatop rowform">
	        
	          	<input type="text" class="txt" value="{$info['address']|escape}" name="address" id="address" placeholder="请输入{#title#}{#address#}" class="txt">
	        </td>
	        <td class="vatop tips"><a href="javascript:void(0);" id="locationOnMap">开始地图标注</a> {form_error('address')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label class="validation">{#owner#}{#mobile#}</label></td>
      	</tr>
        <tr class="noborder">
	        <td class="vatop rowform">
	        
	          	<input type="text" class="txt" value="{$info['mobile']|escape}" name="mobile" id="mobile" placeholder="请输入{#title#}{#mobile#}" class="txt">
	        </td>
	        <td class="vatop tips">{form_error('mobile')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label>{#owner#}备用{#mobile#}</label></td>
      	</tr>
        <tr class="noborder">
	        <td class="vatop rowform">
	          	<input type="text" class="txt" value="{$info['mobile2']|escape}" name="mobile2" id="mobile2" placeholder="请输入{#title#}{#mobile2#}" class="txt">
	        </td>
	        <td class="vatop tips">{form_error('mobile2')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label>{#tel#}</label></td>
      	</tr>
        <tr class="noborder">
	        <td class="vatop rowform">
	          	<input type="text" class="txt" value="{$info['tel']|escape}" name="tel" id="tel" placeholder="请输入{#tel#}" class="txt">
	        </td>
	        <td class="vatop tips">{form_error('mobile')}</td>
        </tr>
      	<tr class="noborder">
      		<td colspan="2" class="required"><label class="validation" >{#category_name#}</label></td>
      	</tr>
      	<tr class="noborder">
	        <td class="vatop rowform">
	            {foreach from=$allMetaList['场地类型'] item=item}
	            <label><input type="checkbox" name="category_name[]" value="{$item['name']}" {if $info['category_name'] == $item['name']}selected{/if}/>{$item['name']}</label>
	            {/foreach}
	        </td>
	        <td class="vatop tips">{form_error('category_name')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label class="validation">{#ground_type#}</label></td>
      	</tr>
      	<tr class="noborder">
	        <td class="vatop rowform">
	          	{foreach from=$allMetaList['地面材质'] item=item}
	            <label><input type="checkbox" name="ground_type[]" value="{$item['name']}" {if $info['ground_type'] == $item['name']}selected{/if}/>{$item['name']}</label>
	            {/foreach}
	        </td>
	        <td class="vatop tips">{form_error('ground_type')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label class="validation">{#open_type#}</label></td>
      	</tr>
      	<tr class="noborder">
	        <td class="vatop rowform">
	          	{foreach from=$allMetaList['开放类型'] item=item}
	            <label><input type="radio" name="open_type" value="{$item['name']}" {if $info['open_type'] == $item['name']}selected{/if}/>{$item['name']}</label>
	            {/foreach}
	        </td>
	        <td class="vatop tips">{form_error('open_type')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label class="validation">{#owner_type#}</label></td>
      	</tr>
      	<tr class="noborder">
	        <td class="vatop rowform">
	          	{foreach from=$allMetaList['权属类型'] item=item}
	            <label><input type="radio" name="owner_type" value="{$item['name']}" {if $info['open_type'] == $item['name']}selected{/if}/>{$item['name']}</label>
	            {/foreach}
	        </td>
	        <td class="vatop tips">{form_error('owner_type')}</td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label class="validation">{#support_sports#}</label></td>
      	</tr>
        <tr class="noborder">
	        <td class="vatop rowform">
	          	{foreach from=$allMetaList['权属类型'] item=item}
	            <label><input type="checkbox" name="support_sports" value="{$item['name']}" {if $info['open_type'] == $item['name']}selected{/if}/>{$item['name']}</label>
	            {/foreach}
	        </td>
	        <td class="vatop tips">{form_error('owner_type')}</td>
        </tr>
        
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#meta_title#}名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" maxlength="20" class="txt"></td>
          <td class="vatop tips">{form_error('name')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required">开启状态: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="status1" {if $info['status'] == 1}class="cb-enable selected"{else}class="cb-enable"{/if}><span>是</span></label>
            <label for="status0" {if $info['status'] == 1}class="cb-disable"{else}class="cb-disable selected"{/if}><span>否</span></label>
            <input id="status1" name="status" {if $info['status'] == 1}checked{/if} value="1" type="radio">
            <input id="status0" name="status" {if $info['status'] == 0}checked{/if} value="0" type="radio"></td>
          <td class="vatop tips">{form_error('status')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{if $info['meta_sort']}{$info['meta_sort']}{else}255{/if}" name="meta_sort" id="meta_sort" class="txt"></td>
          <td class="vatop tips">{form_error('meta_sort')} 数字范围为0~255，数字越小越靠前</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
  <script>
  $(function(){
  	$("select[name=category_name]").change(function(){
  		var currentCate = $(this).val();
  		$.get("{admin_site_url('sports_meta/getgroup')}", { category_name: currentCate , ts: Math.random() } ,function(json){
  			if(json.message == 'success'){
  				$("select[name=gname").html('<option value="">请选择</option>' );
  				for(var i = 0; i < json['data'].length; i++) {
  					
  					$("select[name=gname").append('<option value="' + json['data'][i].gname + '">' + json['data'][i].gname + '</option>'); 
  				}
  			}
  		
  		})
  	});
  	
  	
  })
  </script>
{include file="common/main_footer.tpl"}