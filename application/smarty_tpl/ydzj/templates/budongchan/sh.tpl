{include file="common/my_header.tpl"}
	{config_load file="common.conf"}
	<div class="search_panel">
		<form action="{site_url($uri_string)}" id="formSearch">
	        <input type="hidden" name="page" value=""/>
	         <ul class="search_con clearfix">
	            <li>
	                <label class="ftitle">{#bdc_name#}</label>
	                <input type="text" class="mtxt" name="name" value="{$smarty.get.name}"/>
	            </li>
	            <li>
	                <label class="ftitle">{#cm_lsno#}</label>
	                <input type="text" class="mtxt" name="lsno" value="{$smarty.get.lsno}"/>
	            </li>
	            <li>
	                <input class="master_btn" type="submit" name="search" value="查询"/>
	            </li>
	         </ul>
	         <table class="fulltable bordered">
	            <thead>
	                <tr>
	                    <th>{#cm_lsno#}</th>
	                    <th>{#bdc_name#}</th>
	                    <th>{#cm_id_type#}</th>
	                    <th>{#cm_id_no#}</th>
	                    <th>{#cm_mobile#}</th>
	                    <th>{#cm_address#}</th>
	                    <th>{#cm_status#}</th>
	                    <th>当前经办</th>
	                    <th>{#cm_add_username#}<br/>{#cm_gmt_create#}</th>
	                    <th>{#cm_edit_username#}<br/>{#cm_gmt_modify#}</th>
	                </tr>
	            </thead>
		        <tbody>
			        {foreach from=$list item=item}
			        <tr id="row{$item['id']}" class="{if $item['cur_uid']}shouli{else}unshouli{/if}">
			           <td><a href="{site_url($shUrl|cat:'?id='|cat:$item['id'])}">{$item['lsno']}</a></td>
			           <td><a href="{site_url($shUrl|cat:'?id='|cat:$item['id'])}">{$item['name']|escape}</a></td>
			           <td>{$id_type[$item['id_type']]}</td>
			           <td>{$item['id_no']}</td>
			           <td>{$item['mobile']|escape}</td>
			           <td>{$item['address']}</td>
			           <td><div>{$item['status_name']}【{if $item['cur_uid']}已{else}未{/if}受理】{if $item['is_done']}已{else}未{/if}办结</div></td>
			           <td>
			           		<div>{$item['cur_dept_sname']|escape}</div>
			           		{if $item['cur_uid']}
			           		<div>{$item['cur_username']|escape}</div>
			           		{/if}
			           </td>
			           <td>
			           		{$item['add_username']|escape}<br/>
			           		{time_tran($item['gmt_create'])}
			           </td>
			           <td>
			           		{$item['edit_username']|escape}<br/>
			           		{time_tran($item['gmt_modify'])}
			           </td>
			        <tr>
			        {foreachelse}
			        <tr><td colspan="10">找不到相关记录</td></tr>
			        {/foreach}
			    </tbody>
			 </table>
		    <div class="align-right mg10">
		    	{include file="common/pagination.tpl"}
		    </div>
	    </form>
    </div>
    
{include file="common/my_footer.tpl"}

