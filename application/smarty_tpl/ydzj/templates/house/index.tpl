{include file="common/my_header.tpl"}
	{config_load file="common.conf"}
	{config_load file="person.conf"}
	<div class="search_panel">
		<form action="{site_url($uri_string)}" method="post" id="formSearch">
	        <input type="hidden" name="page" value=""/>
	         <ul class="search_con clearfix">
	            <li>
	                <label class="ftitle">{#qlr_name#}</label>
	                <input type="text" class="mtxt" name="qlr_name" value="{$smarty.post.qlr_name}"/>
	            </li>
	            <li>
	                <label class="ftitle">{#id_no#}</label>
	                <input type="text" name="id_no" value="{$smarty.post.id_no}" placeholder="请输入身份证号码"/>
	            </li>
	            <li>
	                <input class="master_btn" type="submit" name="search" value="查询"/>
	            </li>
	         </ul>
	        {include file="./list.tpl"}
		    <div class="align-right">{include file="common/pagination.tpl"}</div>
	    </form>
    </div>
    <script>
    	$(function(){
    		bindDeleteEvent();
    	});
    </script>
{include file="common/my_footer.tpl"}

