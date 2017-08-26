		        		{if $info['lsno']}
		        		<tr>
		        			<td class="w120">流水号</td>
		        			<td><span class="lsno">{$info['lsno']}</span></td>
		        		</tr>
		        		{/if}
		        		<tr>
		        			<td class="w120"><label class="required">{#bdc_name#}</label></td>
		        			<td>{$info['name']|escape}</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_address#}</label></td>
		        			<td>{$info['address']|escape}</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_contact_name#}</label></td>
		        			<td>{$info['contactor']|escape}</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_mobile#}</label></td>
		        			<td>{$info['mobile']|escape}</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_id_type#}</label></td>
		        			<td>{$id_type[$info['id_type']]}</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_id_no#}</label></td>
		        			<td>{$info['id_no']|escape}</td>
		        		</tr>
		        		<tr>
		        			<td class="vtop"><label>{#cm_remark#}</label></td>
		        			<td>{$info['bz']|escape}</td>
		        		</tr>
		        		{include file="./jinban_info.tpl"}