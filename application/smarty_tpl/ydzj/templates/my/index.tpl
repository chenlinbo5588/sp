{include file="./my_header.tpl"}
	{include file="common/dlg.tpl"}
	<table class="fulltable style1">
		<tbody>
			<tr>
				<td class="w120">用户UID</td>
				<td>{$profile['basic']['uid']} <a class="hightlight" href="{site_url('my/edit_base')}">修改基本资料</a></td>
			</tr>
			<tr>
                <td>登陆账号</td>
                <td>{$profile['basic']['username']|escape}</td>
            </tr>
			<tr>
				<td>昵称</td>
				<td>{$profile['basic']['nickname']|escape}</td>
			</tr>
			<tr>
				<td>手机账号</td>
				<td><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;<a href="javascript:void(0);">{$profile['basic']['mobile']|escape}</a><a class="warning"  href="{site_url('my/change_mobile')}">更换手机号码</a></td>
			</tr>
			<tr>
				<td>邮箱地址</td>
				<td>{$profile['basic']['email']|escape} {if $profile['basic']['email_status'] == 0}<span class="hightlight">邮箱尚未认证,可能无法收到邮件提醒</span> <a class="warning" href="{site_url('my/verify_email')}" title="马上认证邮箱">马上认证邮箱</a>{else}<span>已认证</span> <a href="{site_url('my/change_email')}">更换邮箱地址</a>{/if}</td>
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
			<tr>
				<td>推广链接</td>
				<td>
					<input type="text" style="width:80%" value="{$inviteUrl}"/>
					<input class="action" type="button" name="copylink" value="复制推广链接"/>
				</td>
			</tr>
		</tbody>
	</table>
{include file="./my_footer.tpl"}

