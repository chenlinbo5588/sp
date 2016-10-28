{include file="common/my_header.tpl"}
  {form_open(site_url($uri_string),'name="formSearch" id="formSearch"')}
    <input type="hidden" name="page" value=""/>
    <input type="hidden" name="submit_type" id="submit_type" value="" />
    <div class="goods_search">
     <ul class="search_con clearfix">
        <li>
            <label class="ftitle">角色名称</label>
            <input type="text" value="{$smarty.post['keywords']}" name="keywords" class="txt" placeholder="请输入角色名称"/>
        </li>
        <li>
            <input class="master_btn" type="submit" name="search" value="查询"/>
        </li>
     </ul>
     <a class="master_btn position_a" style="right:0;top:10px;" href="{site_url('lab_role/add')}">添加角色</a>
    </div>
    <table class="fulltable style1">
      <thead>
        <tr class="thead">
          <th></th>
          <th>角色</th>
          <th>状态</th>
          <th>创建时间</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover">
      	  <td class="w24"><input name="id[]" group="chkVal" type="checkbox" value="{$item['id']}"></td>
          <td>{$item['name']|escape}</td>
          <td>{if $item['status'] == 1}开启{else}关闭{/if}</td>
          <td>{time_tran($item['gmt_create'])}</td>
          <td class="align-center"><a href="{site_url('lab_role/edit')}?id={$item['id']}">编辑</a></td>
        </tr>
      	{foreachelse}
      	<tr class="no_data">
          <td colspan="5">没有符合条件的记录</td>
        </tr>
        {/foreach}
      </tbody>
      <tfoot>
      	<tr>
      		<td colspan="5">
      			<label><input type="checkbox" class="checkall" name="chkVal">全选</label>&nbsp;
          		<a href="javascript:void(0);" class="action" onclick="$('#submit_type').val('1');go();"><span>开启</span></a>
          		<a href="javascript:void(0);" class="action" onclick="$('#submit_type').val('-1');go();"><span>关闭</span></a>
          		{include file="common/pagination.tpl"}
          	</td>
      	</tr>
      </tfoot>
    </table>
  </form>
  <script type="text/javascript">
	function go(){
		$("#formSearch").submit();
	}
  </script>
{include file="common/my_footer.tpl"}