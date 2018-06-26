{include file="common/main_header.tpl"}
  {include file="common/sub_nav.tpl"}
  {config_load file="wuye.conf"}
  {if $info['id']}
  {form_open_multipart(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open_multipart(site_url($uri_string),'id="infoform"')}
  
  {/if}
  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
    <table class="table tb-type2 mgbottom">
      <tbody>
      	
      	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#resident_name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" class="txt"></td>
          <td class="vatop tips">{form_error('name')}</td>
        </tr>
        
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="address">{#address#}: </label>{form_error('address')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['address']|escape}" name="address" id="address" class="txt"></td>
          <td class="vatop tips">{form_error('address')}</td>
        </tr>
        
        <tr>
        
        
        </tr>
        
      </tbody>
    </table>
    <div class="fixedBar">
    	<input type="submit" name="tijiao" value="保存" class="msbtn"/>
    	{if $gobackUrl}
    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
    	{/if}
    </div>
  </form>
  
  <script type="text/javascript">
  	
	{if $successMessage}
		showToast('success','{$successMessage}')
	{/if}
	
	{if $redirectUrl}
		setTimeout(function(){
			location.href="{$redirectUrl}";
		},2000);
	{/if}
		
  </script>
{include file="common/main_footer.tpl"}