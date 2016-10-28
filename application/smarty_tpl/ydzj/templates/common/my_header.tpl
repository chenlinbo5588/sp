{include file="common/header.tpl"}
	<link href="{resource_url('css/lab.css')}" rel="stylesheet" type="text/css"/>
    <div id="my" class="boxz clearfix">
        {if !$isMobile}{include file="my/my_nav.tpl"}{/if}
        <div class="panel_content">
        	<div class="loca clearfix">
				<strong>您的位置:</strong>
			    <div class="crumbs">
			    	{foreach name="crumbs" from=$breadCrumbs item=item}
			    	<a href="{site_url($item['url'])}" title="{$item['title']|escape}">{$item['title']|escape}</a>
			    	{if !$smarty.foreach.crumbs.last}<a class="arrow">&nbsp;</a>{/if}
			    	{/foreach}
			   	</div>
			</div>
			{*
			<div id="repub">
				<a class="warning" href="{site_url('hp/add')}">3个货品待重新发布</a>
				<ul class="republist">
					<li><strong>Z12233</strong><span>Z12233</span><a href="javascript:void(0);">X</a></li>
				</ul>
			</div>*}
			<div class="feedback">{$feedback}</div>
