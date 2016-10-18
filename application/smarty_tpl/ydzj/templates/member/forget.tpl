{include file="common/header.tpl"}
    <style>
    .panel .row {
	    padding: 15px 5px;
	}
	
	#loginPanel .codeimg, #register .codeimg {
	   top:16px;
	}
	
	#mobile_authcode {
	    top: 15px;
    }
    
    .step3 {
    
    }
    </style>
	<div class="boxz">
	    {$stepHTML}
	    {form_open(site_url('member/forget'),'id="forgetForm"')}
	    <ul id="register" class="panel step{$step}">
	        <li class="title"><h1>找回密码</h1></li>
	        <li class="tip">{$feedback}</li>
	        {if 1==$step}
            <li class="tip">{form_error('username')}</li>
            <li class="row">
                <input class="at_txt" type="text" name="username" value="{set_value('username')}" placeholder="请输入您登陆账号"/>
            </li>
            <li class="tip">{form_error('auth_code')}</li>
            <li class="row rel">
                <input class="at_txt" type="text" autocomplete="off" name="auth_code" value="{set_value('auth_code')}" placeholder="请输入4位验证码"/>
                <div class="codeimg" id="authImg" title="点击图片刷新">正在获取验证码...</div>
            </li>
	        <li class="row"><input class="master_btn at_txt" type="submit" name="register" value="下一步"/></li>
	        {elseif 2==$step}
	        <li class="row forgetway">
               <label><input type="radio" name="find_way" checked value="way_email" {set_radio('find_way','way_email')}/>通过邮箱找回</label>
               <label><input type="radio" name="find_way" value="way_mobile" {set_radio('find_way','way_mobile')}/>通过手机找回</label>
            </li>
            <li class="row way_email">
                <input class="at_txt" type="hidden" id="email" name="email" value="{$userinfo['email']}" />
                <strong><a href="{$mailurl}" target="_blank">{mask_email($userinfo['email'])}</a></strong><input type="button" name="emailCodeBtn" class="action" data-retext="重发验证邮件" value="发送验证邮件"/>
            </li>
            <li class="tip" id="email_code"></li>
            <li class="row way_email">
                <input class="at_txt" type="text" id="email_code" name="email_code" value="" placeholder="请输入您邮箱中的收到的验证码"/>
            </li>
            <li class="row way_mobile">
                <input class="at_txt" type="hidden" id="mobile" name="mobile" value="{$userinfo['mobile']}" />
                <strong>{mask_mobile($userinfo['mobile'])}</strong>
            </li>
            <li class="tip">{form_error('auth_code')}</li>
            <li class="row rel way_mobile">
                <input class="w50pre" type="text" autocomplete="off" name="auth_code" value="" placeholder="请输入4位验证码"/>
                <div class="codeimg" id="authImg" title="点击图片刷新">正在获取验证码...</div>
            </li>
            <li class="tip">{form_error('mobile_auth_code')}</li>
            <li class="row rel way_mobile">
                <input class="at_txt" type="text" name="mobile_auth_code" value="" placeholder="请输入您手机收到的6位数字验证码"/>
                <input id="mobile_authcode" class="master_btn greenBtn grayed mcode" disabled data-mcode="#mobile" type="button" name="authCodeBtn" value="免费获取验证码"/>
            </li>
            <li class="row"><input class="master_btn at_txt" type="submit" name="register" value="下一步"/></li>
	        {elseif 3==$step}
	        <li class="tip">{form_error('newpsw')}</li>
	        <li class="row">
                <input class="at_txt" type="password"  name="newpsw" value="" placeholder="请输入新密码"/>
            </li>
            <li class="tip">{form_error('newpsw_confirm')}</li>
            <li class="row">
                <input class="at_txt" type="password"  name="newpsw_confirm" value="" placeholder="请输入新确认"/>
            </li>
            <li class="tip">{form_error('auth_code')}</li>
            <li class="row rel">
                <input class="w50pre" type="text" autocomplete="off" name="auth_code" value="" placeholder="请输入4位验证码"/>
                <div class="codeimg" id="authImg" title="点击图片刷新">正在获取验证码...</div>
            </li>
            <li class="row"><input class="master_btn at_txt" type="submit" name="register" value="重新设置密码"/></li>
	        {elseif 4==$step}
	        <div class="pd20 passbg">
		        <span>密码修改成功,<a class="warning" href="{site_url('member/login')}">马上登陆</a></span>
		    </div>
	        {/if}
	    </ul>
	    </form>
	</div>
	<script>
		var emailUrl = "{site_url('member/email_code')}";
		var captchaCheck = "{site_url('captcha/check_captcha')}";
	</script>
	<script src="{resource_url('js/forget.js')}" type="text/javascript"></script>
	<script src="{resource_url('js/getcode.js')}" type="text/javascript"></script>
{include file="common/footer.tpl"}