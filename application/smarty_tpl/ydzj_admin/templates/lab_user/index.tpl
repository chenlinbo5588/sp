{include file="common/main_header.tpl"}
{config_load file="member.conf"}
	{include file="./user_common.tpl"}
	<div class="fixed-empty"></div>
    <div class="feedback">{$feedback}</div>
	  {form_open(admin_site_url('lab_user/index'),'id="formSearch"')}
	      <input type="hidden" name="page" value="{$currentPage}"/>
	      <table class="tb-type1 noborder search">
	      <tbody>
	        <tr>
	          <th><label for="search_goods_name">名称:</label></th>
	          <td>
	          	<input type="text" class="txt" name="name" value="{$smarty.get.name}" placeholder="请输入实验员名称" />
	          	<a href="{admin_site_url('lab_user/export/?')}{$queryStr}" title="导出到EXCEL">导出到EXCEL</a><span class="tip">&lt;&lt;&lt;请右键目标另存为</span>
	          </td>
	          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
	        </tr>
	      </tbody>
	    </table>
	  </form>
	  <div>
		<table class="table tb-type2">
		    <colgroup>
		        <col style="width:10%"/>
		        <col style="width:15%"/>
		        <col style="width:10%"/>
		        <col style="width:10%"/>
		        <col style="width:10%"/>
		        <col style="width:20%"/>
		        <col style="width:20%"/>
		    </colgroup>
		    <thead>
		        <tr>
		            <th>序号</th>
		            <th>登陆账号</th>
		            <th>名称</th>
		            <th>状态</th>
		            <th>录入人</th>
		            <th>录入时间</th>
		            <th>操作</th>
		        </tr>
		    </thead>
		    <tbody>
		        {foreach from=$data['data'] key=key item=item}
	            <tr id="row_{$item['id']}" {if $key % 2 == 0}class="odd"{else}class="even"{/if}>
	                <td>{$item['id']}</td>
	                <td>{$item['account']|escape}</td>
	                <td>{$item['name']|escape}</td>
	                <td>{$item['status']|escape}</td>
	                <td>{$item['creator']|escape}</td>
	                <td>{time_tran($item['gmt_create'])}</td>
	                <td>
	                    <a href="{admin_site_url('lab_user/edit?id=')}{$item['id']}">编辑</a>&nbsp;
	                    <a class="delete" href="javascript:void(0);" data-href="{admin_site_url('lab_user/delete?id=')}{$item['id']}" data-title="确定删除{$item['name']|escape}吗?">删除</a>&nbsp;
	                </td>
	            </tr>
	            {/foreach}  
		    </tbody>
		    <tfoot>
	            <tr>
	                <td colspan="7">{include file="common/pagination.tpl"}</td>
	            </tr>
	        </tfoot>
		</table>
	</div>
{include file="common/main_footer.tpl"}