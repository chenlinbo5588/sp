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
          <th class="align-center">真实名称</th>
          <th class="align-center">上次登录</th>
          <th class="align-center">权限组</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover">
          <td class="w24"><input name="del_id[]" type="checkbox" value="{$item['uid']}" {if {$item['uid']} == 1}disabled="disabled"{/if}></td>
          <td>{$item['email']}</td>
          <td class="align-center">{$item['username']}</td>
          <td class="align-center">{$item['last_login']|date_format:"%Y-%m-%d"}</td>
          <td class="align-center">{$item['group_id']}</td>
          <td class="w150 align-center">超级管理员不可编辑</td>
        </tr>
        {/foreach}
      </tbody>
      <tfoot>
      	<tr class="tfoot">
          <td><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal"></td>
          <td colspan="16"><label for="checkallBottom">全选</label>&nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('您确定要删除吗?')){ $('#form_admin').submit(); } "><span>删除</span></a>
            	{include file="common/pagination.tpl"}
           </td>
        </tr>
       </tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}