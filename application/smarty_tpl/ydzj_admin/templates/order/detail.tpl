{include file="common/main_header_navs.tpl"}
  {config_load file="order.conf"}
  {form_open_multipart(admin_site_url($moduleClassName|cat:"/edit"|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
  <input type="hidden" name="id" value="{$info['id']}"/>
    <table class="tb-type2 mgbottom">
   		<tr>
   		  <td class="required">{#order_id#}: </td>
          <td class="vatop rowform">{$info['order_id']|escape}</td>      
       	</tr>
       	{include file="order/detail_common.tpl"}
    </table>
    <div class="fixedOpBar">
    	<input type="submit" name="tijiao" data-url="{admin_site_url($moduleClassName|cat:'/single_close')}" value="关闭" data-title="确定要关闭吗?" class="msbtn"/>
    	<input type="submit" name="tijiao" data-url="{admin_site_url($moduleClassName|cat:'/single_delete')}" value="删除" data-title="确定要删除吗?" class="msbtn"/>
    	{if $gobackUrl}
    	<a href="{$gobackUrl}" class="salvebtn" style>返回</a>
    	{/if}
    </div>
  </form>
   <script type="text/javascript">
	var submitUrl = [new RegExp("{$uri_string}")];
	
	$(function(){
		$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
		bindAjaxSubmit("#infoform", { showComfirm : true });
	});
	
		
  </script>
{include file="common/main_footer.tpl"}