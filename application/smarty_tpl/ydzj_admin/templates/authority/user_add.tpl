{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>管理员</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('authority/user')}"><span>列表</span></a></li>
      	{include file="common/sub_topnav.tpl"}
      </ul>
     </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {if $info['uid']}
  {form_open(admin_site_url('authority/user_edit'),'id="add_form"')}
  {else}
  {form_open(admin_site_url('authority/user_add'),'id="add_form"')}
  {/if}
  <input type="hidden" name="uid" value="{$info['uid']}"/>
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="email">登录名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="email" value="{$info['email']}" name="email" class="txt"></td>
          <td class="vatop tips">请输入邮箱登录名 {form_error('email')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="username">用户真实名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="username" value="{$info['username']|escape}" name="username" class="txt"></td>
          <td class="vatop tips">请输入用户真实名称 {form_error('username')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="admin_password">密码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="password" id="admin_password" name="admin_password" class="txt"></td>
          <td class="vatop tips">请输入密码 最少六位 只能包含英文字母、数字、下划线、破折号 {form_error('admin_password')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="admin_rpassword">确认密码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="password" id="admin_rpassword" name="admin_rpassword" class="txt"></td>
          <td class="vatop tips">请输入确认密码 {form_error('admin_rpassword')}</td>
        </tr>
        <tr>
          <td colspan="2"><label for="group_id">权限组:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="group_id">
          		<option value="0">请选择</option>
          	{foreach from=$roleList item=item}
          		<option {if $info['group_id'] == $item['id']}selected{/if} value="{$item['id']}">{$item['name']}</option>
          	{/foreach}
          	</select>
          </td>
          <td class="vatop tips">请选择一个权限组，如果还未设置，{form_error('gid')} <a href="{admin_site_url('authority/add_role')}">点击马上设置</a></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}