				   		<tr>
				   			<td>发送至</td>
				   			<td>
				   				<ul class="senderList clearfix">
				   					{foreach from=$orgList item=item}
				   					<li><label><input type="radio" name="to_dept" value="{$item['id']}"/>&nbsp;{$item['name']|escape}</label></li>
				   					{/foreach}
				   				</ul>
				   				<div class="errtip" id="error_to_dept">{form_error('to_dept')}</div>
				   			</td>
				   		</tr>
		        	

