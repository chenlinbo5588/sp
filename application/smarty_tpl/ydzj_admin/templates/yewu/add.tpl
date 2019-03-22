{include file="common/main_header_navs.tpl"}
  {config_load file="user.conf"}
  {if $info['id']}
  {form_open(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open(site_url($uri_string),'id="infoform"')}
  {/if}
</body>

  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="user_type">{#work_category#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="work_category">
	          <option value="">请选择...</option>
	          {foreach from=$workCategory key=key item=item}
	          <option {if $info['work_category'] == $item['id']}selected{/if} value="{$item['id']}">{$key}</option>
	          {/foreach}
	        </select>
          </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="user_type">{#service_area#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="service_area">
	          <option value="">请选择...</option>
	          {foreach from=$serviceArea key=key item=item}
	         <option {if $info['service_area'] == $item['id']}selected{/if} value="{$item['id']}">{$item['show_name']}</option>
	          {/foreach}
	        </select>
          </td>
        </tr> 
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="mobile">联系{#mobile#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['mobile']|escape}" name="mobile" id="mobile" class="txt"></td>
        </tr>
        
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#real_name#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['real_name']|escape}" name="real_name" id="real_name" class="txt"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#address#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['address']|escape}" name="address" id="address" class="txt"></td>
        </tr>
        {if $edit}
	        <tr class="noborder">
	          <td colspan="2" class="required">{#worker_name#}</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform"><input type="text" value="{$info['worker_name']|escape}" name="worker_name" id="worker_name" class="txt"></td>
	        </tr>
	        <tr class="noborder">
	          <td colspan="2" class="required">{#worker_mobile#}</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform"><input type="text" value="{$info['worker_mobile']|escape}" name="worker_mobile" id="worker_mobile" class="txt"></td>
	        </tr>
	        <tr class="noborder">
	          <td colspan="2" class="required">{#user_name#}</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform"><input type="text" value="{$info['user_name']|escape}" name="user_name" id="user_name" class="txt"></td>
	        </tr>
	        <tr class="noborder">
	          <td colspan="2" class="required">{#user_mobile#}</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform"><input type="text" value="{$info['user_mobile']|escape}" name="user_mobile" id="user_mobile" class="txt"></td>
	        </tr>
	        <tr class="noborder">
	          <td colspan="2" class="required">{#company_name#}</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform"><input type="text" value="{$info['company_name']|escape}" name="company_name" id="company_name" class="txt"></td>
	        </tr>
        {/if}
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="remark">{#yewu_describe#}:</label><label id="error_remark"></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea style="height:150px" name="yewu_describe" id="yewu_describe">{$info['yewu_describe']|escape}</textarea></td>
          <td class="vatop tips">{form_error('yewu_describe')}</td>
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
	submitUrl = [new RegExp("{$uri_string}")];
  </script>
  <script>
	  $(function(){
		  	$( ".datepicker" ).datepicker();
			
			$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
			bindAjaxSubmit("#infoform");
			
		});
	</script>
{include file="common/main_footer.tpl"}