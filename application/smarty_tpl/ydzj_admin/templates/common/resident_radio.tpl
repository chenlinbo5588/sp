  	        <tr class="noborder">
	          <td colspan="2"><label class="validation">{#resident_name#}:</label>{form_error('resident_id')}</td>
	        </tr>
	        <tr class="noborder">
	        	<td colspan="2">
		          	<ul class="ulListStyle1 clearfix">
		          	{foreach from=$residentList item=item}
		          		<li><label><input type="radio" name="resident_id" value="{$item['id']}"/><span>{$item['name']|escape}</span></label></li>
		          	{/foreach}
		          	</ul>
		         </td>
	        </tr>