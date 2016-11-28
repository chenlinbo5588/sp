{include file="common/share_header.tpl"}
<link rel="stylesheet" href="{resource_url('css/website.css',true)}"/>
<body {if $bodyClass}class="{$bodyClass}"{/if}>
    <a name="top"></a>
    <div id="showDlg" style="display:none;"></div>
    <div id="wrap">
        <div id="topbar">
            <div class="boxz clearfix">
                <div id="logo"><a href="{site_url('/')}">{$siteSetting['site_name']}</a></div>
                {include file="common/website_nav.tpl"}
                <div id="homeSideLinks">
                    {if $isMobile}
	                <a href="javascript:void(0);" id="naviText">导航</a>
	                {/if}
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
    