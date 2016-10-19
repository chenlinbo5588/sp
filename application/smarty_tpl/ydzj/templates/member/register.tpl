{include file="common/header.tpl"}
	<div class="boxz">
	    <ul id="register" class="panel">
	    {form_open(site_url('member/register'),'id="registerForm"')}
	        <input type="hidden" name="inviter" value="{$inviter}"/>
	        <input type="hidden" name="inviteFrom" value="{$inviteFrom}"/>
	        <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
	        <li class="title"><h1>注册</h1></li>
	        <li class="tip">{$feedback}</li>
	        
            <li class="tip" id="tip_username">{form_error('username')}</li>
            <li class="row">
                <input class="at_txt" type="text" name="username" value="{set_value('username')}" placeholder="登陆账号,字母、数字组合最长20个字符，中文最多8个字符"/>
            </li>
            <li class="tip">{form_error('email')}</li>
            <li class="row">
                <input class="at_txt" type="text" name="email" value="{set_value('email')}" placeholder="请输入您常用的邮箱地址"/>
            </li>
            <li class="tip">{form_error('qq')}</li>
            <li class="row">
                <input class="at_txt" type="text" name="qq" value="{set_value('qq')}" placeholder="请输入QQ号码"/>
            </li>
	        <li class="tip" id="tip_psw">{form_error('psw')}</li>
	        <li class="row">
	            <input class="at_txt" type="password" name="psw" value="{set_value('psw')}" placeholder="密码长度6~12位,英文字母、数字、特殊符号"/>
	        </li>
	        <li class="tip">{form_error('psw_confirm')}</li>
	        <li class="row">
	            <input class="at_txt" type="password" name="psw_confirm" value="{set_value('psw_confirm')}" placeholder="登陆密码确认"/>
	        </li>
	        <li class="tip">{form_error('mobile')}</li>
            <li class="row">
                <input class="at_txt" type="text" id="mobile" name="mobile" value="{set_value('mobile')}" placeholder="请输入您常用手机号码，如13868880088"/>
            </li>
	        <li>{form_error('auth_code')}</li>
            <li class="row rel">
                <input class="w50pre" type="text" autocomplete="off" name="auth_code" value="{set_value('auth_code')}" placeholder="请输入右侧图片中4位验证码"/>
                <div class="codeimg" id="authImg" title="点击图片刷新">正在获取验证码...</div>
            </li>
            <li class="tip">{form_error('mobile_auth_code')}</li>
            <li class="row  rel">
                <input class="at_txt" type="text" name="mobile_auth_code" value="{set_value('mobile_auth_code')}" placeholder="请输入您手机收到的6位数字验证码"/>
                <input id="mobile_authcode" class="master_btn greenBtn grayed mcode" disabled data-mcode="#mobile" type="button" name="authCodeBtn" value="免费获取验证码"/>
            </li>
	        <li class="row"><input class="master_btn w100pre" type="submit" name="register" value="注册"/></li>
	    </form>
	    </ul>
	</div>
	<script>
		var captchaCheck = "{site_url('captcha/check_captcha')}", usernameCheck = "{site_url('member/check_username')}";
	</script>
	{include file="common/jquery_ui.tpl"}
	<script src="{resource_url('js/getcode.js')}" type="text/javascript"></script>
	<script src="{resource_url('js/register.js')}" type="text/javascript"></script>
{include file="common/footer.tpl"}