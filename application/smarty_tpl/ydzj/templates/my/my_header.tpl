{include file="common/header.tpl"}
	{*
    <script type="text/javascript" src="{resource_url('js/Web_SDK_Base_v2.8.0.js')}"></script>
    <script type="text/javascript" src="{resource_url('js/Web_SDK_NIM_v2.8.0.js')}"></script>
    
    <script type="text/javascript" src="{base_url('webim/sdk/dist/strophe.js')}"></script>
	<script type="text/javascript" src="{base_url('webim/sdk/dist/websdk-1.1.2.js')}"></script>
	<script type="text/javascript">
	var push_appkey = '{$pushConfig['appkey']}';
	</script>
	<script type="text/javascript" src="{base_url('webim/demo/javascript/dist/webim.config.js')}"></script>
	*}
    <div id="my" class="boxz clearfix">
        {include file="./my_nav.tpl"}
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
