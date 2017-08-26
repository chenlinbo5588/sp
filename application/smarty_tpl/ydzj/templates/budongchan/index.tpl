{include file="common/my_header.tpl"}
	{config_load file="common.conf"}
	<div class="search_panel">
		<div class="mainside">
			<a class="action" href="{site_url('budongchan/add')}" title="{#bdc_add#}">{#bdc_add#}</a>
		</div>
		<form action="{site_url($uri_string)}" id="formSearch">
	        <input type="hidden" name="page" value=""/>
	         <ul class="search_con clearfix">
	            <li>
	                <label class="ftitle">{#bdc_name#}</label>
	                <input type="text" class="mtxt" name="name" value="{$smarty.post.name}"/>
	            </li>
	            <li>
	                <label class="ftitle">{#cm_lsno#}</label>
	                <input type="text" class="mtxt" name="lsno" value="{$smarty.post.lsno}"/>
	            </li>
	            <li>
	                <input class="master_btn" type="submit" name="search" value="查询"/>
	            </li>
	         </ul>
	        {include file="./list.tpl"}
		    <div class="align-right mg10">
		    	{include file="common/pagination.tpl"}
		    </div>
	    </form>
    </div>
    <script>
    	$(function(){
    		bindDeleteEvent();
    	});
    </script>
{include file="common/my_footer.tpl"}

