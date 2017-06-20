{include file="common/my_header.tpl"}
	{config_load file="common.conf"}
	{config_load file="person.conf"}
	<div class="search_panel">
		<div class="mainside">
			<a class="action" href="{site_url('person/add')}" title="添加人员">添加人员</a>
			<a class="action" href="{site_url('person/import')}" title="EXCEL导入">EXCEL导入</a>
		</div>
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
	                <label class="ftitle">{#sex#}</label>
	                <select name="sex">
	                    <option value="0" {if $smarty.post.sex == 0}selected{/if}>不限</option>
	                    <option value="1" {if $smarty.post.sex == 1}selected{/if}>男</option>
	                    <option value="2" {if $smarty.post.sex == 2}selected{/if}>女</option>
	                </select>
	            </li>
	            <li>
	                <label class="ftitle">{#person_yhdz#}</label>
	                <select name="yhdz">
	                    <option value="" {if $smarty.post.yhdz == 0}selected{/if}>不限</option>
	                    <option value="是" {if $smarty.post.yhdz == '是'}selected{/if}>是</option>
	                </select>
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

