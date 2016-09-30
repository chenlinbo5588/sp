{include file="common/my_header.tpl"}
	<div>{$feedback}</div>
	{form_open(site_url($uri_string),"id='editForm'")}
	<table class="fulltable style1">
		<tbody>
			<tr>
				<td class="w120">用户UID</td>
				<td>{$profile['basic']['uid']}</td>
			</tr>
			<tr>
                <td>登录账户</td>
                <td>{$profile['basic']['username']}</td>
            </tr>
			<tr>
				<td>原密码</td>
				<td><input type="password" name="old_psw" value="" placeholder="请输入原密码"/>{form_error('old_psw')}</td>
			</tr>
			<tr>
				<td>原密码</td>
				<td><input type="password" name="psw" value="" placeholder="请输入新密码"/>{form_error('psw')}</td>
			</tr>
			<tr>
				<td>原密码确认</td>
				<td><input type="password" name="psw2" value="" placeholder="请输入新密码"/>{form_error('psw2')}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" class="master_btn" name="tijiao" value="保存"/></td>
			</tr>
		</tbody>
	</table>
	</form>
{include file="common/my_footer.tpl"}

