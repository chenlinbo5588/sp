{include file="common/header.tpl"}
	<div class="boxz">
	    <ul id="register" class="panel">
	    {form_open(site_url('member/register'),'id="registerForm"')}
	        <input type="hidden" name="inviter" value="{$inviter}"/>
	        <input type="hidden" name="inviteFrom" value="{$inviteFrom}"/>
	        <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
	        <li class="title"><h1>注册</h1></li>
	        <li class="tip">{$feedback}</li>
	        <li class="tip">{form_error('mobile')}</li>
	        <li class="row rel">
	            <input id="mobile_text" class="at_txt" type="text" name="mobile" value="{set_value('mobile')}" placeholder="请输入您常用的11位手机号码"/>
	            <input id="mobile_authcode" class="master_btn greenBtn" type="button" name="authCodeBtn" value="免费获取验证码"/>
	        </li>
	        <li class="tip">{form_error('psw')}</li>
	        <li class="row">
	            <input id="password_text" class="at_txt" type="password" name="psw" value="{set_value('psw')}" placeholder="请输入登陆密码"/>
	        </li>
	        <li class="tip">{form_error('psw_confirm')}</li>
	        <li class="row">
	            <input id="psw_confirm_text" class="at_txt" type="password" name="psw_confirm" value="{set_value('psw_confirm')}" placeholder="登陆密码确认"/>
	        </li>
	        <li class="tip">{form_error('auth_code')}</li>
	        <li class="row">
	            <input id="authcode_text" class="at_txt" type="text" name="auth_code" value="{set_value('auth_code')}" placeholder="请输入您收到的6位数字验证码"/>
	        </li>
	        <li class="row"><input class="master_btn" type="submit" name="register" value="注册"/></li>
	    </form>
	</div>
	<script>
	   var authCodeURL ="{site_url('api/register/authcode')}";
	</script>
	<script src="{resource_url('js/register.js')}" type="text/javascript"></script>
{include file="common/footer.tpl"}