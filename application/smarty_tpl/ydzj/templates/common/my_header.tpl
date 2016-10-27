{include file="common/header.tpl"}
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
	        <div id="repub" {if empty($repubList) || $norepub}style="display:none;"{/if}>
	           <a class="title" href="{site_url('hp/add')}">您有<em>{count($repubList)}</em>个待发布求货</a><a class="detail" data-url="{site_url('my_req/repub')}" href="javascript:void(0);">详情</a>
	        </div>
			<div class="feedback">{$feedback}</div>
