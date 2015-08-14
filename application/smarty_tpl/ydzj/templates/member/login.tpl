{include file="common/header.tpl"}

<div class="logo" title="运动之家 logo"></div>
{if !$profile}
<div class="handle_area">
    <div class="feedback">{$feedback}</div>
    {form_open(site_url('member/login'))}
        <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
        {form_error('email')}
        <div class="row">
            <label class="side_lb" for="email_text">用户名：</label><input id="email_text" class="at_txt" type="text" name="email" {if $loginemail}value="{$loginemail}"{else}value="{set_value('email')}"{/if} placeholder="请输入邮箱/手机号码"/>
        </div>
        {form_error('password')}
        <div class="row">
            <label class="side_lb" for="password_text">密&nbsp;&nbsp;&nbsp;&nbsp;码：</label><input id="password_text" class="at_txt" type="password" name="password" value="{set_value('password')}" placeholder="请输入您的登陆密码"/>
        </div>
        <div class="row"><input class="primaryBtn" type="submit" name="login" value="登陆"/></div>
        <div class="row"><a class="link_btn grayed" href="{site_url('member/register')}" title="注册">注册</a></div>
        <div class="row center" style="margin:20px 0 10px 0;"><a href="{site_url('member/forget')}" title="忘记密码">忘记密码</div>
    </form>
</div>
{else}
您已登陆 ,点击进入<a href="{site_url('my')}"> 个人中心 </a>  <a href="{site_url('member/logout')}">退出</a>
{/if}

{include file="common/footer.tpl"}