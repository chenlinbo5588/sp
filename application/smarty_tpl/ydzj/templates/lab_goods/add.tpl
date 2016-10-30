{include file="common/my_header.tpl"}
	{config_load file="lab_goods.conf"}
	<div id="handleDiv">
    <form name="goodsForm" method="post" action="{site_url($uri_string)}">
    {if $info['id']}
    <input type="hidden" name="id" value="{$info['id']}"/>
    <input type="hidden" name="lab_id" value="{$info['lab_id']}"/>
	{/if}
    {include file="./add_body.tpl"}
    </form>
    </div>
    <script>
    	$(function(){
	    	$("form").each(function(){
                var name = $(this).prop("name");
                formLock[name] = false;
            });
            
            $.loadingbar({ urls: [ new RegExp($("form[name=goodsForm]").attr('action')) ], templateData:{ message:"努力加载中..." } ,container: "#handleDiv" });
            bindAjaxSubmit('form');
	    });
    </script>
{include file="common/my_footer.tpl"}