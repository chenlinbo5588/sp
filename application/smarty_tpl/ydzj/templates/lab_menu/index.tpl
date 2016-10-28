{include file="common/my_header.tpl"}
  	<div>
  	   <a class="master_btn" href="{site_url('lab_menu/add')}">添加功能菜单</a>
  	</div>
  	
  	  <table class="fulltable tb-type2 noext">
	    <thead>
	        <tr>
	            <th class="first">层级</th>
	            <th>菜单名称</th>
	            <th class="last">操作</th>
	        </tr>
	    </thead>
	    <tbody>
	        {foreach from=$lab_menu key=key item=item}
            <tr id="row{$item['id']}">
                <td>{$item['level'] + 1}</td>
                <td>{$item['sep']}{$item['name']|escape}</td>
                <td>
                    <a href="{site_url('lab_menu/edit?id=')}{$item['id']}">编辑</a>&nbsp;
                    <a class="delete" href="javascript:void(0);" data-id="{$item['id']}" data-url="{site_url('lab_menu/delete?id=')}{$item['id']}" data-title="{$item['name']|escape}">删除</a>
                </td>
            </tr>
            {foreachelse}
            <tr>
            	<td colspan="2">找不到记录</td>
            </tr>
            {/foreach}  
	    </tbody>
	    <tfoot>
            <tr>
                <td colspan="9">{include file="common/pagination.tpl"}</td>
            </tr>
        </tfoot>
	</table>
	{include file="common/jquery_ui.tpl"}
     <script>
	$(function(){
		bindDeleteEvent();
	});
    </script>
	 
{include file="common/my_footer.tpl"}