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
  {form_open(admin_site_url('authority/user'),'id="form_admin"')}
  	<input type="hidden" name="page" value="{$currentPage}"/>
  	<input type="hidden" name="submit_type" id="submit_type" value="" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>用户名称</td>
          <td><input type="text" value="{$smarty.post['username']}" name="username" class="txt"></td>
          <td>用户邮箱</td>
          <td><input type="text" value="{$smarty.post['email']}" name="email" class="txt"></td>
          <td>
            <input type="submit" class="msbtn" name="tijiao" value="查询"/>
          </td>
        </tr>
      </tbody>
    </table>
    
    <table class="table tb-type2">
      <thead>
        <tr class="space">
          <th colspan="6" class="nobg">列表</th>
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
          <td class="w24"><input name="del_id[]" group="chkVal" type="checkbox" value="{$item['uid']}"></td>
          <td>{$item['email']}</td>
          <td class="align-center">{$item['username']}</td>
          <td class="align-center">{$item['last_login']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td class="align-center">{$roleList[$item['group_id']]}</td>
          <td class="align-center"><a href="{admin_site_url('authority/user_edit')}?id={$item['uid']}">编辑</a></td>
        </tr>
        {/foreach}
      </tbody>
      <tfoot>
      	<tr class="tfoot">
          <td colspan="6">
          		<a href="javascript:void(0);" class="btn" onclick="$('#submit_type').val('switchON');go();"><span>开启</span></a><a href="javascript:void(0);" class="btn" onclick="$('#submit_type').val('switchOFF');go();"><span>关闭</span></a>
            	{include file="common/pagination.tpl"}
           </td>
        </tr>
       </tfoot>
    </table>
  </form>
  <script type="text/javascript">
	function go(){
		$("#form_admin").submit();
	}
  </script>
{include file="common/main_footer.tpl"}