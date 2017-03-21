{include file="common/share_header.tpl"}
<body {if $bodyClass}class="{$bodyClass}"{/if}>
    <a name="top"></a>
    <div id="showDlg" style="display:none;"></div>
    <div id="wrap">
        <div id="topBar" class="clearfix">
	       <div class="boxz clearfix">
	       		<div id="siteLogo" class="logo"><a href="/"><img src="{resource_url('img/cmp/logo-white.png')}"/></a></div>
	       		<div id="siteContacts">
	           		<a class="welcome" href="/" title="首页">{if $currentLang == 'english'}Welcome To Visist {else}您好,欢迎访问{/if}{config_item('site_name')}</a>
	           		<div class="lixi">
				    	<p class="lixi1"> <a>手机:13758123686</a><span> (销售部)</span></p>
				    	<p class="lixi2"> <a>电话:0571-86759585</a><span> (总机)</span></p>
				    </div>
				    <div id="langDiv"><a class="cn" href="{base_url('/?lang=chinese')}"><img src="{resource_url('img/chn.png')}"/>中文版 </a> | <a class="eng" href="{base_url('/?lang=english')}"><img src="{resource_url('img/gbr.png')}"/>English </a></div>
	           		{if $isMobile}
	           		<a href="javascript:void(0);" id="naviText">{if $currentLang == 'english'}Navigation{else}导航{/if}</a>
	           		{/if}
	       		</div>
	       	</div>
	       	<div class="boxz clearfix">
	       		{include file="common/website_nav.tpl"}
	        </div>
	   </div>
        <!-- begin main-content -->
        <div id="main-content">
    