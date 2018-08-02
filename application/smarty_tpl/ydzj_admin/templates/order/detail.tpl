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
          <td class="vatop rowform">{$info['order_typename']|escape}</td>      
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
   		  <td class="required">{#refund_amount#}: </td>
          <td class="vatop rowform">{$info['refund_amount']/100|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#refund_cnt#}: </td>
          <td class="vatop rowform">{$info['refund_cnt']/100|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#status#}: </td>
          <td class="vatop rowform">{$OrderStatus[$info['status']]|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#order_time#}: </td>
          <td class="vatop rowform">{$info['gmt_create']|escape|date_format:"%Y-%m-%d %H:%M:%S"}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#time_expire#}: </td>
          <td class="vatop rowform">{$info['time_expire']|escape|date_format:"%Y-%m-%d %H:%M:%S"}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#pay_time_end#}: </td>
          <td class="vatop rowform">{$info['pay_time_end']|escape|date_format:"%Y-%m-%d %H:%M:%S"}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#ip#}: </td>
          <td class="vatop rowform">{$info['ip']|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#extra_info#}: </td>
          <td class="vatop rowform">{$item['extra_info']|escape}</td>      
       	</tr>
       
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