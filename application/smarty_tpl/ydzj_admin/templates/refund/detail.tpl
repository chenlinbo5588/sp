{include file="common/main_header_navs.tpl"}
  {config_load file="order.conf"}
  {form_open(admin_site_url($moduleClassName|cat:"/verify"|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
    <table class="tb-type2 mgbottom">
   		<tr>
   		  <td class="required">{#order_id#}: </td>
          <td class="vatop rowform">{$info['order_id']|escape}</td>      
       	</tr>
       	{include file="order/detail_common.tpl"}
       	{if $showSubmit}
       	<tr>
			<td colspan="2"><label class="validation">备注:</label><label class="errtip" id="error_remark"></label></td>
		</tr>
      	<tr>
			<td colspan="2">
				<textarea name="remark" style="width:100%;height:100px;"></textarea>
			</td>
		</tr>
		{else}
		<tr>
			<td class="required">审核备注: </td>
			<td>{$info['extra_info']['verify_remark']|escape}</td>
		</tr>
		{/if}
    </table>
    <div class="fixedOpBar">
    	{if $showSubmit}
    	<input type="submit" name="tijiao" value="审核通过" class="msbtn"/>
    	<input type="submit" name="tijiao" value="退回" class="msbtn"/>
    	{/if}
    	{if $lastUrl}
    	<a href="{$lastUrl}" class="salvebtn">返回</a>
    	{/if}
    </div>
  </form>
  <div id="avatarDlg"></div>
  
  {if $showSubmit}
  <script type="text/javascript">
	var submitUrl = [new RegExp("{admin_site_url($moduleClassName|cat:"/verify")}")];
	
	$(function(){
		$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
		bindAjaxSubmit("#infoform");
	});
  </script>
  {/if}
{include file="common/main_footer.tpl"}