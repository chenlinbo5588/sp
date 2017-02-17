{include file="common/header.tpl"}
	<link href="{resource_url('css/login.css')}" rel="stylesheet" type="text/css"/>
	<div class="bg-dot"></div>
	<div class="boxz">
	    <ul id="loginPanel" class="panel">
		    {form_open(site_url('member/admin_login'))}
		        <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
		        <li class="title"><h1>登陆-{$siteSetting['site_name']}管理中心</h1></li>
		        <li class="tip">{form_error('email')}</li>
		        <li class="row">
		            <input id="email_text" class="at_txt" type="text" name="email" value="{set_value('email')}" placeholder="请输入登陆邮箱地址"/>
		        </li>
		        <li class="tip">{form_error('password')}</li>
		        <li class="row">
		            <input id="password_text" class="at_txt" type="password" name="password" value="{set_value('password')}" placeholder="请输入您的登陆密码"/>
		        </li>
		        <li class="tip">{form_error('auth_code')}</li>
		        <li class="row rel">
		            <input id="authcode_text" class="at_txt" type="text" autocomplete="off" name="auth_code" value="{set_value('auth_code')}" placeholder="请输入右侧图片中4位验证码"/>
		            <div class="codeimg" id="authImg" title="点击图片刷新">正在获取验证码...</div>
		        </li>
		        <li class="row"><input class="master_btn w100pre" type="submit" name="login" value="登陆"/></li>
		        <li class="row clearfix">
		          {if empty($profile)}
		          <a class="fl" href="{site_url('member/login')}" title="去前台登陆">去前台登陆</a>
		          {/if}
		          {if $admin_profile}
		          <a class="fr" href="{admin_site_url('index/index')}">进入管理中心 </a>
		          {/if}
		        </li>
		    </form>
	    </ul>
	</div>
    <script>
    	$(function(){
			var imgCode1 = $.fn.imageCode({ wrapId: "#authImg", captchaUrl : captchaUrl });
	    	setTimeout(imgCode1.refreshImg,500);
		});
	</script>
{include file="common/footer.tpl"}