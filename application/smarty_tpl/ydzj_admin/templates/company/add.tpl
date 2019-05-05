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
          <td colspan="2" class="required"><label class="validation" for="name">{#company_name#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" class="txt"></td>
          <td class="vatop tips"><label id="error_name"></label>{form_error('name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="corporation">{#corporation#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['corporation']|escape}" name="corporation" id="corporation" class="txt"></td>
          <td class="vatop tips"><label id="error_corporation"></label>{form_error('corporation')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="contact_number">{#contact_number#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['contact_number']|escape}" name="contact_number" id="contact_number" class="txt"></td>
          <td class="vatop tips"><label id="error_contact_number"></label>{form_error('contact_number')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="mobile">{#business_license#}</label></td>
        </tr>
        <tr class="noborder">
            <td class="w120 picture"><a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}"><img class="size-100x100" src="{if $item['business_license']}{resource_url($item['business_license'])}{else}{resource_url('img/default.jpg')}{/if}"/></a></td>
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
  <script type="text/javascript" src="{resource_url('js/service/yezhu.js',true)}"></script>
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