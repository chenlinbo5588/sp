{include file="./my_header.tpl"}
	{form_open(site_url('my/edit_base'),"id='editForm'")}
	<table class="fulltable style1">
		<tbody>
			<tr>
				<td style="width:100px;">用户UID</td>
				<td>{$profile['basic']['uid']}</td>
			</tr>
			<tr>
				<td>登陆账号</td>
				<td>{$profile['basic']['nickname']|escape}</td>
			</tr>
			<tr>
				<td>手机账号</td>
				<td>{$profile['basic']['mobile']|escape} <a class="warning"  href="{site_url('my/change_mobile')}">更换手机号码</a></td>
			</tr>
			<tr>
				<td>邮箱地址</td>
				<td>{$profile['basic']['email']|escape} {if $profile['basic']['email_status'] == 0}<span class="hightlight">邮箱尚未认证,可能无法收到邮件提醒</span> <a class="warning" href="{site_url('my/verify_email')}" title="马上认证邮箱">马上认证</a>{else}<span>已认证</span> <a href="{site_url('my/change_email')}">更换邮箱地址</a>{/if}</td>
			</tr>
			<tr>
				<td>头像</td>
				<td>
					<img src="{if $profile['basic']['avatar_s']}{resource_url($profile['basic']['avatar_s'])}{else}{resource_url($siteSetting['default_user_portrait'])}{/if}"/>
					<a class="warning" href="{site_url('my/set_avatar')}">上传头像</a>
				</td>
			</tr>
			<tr>
				<td>QQ</td>
				<td>{$profile['basic']['qq']|escape}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" class="master_btn" name="tijiao" value="修改"/></a></td>
			</tr>
		</tbody>
	</table>
	</form>
{include file="./my_footer.tpl"}

