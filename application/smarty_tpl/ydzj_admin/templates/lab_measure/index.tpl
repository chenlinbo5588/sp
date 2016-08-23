{include file="common/main_header.tpl"}
    {config_load file="goods.conf"}
    {include file="./measure_common.tpl"}
	<div class="fixed-empty"></div>
    <div class="feedback">{$feedback}</div>
	{form_open(admin_site_url('lab_measure/index'),'id="formSearch"')}
	    <input type="hidden" name="page" value="{$currentPage}"/>
	    <table class="tb-type1 noborder search">
	      <tbody>
	      	<tr>
	          <td><label>{#measure_title#}名称:</label></td>
	          <td><input type="text" class="txt" name="name" value="{$smarty.post.name}" placeholder="请输入{#measure_title#}名称" /></td>
	          <td><input type="submit" class="msbtn" value="查询" /></td>
	       	</tr>
	      </tbody>
	    </table>
	  
		<table class="rounded-corner">
		    <colgroup>
		        <col style="width:10%"/>
		        <col style="width:30%"/>
		        <col style="width:10%"/>
		        <col style="width:10%"/>
		        <col style="width:10%"/>
		    </colgroup>
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
	                	<a href="{admin_site_url('lab_measure/edit?id=')}{$item['id']}">编辑</a>
	                	<a class="delete" href="javascript:void(0);" data-id="{$item['id']}" data-url="{admin_site_url('lab_measure/delete?id=')}{$item['id']}" data-title="确定删除{$item['name']|escape}吗?">删除</a>
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
{include file="common/main_footer.tpl"}