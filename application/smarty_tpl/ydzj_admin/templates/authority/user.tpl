{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>权限设置</h3>
      <ul class="tab-base">
      	<li><a class="current"><span>管理员</span></a></li>
      	<li><a href="{admin_site_url('authority/user_add')}"><span>添加管理员</span></a></li>
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
          <th>登录名</th>
          <th class="align-center">上次登录</th>
          <th class="align-center">登录次数</th>
          <th class="align-center">权限组</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	<tr class="hover">
          <td class="w24"><input name="del_id[]" type="checkbox" value="1" disabled="disabled"></td>
          <td>admin</td>
          <td class="align-center">2016-04-08 13:08:41</td>
          <td class="align-center">2</td>
          <td class="align-center"></td>
          <td class="w150 align-center">超级管理员不可编辑</td>
        </tr>
      </tbody>
      <tfoot>
      	<tr class="tfoot">
          <td><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal"></td>
          <td colspan="16"><label for="checkallBottom">全选</label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('您确定要删除吗?')){
            	$('#form_admin').submit();}"><span>删除</span></a>
            <div class="pagination"> <ul><li><span>首页</span></li><li><span>上一页</span></li><li><span class="currentpage">1</span></li><li><span>下一页</span></li><li><span>末页</span></li></ul> </div></td>
        </tr>
       </tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}