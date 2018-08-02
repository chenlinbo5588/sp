{include file="common/main_header_navs.tpl"}
  {config_load file="order.conf"}
  {form_open_multipart(admin_site_url($moduleClassName|cat:"/batch_verify"|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
    <table class="tb-type2 mgbottom">
   		<tr>
   		  <td class="required">{#order_id#}: </td>
          <td class="vatop rowform">{$info['order_id']|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#name#}: </td>
          <td class="vatop rowform">{$info['add_username']|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#mobile#}: </td>
          <td class="vatop rowform">{$info['mobile']|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#ref_order#}: </td>
          <td class="vatop rowform">{$info['ref_order']}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#order_typename#}: </td>
          <td class="vatop rowform">{$info['order_typename']}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#pay_channel#}: </td>
          <td class="vatop rowform">{$info['pay_channel']|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#pay_method#}: </td>
          <td class="vatop rowform">{$info['pay_method']|escape}</td>      
       	</tr>
      	<tr>
   		  <td class="required">{#goods_name#}: </td>
          <td class="vatop rowform">{$info['goods_name']|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#amount#}: </td>
          <td class="vatop rowform">{$info['amount']/100|escape}</td>      
       	</tr>
       	<tr>
       	<tr>
   		  <td class="required">{#refund_amount#}: </td>
          <td class="vatop rowform">{$info['refund_amount']/100|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#refund_cnt#}: </td>
          <td class="vatop rowform">{$info['refund_cnt']|escape}</td>      
       	</tr>
   		  <td class="required">{#status#}: </td>
          <td class="vatop rowform">{$OrderStatus[$info['status']]|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#order_old#}: </td>
          <td class="vatop rowform">{$info['order_old']|escape}</td>      
       	</tr>
       	
       	<tr>
   		  <td class="required">{#ip#}: </td>
          <td class="vatop rowform">{$info['ip']|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#extra_info#}: </td>
          <td class="vatop rowform">{$item['extra_info']|escape}</td>      
       	</tr>

       	<tr class="noborder">
    		<td  class="required">退款原因:</td>
	    	<td>
	    		<select name="reason">
		    	<option value="">请选择...</option>
		    		{foreach from=$list item=info}
		         		<option  value="{$info['show_name']}">{$info['show_name']}</option>
		        	 {/foreach}
		     	</select>
	    </td>
       	<tr>
			<td colspan="2" class="required">备注</td>
		</tr>
      	<tr>
			<td colspan="2">
				<textarea name="remark" style="width:100%;height:100px;"></textarea>
			</td>
		</tr>
		
    </table>
    <div class="fixedOpBar">
    	<input type="submit" name="tijiao" value="审核通过" class="msbtn"/>
    	<input type="submit" name="tijiao" value="退回" class="msbtn"/>
    	{if $gobackUrl}
    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
    	{/if}
    </div>
   
  </form>
  <div id="avatarDlg"></div>
  
  <script type="text/javascript">
	var submitUrl = [new RegExp("{$uri_string}")];
	
	$(function(){
		$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
		bindAjaxSubmit("#infoform");
	});
	
		
  </script>
{include file="common/main_footer.tpl"}