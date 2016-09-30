<!DOCTYPE html>
<html>
<head>
<title>{$SEO_title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="{$SEO_description}" />
<meta name="keywords" content="{$SEO_keywords}" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no,minimal-ui"/>
<link href="{resource_url('css/skin_1.css')}" rel="stylesheet" type="text/css" id="cssfile2" />
<link href="{resource_url('js/jquery-ui/themes/redmond/jquery-ui-1.9.2.custom.css')}" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{resource_url('js/jquery.js')}" ></script>
<script type="text/javascript" src="{resource_url('js/jquery.validation.min.js')}"></script>
<script type="text/javascript" src="{resource_url('js/jquery.cookie.js')}"></script>
<script>
var cookiedomain = "{config_item('cookie_domain')}",
    cookiepath = "{config_item('cookie_path')}",
    cookiepre = "{config_item('cookie_prefix')}",
    formhash = "{$formhash}",
    cookie_skin = $.cookie("MyCssSkin"),
    SITEURL = '{base_url()}',
    cityUrl = "{site_url('district/index/')}",
    LOADING_IMAGE = "{resource_url('img/loading/loading.gif')}",
    authCodeURL ="{site_url('api/register/authcode')}",
    captchaUrl = "{site_url('captcha/index')}";
    
if (cookie_skin) {
    $('#cssfile2').attr("href","{resource_url('css')}/"+ cookie_skin +".css");
}
</script>
<!--[if lt IE 9]>
<script type="text/javascript" src="{resource_url('js/html5shiv.js')}"></script>
<script type="text/javascript" src="{resource_url('js/respond.min.js')}"></script>
<![endif]-->
<script type="text/javascript" src="{resource_url('js/common.js')}"></script>
<script type="text/javascript" src="{resource_url('js/admincp.js')}"></script>
</head>
<body>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div id="main-header">
	<div id="title"><a href="javascript:void(0);">{config_item('site_name')}</a></div>
    <ul id="topnav" class="top-nav clearfix">
      {*<li><div id="sitemap"><a class="bar-btn" id="siteMapBtn" href="#rhis" onclick="showBg('dialog','dialog_content');"><span>快捷导航</span></a></div></li>*}
      <li class="adminid" title="您好:{$admin_profile['basic']['username']|escape}"><span>您好&nbsp;:&nbsp;</span><strong>{$admin_profile['basic']['username']|escape}</strong></li>
      <li><a href="{admin_site_url('index/profile')}"><span>修改密码</span></a></li>
      <li><a href="{admin_site_url('my/logout')}" title="退出"><span>退出</span></a></li>
    </ul>
	<ul id="nav" class="main-nav clearfix">
		{foreach from=$navs['main'] key=key item=item}
	<li><a class="link{if $item['url'] == $currentTopNav['url']} actived{/if}" href="{admin_site_url($item['url'])}">{$item['title']|escape}</a></li>
		{/foreach}
	</ul>
</div>
<div id="main-content">
	<div class="side-nav">
		{include file="common/side.tpl"}
	</div>
	<div class="page">
		<div class="loca clearfix">
			<strong>您的位置:</strong>
		    <div class="crumbs">
		    {if $breadCrumbs}
		    	{foreach name="crumbs" from=$breadCrumbs item=item}
		    	<a href="{admin_site_url($item['url'])}" title="{$item['title']|escape}">{$item['title']|escape}</a>
		    	{if !$smarty.foreach.crumbs.last}<a class="arrow">&nbsp;</a>{/if}
		    	{/foreach}
		   	{/if}
		   	</div>
		</div>
		{include file="common/sub_nav.tpl"}
