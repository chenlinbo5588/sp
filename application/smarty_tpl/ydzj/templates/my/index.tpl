{include file="./my_header.tpl"}

	<table class="fulltable allborder">
		<thead>
			<tr>
				<th>账户资料</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="width:100px;">用户UID</td>
				<td>{$profile['basic']['uid']}</td>
			</tr>
			<tr>
				<td>登陆账号</td>
				<td>{$profile['basic']['mobile']|escape}</td>
			</tr>
			<tr>
				<td>昵称</td>
				<td>{$profile['basic']['nickname']|escape}</td>
			</tr>
			<tr>
				<td>邮箱地址</td>
				<td>{$profile['basic']['email']|escape} {if $profile['basic']['email_status'] == 0}<span>邮箱尚未认证,可能无法收到邮件提醒</span> <a class="warning" href="{site_url('my/verify_email')}" title="马上认证邮箱">马上认证</a>{else}<span>已认证</span> <a href="{site_url('my/change_email')}">更换邮箱地址</a>{/if}</td>
			</tr>
			<tr>
				<td>头像</td>
				<td><img src="{if $profile['basic']['avatar_s']}{resource_url($profile['basic']['avatar_s'])}{else}{resource_url($siteSetting['default_user_portrait'])}{/if}"/></td>
			</tr>
			<tr>
				<td>QQ</td>
				<td>{$profile['basic']['qq']|escape}</td>
			</tr>
			<tr>
				<td>上次登陆时间</td>
				<td>{$profile['basic']['last_login']|date_format:"%Y-%m-%d %H:%M"}</td>
			</tr>
			<tr>
				<td>注册IP</td>
				<td>{$profile['basic']['last_loginip']|escape}</td>
			</tr>
			<tr>
				<td>注册日期</td>
				<td>{$profile['basic']['reg_date']|date_format:"%Y-%m-%d %H:%M"}</td>
			</tr>
			<tr>
				<td>注册IP</td>
				<td>{$profile['basic']['reg_ip']|escape}</td>
			</tr>
			<tr>
				<td>积分</td>
				<td>{$profile['basic']['credits']|escape}</td>
			</tr>
			
			
		</tbody>
	</table>

{include file="./my_footer.tpl"}

