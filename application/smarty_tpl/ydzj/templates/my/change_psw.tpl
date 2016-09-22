{include file="./my_header.tpl"}
	{form_open(site_url($uri_string),"id='editForm'")}
	<table class="fulltable style1">
		<tbody>
			<tr>
				<td style="width:100px;">用户UID</td>
				<td>{$profile['basic']['uid']}</td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" class="master_btn" name="tijiao" value="修改"/></a></td>
			</tr>
		</tbody>
	</table>
	</form>
{include file="./my_footer.tpl"}

