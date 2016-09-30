{include file="common/main_header.tpl"}
	{config_load file="goods.conf"}
    {if $info['id']}
    <form name="goodsForm" method="post" action="{site_url('goods/edit?id=')}{$info['id']}">
    <input type="hidden" name="id" value="{$info['id']}"/>
    <input type="hidden" name="lab_id" value="{$info['lab_id']}"/>
	{else}
	<form name="goodsForm" method="post" action="{site_url('goods/add')}">
	{/if}
    {include file="./add_body.tpl"}
    </form>
    <script>
    	$(function(){
	    	{include file="common/form_ajax_submit.tpl"}
	    });
    </script>
{include file="common/main_footer.tpl"}