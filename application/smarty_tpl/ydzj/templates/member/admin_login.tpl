{include file="common/header.tpl"}
{if !$admin_profile}
<div class="handle_area" id="adminlogin">
    <div class="feedback">{$feedback}</div>
    {form_open(site_url('member/admin_login'))}
        <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
        <div class="row clearfix">
            <input id="email_text" class="at_txt" type="text" name="email" value="{set_value('email')}" placeholder="请输入邮箱"/>
        </div>
        <div class="tip">{form_error('email')}</div>
        <div class="row clearfix">
            <input id="password_text" class="at_txt" type="password" name="password" value="{set_value('password')}" placeholder="请输入您的登陆密码"/>
        </div>
        <div class="tip">{form_error('password')}</div>
        <div class="row clearfix">
            <input id="authcode_text" class="at_txt" type="text" autocomplete="off" name="auth_code" value="{set_value('auth_code')}" placeholder="请输入4位验证码"/>
            <img class="nature" id="authImg" src="{site_url('captcha')}" title="点击图片刷新"/>
        </div>
        <div class="tip">{form_error('auth_code')}</div>
        <div class="row"><input class="master_btn" type="submit" name="login" value="登陆"/></div>
        {*<div class="row center"><a href="{site_url('member/login')}" title="去前台登陆">去前台登陆</a></div>*}
    </form>
</div>
{else}
您已登陆 ,点击进入<a href="{admin_site_url('index/index')}"> 管理中心 </a>
{/if}
	<script>
	var imgUrl = "{site_url('captcha')}";
	$(function(){
		$("#authImg").bind("click",function(){
			$("#authImg").attr("src",imgUrl + "?t=" + Math.random());
		});
	});
	</script>
{include file="common/footer.tpl"}