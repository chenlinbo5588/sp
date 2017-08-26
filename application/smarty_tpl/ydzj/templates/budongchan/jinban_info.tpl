		        		<tr>
		        			<td><label>当前经办单位</label></td>
		        			<td><strong class="hightlight">{$info['cur_dept_sname']|escape}</strong></td>
		        		</tr>
		        		<tr>
		        			<td><label>当前经办人</label></td>
		        			<td><strong class="hightlight">{if $info['cur_uid']}{$info['cur_username']|escape}{else}未受理{/if}</strong></td>
		        		</tr>