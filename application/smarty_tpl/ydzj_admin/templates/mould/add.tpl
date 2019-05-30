{include file="common/main_header_navs.tpl"}
  {config_load file="user.conf"}
  {if $info['id']}
  {form_open(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open(site_url($uri_string),'id="infoform"')}
  {/if}
</body>
<style>
.tarea{
	height: 150px;

}

</style>

  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
    <table class="table tb-type2">
      <tbody>
         
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">模板名称</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" class="txt"></td>
          <td class="vatop tips"><label id="error_name"></label>{form_error('name')}</td>
        </tr>
        
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="style">模板类型</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['type']|escape}" name="type" id="type" class="txt"></td>
          <td class="vatop tips"><label id="error_type"></label>{form_error('type')}</td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label class="validation">模板内容</label>{form_error('content')}</td>
        </tr>
        <tr>
        <td>
		<textarea name="content" rows="6" stylt="width=100%" class="tarea" id="content">{$info['content']|escape}</textarea>
        </td>
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