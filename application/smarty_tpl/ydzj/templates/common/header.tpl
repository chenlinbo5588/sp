<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>{$SEO_title}</title>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="{$SEO_description}" />
<meta name="keywords" content="{$SEO_keywords}" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no,minimal-ui">
<link rel="stylesheet" href="{resource_url('css/site.css',true)}"/>
<link href="{resource_url('font-awesome/css/font-awesome.min.css')}" rel="stylesheet" type="text/css"/>
<script>
var cookiedomain = "{config_item('cookie_domain')}",
    cookiepath = "{config_item('cookie_path')}",
    cookiepre = "{config_item('cookie_prefix')}",
    formhash = "{$formhash}",
    SITEURL = '{base_url()}',
    cityUrl = "{site_url('district/index/')}",
    LOADING_IMAGE = "{resource_url('img/loading/loading.gif')}",
    authCodeURL ="{site_url('api/register/authcode')}",
    captchaUrl = "{site_url('captcha/index')}";
</script>
<script type="text/javascript" src="{resource_url('js/jquery.js')}"></script>
{include file="common/jquery_ui.tpl"}
<link rel="stylesheet" href="{resource_url('js/toast/jquery.toast.min.css')}"/>
<script type="text/javascript" src="{resource_url('js/toast/jquery.toast.min.js')}"></script>
<script type="text/javascript" src="{resource_url('js/common.js',true)}"></script>
</head>
<a name="top"></a>
<div id="showDlg" style="display:none;"></div>
<body class="{if $bodyClass}{$bodyClass}{/if}">
    <div id="wrap">
        <div id="topbar">
            <div class="boxz">
                {if $isMobile && $profile}
                <div id="logo"><a id="navtoggle" href="javascript:void(0);">导航</a></div>
                <div id="mobilenav">{include file="my/my_nav.tpl"}</div>
                {else}
                <div id="logo"><a href="{site_url('/')}">{$siteSetting['site_name']}</a></div>
                {/if}
                
	            <div id="homeSideLinks">
	               {if $profile}
	                   <a href="{site_url('my/index')}">{$profile['basic']['username']|escape}</a>
	                   <a class="action" href="{site_url('member/logout')}">退出</a>
	               {else}
	                   <a class="login action" href="{site_url('member/login')}">登陆</a>
	                   <a class="register action" href="{site_url('member/register')}">注册</a>
	               {/if}
	            </div>
            </div>
        </div>
        <!-- begin main-content -->
        <div id="main-content">