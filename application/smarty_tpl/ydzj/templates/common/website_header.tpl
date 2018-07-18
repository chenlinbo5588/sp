{include file="common/share_header.tpl"}
<body {if $bodyClass}class="{$bodyClass}"{/if}>
    <a name="top"></a>
    <div id="showDlg" style="display:none;"></div>
    <div id="wrap">
        <header>
	        <div id="topBar" class="clearfix">
		       <div class="boxz clearfix">
		       		<div id="siteLogo" class="logo"></div>
		       		<div id="siteContacts">
		           		<a class="welcome" href="/" title="首页">{if $currentLang == 'english'}Welcome To Visist {$siteSetting['site_name_en']|escape}{else}您好,欢迎访问{$siteSetting['site_name']|escape}{/if}</a>
		           		<div class="lixi">
					    	<p class="lixi2"> <a>{$cm_telephone}:0571-86759585</a><span> (总机)</span></p>
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
	   </header>
	   <section>
	        <!-- begin main-content -->
	        <div id="main-content">
    