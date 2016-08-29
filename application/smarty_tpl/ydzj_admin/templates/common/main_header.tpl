<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$SEO_title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="{$SEO_description}" />
<meta name="keywords" content="{$SEO_keywords}" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no,minimal-ui"/>
<link href="{resource_url('css/skin_1.css')}" rel="stylesheet" type="text/css" id="cssfile2" />
<link href="{resource_url('js/jquery-ui/themes/flick/jquery-ui-1.9.2.custom.css')}" rel="stylesheet" type="text/css" />
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
    LOADING_IMAGE = "{resource_url('img/loading/loading.gif')}";
    
if (cookie_skin) {
    $('#cssfile2').attr("href","{resource_url('css')}/"+ cookie_skin +".css");
} 
</script>
<script type="text/javascript" src="{resource_url('js/common.js')}"></script>
<script type="text/javascript" src="{resource_url('js/admincp.js')}"></script>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script type="text/javascript" src="{resource_url('js/html5shiv.js')}"></script>
<script type="text/javascript" src="{resource_url('js/respond.min.js')}"></script>
<![endif]-->
</head>
<body>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>

<div id="main-header">
	  <div id="title"><a href="javascript:void(0);">{config_item('site_name')}-管理中心</a></div>
	  <!-- Top navigation -->
	  <div id="topnav" class="top-nav">
	    <ul>
	      <li><div id="sitemap"><a class="bar-btn" id="siteMapBtn" href="#rhis" onclick="showBg('dialog','dialog_content');"><span>管理地图</span></a></div></li>
	      <li class="adminid" title="您好:{$admin_profile['basic']['account']|escape}">您好&nbsp;:&nbsp;<strong>{$admin_profile['basic']['account']|escape}</strong></li>
	      <li><a href="{admin_site_url('index/profile')}" target="workspace" ><span>修改密码</span></a></li>
	      <li><a href="{admin_site_url('index/logout')}" title="退出"><span>退出</span></a></li>
	      {*<li><a href="{site_url('index')}" target="_blank" title="{config_item('site_name')}"><span>{config_item('site_name')}</span></a></li>*}
	    </ul>
	  </div>
	<!-- Main navigation -->
	<ul id="nav" class="main-nav clearfix">
	    <li><a class="link actived" id="nav_dashboard" href="{admin_site_url('dashboard/welcome')}">控制台</a></li>
		<li><a class="link" id="nav_setting" href="{admin_site_url('setting/base')}">设置</a></li>
		<li><a class="link" id="nav_lab" href="{admin_site_url('lab/index')}">实验室</a></li>
		<li><a class="link" id="nav_member" href="{admin_site_url('lab_user/index')}">实验员</a></li>
		<li><a class="link" id="nav_goods" href="{admin_site_url('goods/index')}">货品</a></li>
		<li><a class="link" id="nav_measure" href="{admin_site_url('lab_measure/index')}">度量单位</a></li>
		<li><a class="link" id="nav_authority" href="{admin_site_url('authority/role')}">权限</a></li>
	</ul>
</div>
<div id="main-content">
	<div class="side"></div>
	<div class="page">
		<div class="loca clearfix">
			<strong>您的位置:</strong>
		    <div id="crumbs" class="crumbs"><span>控制台</span><span class="arrow">&nbsp;</span><span>欢迎页面</span></div>
		</div>