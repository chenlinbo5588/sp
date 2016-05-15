<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>{$SEO_title}</title>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="{$SEO_description}" />
<meta name="keywords" content="{$SEO_keywords}" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no,minimal-ui">
<link rel="stylesheet" href="{resource_url('css/site.css')}"/>
<link rel="stylesheet" href="{resource_url('css/site.css')}"/>
<script type="text/javascript" src="{resource_url('js/jquery.js')}"></script>
<script>
var cookiedomain = "",
    cookiepath = "{config_item('cookie_path')}",
    cookiepre = "{config_item('cookie_prefix')}",
    formhash = "{$formhash}",
    SITEURL = '{base_url()}',
    cityUrl = "{site_url('district/index/')}",
    LOADING_IMAGE = "{resource_url('img/loading/loading.gif')}";
</script>
<script type="text/javascript" src="{resource_url('js/common.js')}"></script>
<!--[if lte IE 9]>
<script type="text/javascript" src="{resource_url('js/html5shiv.js')}"></script>
<![endif]-->
<script type="text/javascript" src="{resource_url('js/respond.min.js')}"></script>
</head>
<body {if $bodyClass}class="{$bodyClass}"{/if}>
	<div id="wrap">
   		<div>
   			<img src="{resource_url('img/pg1/top1.png')}"/>
   		</div>
   		<div id="regbg">
   			<div id="reg">
	   			{form_open(site_url('member/register'),'id="registerForm"')}
		        <input type="hidden" name="inviter" value="{$inviter}"/>
		        <input type="hidden" name="inviteFrom" value="{$inviteFrom}"/>
		        <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
	   			<div class="username"><input type="text" class="txt noround" name="username" value="" placeholder="请输入用户名称"/></div>
	   			<div class="mobile"><input type="text" class="txt noround" name="mobile" value="" placeholder="请输入您的手机号码"/></div>
	   			<div class="auth_code"><input type="text" class="txt noround" name="auth_code" value="" style="width:70%" placeholder="请输入您的验证码"/><input type="button" class="getCode" name="authCodeBtn" value="获取验证码"/></div>
	   			<div class="sb"><input type="image" src="{resource_url('img/pg1/reg_btn.jpg')}" /></div>
	   		</div>
   		</div>
   		
{include file="common/footer.tpl"}