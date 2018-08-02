{form_open(site_url($uri_string),'id="verifyForm"')}
	<input type="hidden" name="id" value="{$id}"/>
	<table class="table">
		<tbody>
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
        	</tr>
			<tr>
				<td colspan="2">备注</td>
			</tr>
			<tr>
				<td colspan="2" >
					<textarea name="remark" style="width:100%;height:100px;"></textarea>
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" class="msbtn" name="verifyOK" value="审核通过"/>  
					<input type="submit" class="salvebtn" name="verifyFail" value="退回"/>  
				</td>
			</tr>
		</tbody>
	</table>
</form>