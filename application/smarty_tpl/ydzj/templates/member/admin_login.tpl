{include file="common/header.tpl"}
<style>

#adminlogin {
	width:100%;
	max-width:640px;
	margin: 0 auto;
}

#adminlogin h1 {
	text-align:center;
}

#adminlogin .side_lb {
	width:23%;
	display:block;
	float:left;
}

#adminlogin .at_txt {
	width:70%;
}

</style>
{if !$manage_profile}
<div class="handle_area" id="adminlogin">
    <div class="feedback">{$feedback}</div>
    <h1>管理后台登陆</h1>
    {form_open(site_url('member/admin_login'))}
        <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
        <div class="row clearfix">
            <label class="side_lb" for="email_text">用户名：</label><input id="email_text" class="at_txt" type="text" name="email" value="{set_value('email')}" placeholder="请输入邮箱"/>
        </div>
        {form_error('email')}
        <div class="row clearfix">
            <label class="side_lb" for="password_text">密码：</label><input id="password_text" class="at_txt" type="password" name="password" value="{set_value('password')}" placeholder="请输入您的登陆密码"/>
        </div>
        {form_error('password')}
        <div class="row clearfix">
            <label class="side_lb" for="authcode_text">验证码：</label><input id="authcode_text" class="at_txt" type="text" name="auth_code" value="{set_value('auth_code')}" placeholder="请输入4位验证码"/>
        </div>
        {form_error('auth_code')}
        <div class="row clearfix">
            <label class="side_lb" for="authcode_text">&nbsp;</label>
            <img class="nature" id="authImg" src="{site_url('captcha')}" title="点击图片刷新"/>&nbsp;<a href="javascript:void(0)" id="refreshBtn">看不请,点击刷新验证码</a>
        </div>
        
        <div class="row"><input class="master_btn" type="submit" name="login" value="登陆"/></div>
        {*<div class="row center"><a href="{site_url('member/login')}" title="去前台登陆">去前台登陆</a></div>*}
    </form>
</div>
{else}
您已登陆 ,点击进入<a href="{site_url('sp_admin')}"> 管理中心 </a>
{/if}
	<script>
	var imgUrl = "{site_url('captcha')}";
	$(function(){
		$("#refreshBtn").bind("click",function(){
			$("#authImg").attr("src",imgUrl + "?t=" + Math.random());
		});
	});
	</script>
{include file="common/footer.tpl"}