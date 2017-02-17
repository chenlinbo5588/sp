{include file="common/share_header.tpl"}
<link href="{resource_url('css/skin_1.css')}" rel="stylesheet" type="text/css"/>
<div id="main-header">
	<div id="title"><a href="javascript:void(0);">{config_item('site_name')}-管理中心</a></div>
    <ul id="topnav" class="top-nav clearfix">
      <li><div id="sitemap"><a class="bar-btn" id="siteMapBtn" href="#rhis" onclick="showBg('dialog','dialog_content');"><span>快捷导航</span></a></div></li>
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