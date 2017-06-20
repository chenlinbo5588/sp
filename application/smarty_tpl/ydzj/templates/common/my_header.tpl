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
			<div class="feedback">{$feedback}</div>
