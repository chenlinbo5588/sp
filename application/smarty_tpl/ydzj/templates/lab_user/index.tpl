{include file="common/main_header.tpl"}
{config_load file="member.conf"}
  {form_open(site_url('lab_user/index'),'id="formSearch"')}
      <input type="hidden" name="page" value=""/>
      <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="search_goods_name">名称:</label></th>
          <td>
          	<input type="text" class="txt" name="name" value="{$smarty.post.name}" placeholder="请输入实验员名称" />
          	{*<a href="{site_url('lab_user/export/?')}{$queryStr}" title="导出到EXCEL">导出到EXCEL</a><span class="tip">&lt;&lt;&lt;请右键目标另存为</span>*}
          </td>
          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
        </tr>
      </tbody>
    </table>
  </form>
	<table class="table tb-type2">
	    <thead>
	        <tr>
	            <th class="first">序号</th>
	            <th>登陆账号</th>
	            <th>名称</th>
	            <th>实验室管理员</th>
	            <th>角色分组</th>
	            <th>状态</th>
	            <th>录入人</th>
	            <th>录入时间</th>
	            <th class="last">操作</th>
	        </tr>
	    </thead>
	    <tbody>
	        {foreach from=$data['data'] key=key item=item}
            <tr id="row{$item['id']}" class="{if $key % 2 == 0}odd{else}even{/if}">
                <td>{$item['id']}</td>
                <td>{$item['account']|escape}</td>
                <td>{$item['name']|escape}</td>
                <td>{if $item['is_manager'] == 'y'}是{else}否{/if}</td>
                <td>{if $item['group_id'] == 0}无权限组{else}{$roleList[$item['group_id']]}{/if}</td>
                <td>{$item['status']|escape}</td>
                <td>{$item['creator']|escape}</td>
                <td>{time_tran($item['gmt_create'])}</td>
                <td>
                    <a href="{site_url('lab_user/edit?id=')}{$item['id']}">编辑</a>&nbsp;
                    <a class="delete" href="javascript:void(0);" data-id="{$item['id']}" data-url="{site_url('lab_user/delete?id=')}{$item['id']}" data-title="确定删除{$item['name']|escape}吗?">删除</a>&nbsp;
                </td>
            </tr>
            {/foreach}  
	    </tbody>
	    <tfoot>
            <tr>
                <td colspan="9">{include file="common/pagination.tpl"}</td>
            </tr>
        </tfoot>
	</table>
	<div id="dialog-confirm" title="删除{#title#}" style="display:none;"></div>
	{include file="common/jquery_ui.tpl"}
	<script>
	$(function(){
		bindDeleteEvent();
	});
  </script>
{include file="common/main_footer.tpl"}