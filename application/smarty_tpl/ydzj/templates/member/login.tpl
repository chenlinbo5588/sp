{include file="common/header.tpl"}
{if !$profile}
<div class="boxz">
	<ul id="loginPanel" class="panel">
	    {if $feedback}<div class="warning">{$feedback}</div>{/if}
	    {form_open(site_url('member/login'))}
	        <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
	        <li class="title"><h1>登陆</h1></li>
	        <li>{form_error('loginname')}</li>
	        <li class="row">
	            <input id="loginname_text" class="at_txt" type="text" name="loginname" {if $loginname}value="{$loginname}"{else}value="{set_value('loginname')}"{/if} placeholder="请输入登陆手机号码"/>
	        </li>
	        <li>{form_error('password')}</li>
	        <li class="row">
	            <input id="password_text" class="at_txt" type="password" name="password" value="{set_value('password')}" placeholder="请输登陆密码"/>
	        </li>
	        <li>{form_error('auth_code')}</li>
            <li class="row rel">
	            <input id="authcode_text" class="at_txt" type="text" autocomplete="off" name="auth_code" value="{set_value('auth_code')}" placeholder="请输入4位验证码"/>
	            <img class="codeimg" src="{site_url('captcha')}" title="点击图片刷新"/>
	        </li>
	        <li class="row"><input class="master_btn" type="submit" name="login" value="登陆"/></li>
	        <li class="row clearfix">
	            <a class="fl" href="{site_url('member/register')}" title="注册">注册</a>
	            <a class="fr"  href="{site_url('member/forget')}" title="忘记密码">忘记密码</a>
	        </li>
	    </form>
	</ul>
</div>
{else}
您已登陆 ,点击进入<a href="{site_url('my')}"> 个人中心 </a>  <a href="{site_url('member/logout')}">退出</a>
{/if}

{include file="common/footer.tpl"}