{include file="common/header.tpl"}

{if !$manage_profile}
<div class="handle_area">
    <div class="feedback">{$feedback}</div>
    {form_open(site_url('member/admin_login'))}
        {form_error('email')}
        <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
        {form_error('email')}
        <div class="row">
            <label class="side_lb" for="email_text">用户名：</label><input id="email_text" class="at_txt" type="text" name="email" value="{set_value('email')}" placeholder="请输入邮箱/手机号码"/>
        </div>
        {form_error('password')}
        <div class="row">
            <label class="side_lb" for="password_text">密&nbsp;&nbsp;&nbsp;&nbsp;码：</label><input id="password_text" class="at_txt" type="password" name="password" value="{set_value('password')}" placeholder="请输入您的登陆密码"/>
        </div>
        <div class="row"><input class="primaryBtn" type="submit" name="login" value="登陆"/></div>
        <div class="row"><a href="{site_url('member/login')}" title="去前台登陆">去前台登陆</a>
    </form>
</div>
{else}
您已登陆 ,点击进入<a href="{site_url('sp_admin')}"> 管理中心 </a>
{/if}

{include file="common/footer.tpl"}