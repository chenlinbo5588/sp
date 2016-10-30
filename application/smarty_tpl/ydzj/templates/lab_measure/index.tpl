{include file="common/my_header.tpl"}
    {config_load file="goods.conf"}
    
	{form_open(site_url($uri_string),'id="formSearch"')}
	    <input type="hidden" name="page" value=""/>
	    <div class="goods_search">
	    <ul class="search_con clearfix">
	        <li>
	            <label class="ftitle" style="width:80px;">{#measure_title#}名称:</label>
	            <input type="text" value="{$smarty.post['name']}" name="name" class="txt" placeholder="请输入{#measure_title#}名称"/>
	        </li>
	        <li>
	            <input class="master_btn" type="submit" name="search" value="查询"/>
	        </li>
	     </ul>
	     <a class="master_btn position_a" style="right:0;top:10px;" href="{site_url('lab_measure/add')}">添加{#measure_title#}</a>
	     
	    </div>
		<table class="fulltable style1">
		    <thead>
		        <tr>
		            <th class="first">序号</th>
		            <th>度量名称</th>
		            <th>录入时间</th>
		            <th>录入人</th>
		            <th class="last">操作</th>
		        </tr>
		    </thead>
		    <tbody>
		        {foreach from=$data['data'] key=key item=item}
	            <tr id="row{$item['id']}" class="{if $key % 2 == 0}odd{else}even{/if}">
	                <td>{$item['id']}</td>
	                <td>{$item['name']|escape}</td>
	                <td>{time_tran($item['gmt_create'])}</td>
	                <td>{$item['creator']|escape}</td>
	                <td>
	                	<a href="{site_url('lab_measure/edit?id=')}{$item['id']}">编辑</a>
	                	<a class="delete" href="javascript:void(0);" data-id="{$item['id']}" data-url="{site_url('lab_measure/delete?id=')}{$item['id']}" data-title="确定删除{$item['name']|escape}吗?">删除</a>
	                </td>
	            </tr>
	            {/foreach}  
		    </tbody>
		    <tfoot>
	            <tr>
	                <td colspan="6">{include file="common/pagination.tpl"}</td>
	            </tr>
	        </tfoot>
		</table>
	</form>
	
	<script>
		$(function(){
			bindDeleteEvent();
		});
	</script>
{include file="common/my_footer.tpl"}