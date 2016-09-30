{include file="common/my_header.tpl"}
	{if !$send}
	{form_open(site_url($uri_string),"id='editForm'")}
	<table class="fulltable style1">
	   <tbody>
			<tr>
				<td class="w120"><label for="email">邮箱地址</td>
				<td>{$profile['basic']['email']|escape}</td>
			</tr>
			<tr>
				<td>验证码</td>
				<td>
					<input class="w25pre" type="text" name="auth_code" value="" placeholder="请填写下方图片中显示的验证码"/>
					{form_error('auth_code')}
				</td>
			</tr>
			<tr>
				<td></td>
				<td><div class="codeimg" id="authImg" title="点击图片刷新">正在获取验证码...</div><span class="muted">点击图片刷新</span></td>
			</tr>	
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" class="master_btn" name="tijiao" value="发送"/></a></td>
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
    {else}
    <div class="panel pd20 passbg">
    	<span>我们已经发了一封邮件到您的邮箱里， 请点击</span><a class="hightlight" href="{$emailServer}">{htmlentities($emailServer)}</a><span>登录查收,如果您没有收到邮箱请点击</span><a class="warning" href="{site_url('my/verify_email')}"><strong>重新发送</strong></a>
    </div>
    {/if}
{include file="common/my_footer.tpl"}

