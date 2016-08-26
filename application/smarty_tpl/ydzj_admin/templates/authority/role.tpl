{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>角色管理</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('authority/role')}" class="current"><span>角色管理</span></a></li>
      	<li><a href="{admin_site_url('authority/role_add')}"><span>添加</span></a></li>
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
          <td>角色名称</td>
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
          {*<th></th>*}
          <th>角色</th>
          <th>创建人</th>
          <th>状态</th>
          <th>创建时间</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover">
      	  {*<td class="w24"><input name="id[]" group="chkVal" type="checkbox" value="{$item['id']}"></td>*}
          <td>{$item['name']|escape}</td>
          <td>{$item['creator']|escape}</td>
          <td>{$item['status']}</td>
          <td>{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td class="align-center"><a href="{admin_site_url('authority/role_edit')}?id={$item['id']}">编辑</a></td>
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
          		<a href="javascript:void(0);" class="btn"><span>开启</span></a>
          		<a href="javascript:void(0);" class="btn"><span>停用</span></a>*}
          		{include file="common/pagination.tpl"}
          	</td>
      	</tr>
      </tfoot>
    </table>
  </form>
  <script type="text/javascript">
	
	
	
	
  </script>
{include file="common/main_footer.tpl"}