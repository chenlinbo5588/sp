{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>角色设置</h3>
      <ul class="tab-base">
      	<li><a class="current"><span>角色</span></a></li>
      	<li><a href="{admin_site_url('authority/role_add')}"><span>添加角色</span></a></li>
      </ul>
     </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" id='form_admin'>
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <thead>
        <tr class="space">
          <th colspan="15" class="nobg">列表</th>
        </tr>
        <tr class="thead">
          <th><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal"></th>
          <th>角色</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	<tr class="no_data">
          <td colspan="10">没有符合条件的记录</td>
        </tr>
      </tbody>
      <tfoot></tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}