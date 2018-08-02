{form_open(site_url($uri_string),'id="refundForm"')}
	<input type="hidden" name="id" value="{$info['id']}"/>
	{config_load file="order.conf"}
	<table class="table">
		<tbody>
			<tr>
	   		  <td>{#order_typename#}: </td>
	          <td >{$info['order_typename']|escape}</td>      
       		</tr>
       		<tr>
	   		  <td>{#name#}: </td>
	          <td >{$info['add_username']|escape}</td>      
       		</tr>
       		<tr>
	   		  <td>{#amount#}: </td>
	          <td>{$info['amount']/100}</td>      
       		</tr>
       		<tr>
	   		  <td>{#refunding_amount#}: </td>
	          <td>{$info['amount']/100}</td>      
       		</tr>
       		<tr>
	   		  <td>{#order_id#}: </td>
	          <td>{$info['order_id']}</td>      
       		</tr>
        	<tr class="noborder">
        		<td>退款原因</td>
		        <td>
		          	<select name="reason">
			          <option value="">请选择...</option>
			          {foreach from=$reasonList item=info}
			          <option  value="{$info['show_name']}">{$info['show_name']}</option>
			          {/foreach}
			        </select>
		         </td>
			<tr>
				<td colspan="2">备注</td>
			</tr>
			<tr>
				<td colspan="2">
					<textarea name="remark" style="width:100%;height:100px;"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" class="msbtn" name="verifyOK" value="创建退款单 "/>  
				</td>
			</tr>
		</tbody>
	</table>
</form>