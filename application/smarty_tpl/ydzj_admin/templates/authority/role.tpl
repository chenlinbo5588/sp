{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>权限组</h3>
      <ul class="tab-base">
      	{if isset($permission['admin/authority/role'])}<li><a class="current"><span>列表</span></a></li>{/if}
      	{if isset($permission['admin/authority/role_add'])}<li><a href="{admin_site_url('authority/role_add')}"><span>添加</span></a></li>{/if}
      </ul>
     </div>
  </div>
  <div class="fixed-empty"></div>
  
  {form_open(admin_site_url('authority/role'),'name="formSearch" id="formSearch"')}
    <input type="hidden" name="page" value="{$currentPage}"/>
    <input type="hidden" name="submit_type" id="submit_type" value="" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>权限组名称</td>
          <td><input type="text" value="{$smarty.post['keywords']}" name="keywords" class="txt"></td>
          <td>
            <input type="submit" class="msbtn" name="tijiao" value="查询"/>
          </td>
        </tr>
      </tbody>
    </table>
    <table class="table tb-type2">
      <thead>
        <tr class="space">
          <th colspan="5" class="nobg">列表</th>
        </tr>
        <tr class="thead">
          <th>序号</th>
          <th>角色</th>
          <th>状态</th>
          <th>创建时间</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover">
      	  {*<td class="w24"><input name="del_id[]" group="chkVal" type="checkbox" value="{$item['id']}"></td>*}
      	  <td>{$item['id']}</td>
          <td>{$item['name']|escape}</td>
          <td>{$item['status']}</td>
          <td>{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td class="align-center">{if isset($permission['admin/authority/role_edit'])}<a href="{admin_site_url('authority/role_edit')}?id={$item['id']}">编辑</a>{/if}</td>
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
      			{*<label><input type="checkbox" class="checkall" name="chkVal">全选</label>&nbsp;
          		<a href="javascript:void(0);" class="btn" onclick="$('#submit_type').val('开启');go();"><span>开启</span></a>
          		<a href="javascript:void(0);" class="btn" onclick="$('#submit_type').val('关闭');go();"><span>关闭</span></a>*}
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
{include file="common/main_footer.tpl"}