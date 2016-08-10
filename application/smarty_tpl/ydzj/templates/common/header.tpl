<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>{$SEO_title}</title>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="{$SEO_description}" />
<meta name="keywords" content="{$SEO_keywords}" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no,minimal-ui">
<link rel="stylesheet" type="text/css" href="{resource_url('css/admin_lab.css')}" />
{include file="common/js_var.tpl"}
<script type="text/javascript" src="{resource_url('js/jquery.js')}"></script>
<script type="text/javascript" src="{resource_url('js/common.js')}"></script>
<!--[if lte IE 9]>
<script type="text/javascript" src="{resource_url('js/html5shiv.js')}"></script>
<![endif]-->
<script type="text/javascript" src="{resource_url('js/respond.min.js')}"></script>
</head>
<body {if $bodyClass}class="{$bodyClass}"{/if}>
<div id="panelwrap">
    <div class="header">
    <div class="title"><a href="javascript:void(0);">实验室药品仪器管理中心 版本v2.1.0</a></div>
    <div class="header_right">欢迎 {$userProfile['name']}, <a href="{base_url('lab_setting/edit')}" class="settings">修改密码</a> <a href="{base_url('lab_logout')}" class="logout">退出</a></div></div>
    <div class="clear"></div>
    <div class="menu clearfix">
        <ul>
            {if $userProfile['id'] == $smarty.const.LAB_FOUNDER_ID || $userProfile['is_manager'] == 'y'}
            <li><a href="{base_url('lab_admin')}" {if $currentMenu == 'lab_admin'}class="selected"{/if}>实验室管理</a></li>
            <li><a href="{base_url('lab_user')}" {if $currentMenu == 'lab_user'}class="selected"{/if}>实验员管理</a></li>
            {/if}
            <li><a href="{base_url('lab_goods')}" {if $currentMenu == 'lab_goods'}class="selected"{/if}>货品管理</a></li>
            {if $userProfile['id'] == $smarty.const.LAB_FOUNDER_ID || $userProfile['is_manager'] == 'y'}
            <li><a href="{base_url('lab_category')}" {if $currentMenu == 'lab_category'}class="selected"{/if}>类别管理</a></li>
            <li><a href="{base_url('lab_measure')}" {if $currentMenu == 'lab_measure'}class="selected"{/if}>度量管理</a></li>
            {/if}
        </ul>
    </div>
   
