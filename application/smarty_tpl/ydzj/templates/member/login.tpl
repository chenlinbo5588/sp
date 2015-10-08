{include file="common/header.tpl"}

<div class="logo" title="运动之家 logo"></div>
{if !$profile}
<div class="handle_area">
    {if $feedback}<div class="warning">{$feedback}</div>{/if}
    {form_open(site_url('member/login'))}
        <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
        {form_error('loginname')}
        <div class="row">
            <label class="side_lb" for="loginname_text">用户名：</label><input id="loginname_text" class="at_txt" type="text" name="loginname" {if $loginname}value="{$loginname}"{else}value="{set_value('loginname')}"{/if} placeholder="请输入手机号码"/>
        </div>
        {form_error('password')}
        <div class="row">
            <label class="side_lb" for="password_text">密&nbsp;&nbsp;&nbsp;&nbsp;码：</label><input id="password_text" class="at_txt" type="password" name="password" value="{set_value('password')}" placeholder="请输入您的登陆密码"/>
        </div>
        <div class="row"><input class="master_btn" type="submit" name="login" value="登陆"/></div>
        <div class="row clearfix" style="margin:15px;">
            <a class="fl" href="{site_url('member/register')}" title="注册">注册</a>
            <a class="fr"  href="{site_url('member/forget')}" title="忘记密码">忘记密码</a>
        </div>
    </form>
</div>
{else}
您已登陆 ,点击进入<a href="{site_url('my')}"> 个人中心 </a>  <a href="{site_url('member/logout')}">退出</a>
{/if}

{include file="common/footer.tpl"}