{include file="common/header.tpl"}
	<div class="boxz">
	    <ul id="loginPanel" class="panel">
		    {form_open(site_url('member/admin_login'))}
		        <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
		        <li class="title"><h1>登陆-{$siteSetting['site_name']}管理中心</h1></li>
		        <li class="tip">{form_error('email')}</li>
		        <li class="row">
		            <input id="email_text" class="at_txt" type="text" name="email" value="{set_value('email')}" placeholder="请输入邮箱"/>
		        </li>
		        <li class="tip">{form_error('password')}</li>
		        <li class="row">
		            <input id="password_text" class="at_txt" type="password" name="password" value="{set_value('password')}" placeholder="请输入您的登陆密码"/>
		        </li>
		        <li class="tip">{form_error('auth_code')}</li>
		        <li class="row rel">
		            <input id="authcode_text" class="at_txt" type="text" autocomplete="off" name="auth_code" value="{set_value('auth_code')}" placeholder="请输入4位验证码"/>
		            <img class="codeimg" id="authImg" src="{site_url('captcha')}" title="点击图片刷新"/>
		        </li>
		        <li class="row"><input class="master_btn" type="submit" name="login" value="登陆"/></li>
		        <li class="row"><a class="action" href="{site_url('member/login')}" title="去前台登陆">去前台登陆</a></li>
		    </form>
	    </ul>
	</div>
您已登陆 ,点击进入<a href="{admin_site_url('index/index')}"> 管理中心 </a>
	<script>
	var imgUrl = "{site_url('captcha')}";
	$(function(){
		$("#authImg").bind("click",function(){
			$("#authImg").attr("src",imgUrl + "?t=" + Math.random());
		});
	});
	</script>
{include file="common/footer.tpl"}