{include file="common/my_header.tpl"}
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
				<td><input class="w25pre" type="password" name="old_psw" value="" placeholder="请输入原密码"/>{form_error('old_psw')}</td>
			</tr>
			<tr>
				<td>新密码</td>
				<td><input class="w25pre" type="password" name="psw" value="" placeholder="请输入新密码"/>{form_error('psw')}</td>
			</tr>
			<tr>
				<td>新密码确认</td>
				<td><input class="w25pre" type="password" name="psw2" value="" placeholder="请输入新密码"/>{form_error('psw2')}</td>
			</tr>
			<tr>
                <td>验证码</td>
                <td>
                    <input class="w25pre" type="text" name="auth_code" value="" placeholder="请填写图片中显示的验证码"/>
                    {form_error('auth_code')}
                </td>
            </tr>
            <tr>
                <td></td>
                <td><div class="codeimg" id="authImg" title="点击图片刷新">正在获取验证码...</div><span class="muted">点击图片刷新</span></td>
            </tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" class="master_btn" name="tijiao" value="保存"/></td>
			</tr>
		</tbody>
	</table>
	</form>
	<script>
        $(function(){
            var imgCode1 = $.fn.imageCode({ wrapId: "#authImg", captchaUrl : captchaUrl });
            setTimeout(imgCode1.refreshImg,500);
        });
    </script>
{include file="common/my_footer.tpl"}

