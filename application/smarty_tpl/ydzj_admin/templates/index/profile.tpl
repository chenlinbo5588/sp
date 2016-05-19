{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>修改资料</h3>
      <ul class="tab-base">
      	<li><a class="current"><span>基本资料</span></a></li>
      </ul>
     </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {form_open(admin_site_url('index/profile'),'id="aform"')}
  <input type="hidden" name="uid" value="{$info['uid']}"/>
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="email">登录名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">{$manage_profile['basic']['email']}</td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="username">用户真实名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="username" value="{$manage_profile['basic']['username']|escape}" name="username" class="txt"></td>
          <td class="vatop tips">请输入用户真实名称 {form_error('username')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="admin_password">原密码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="password" id="old_password" name="old_password" class="txt"></td>
          <td class="vatop tips">请输入密码 最少六位 只能包含英文字母、数字、下划线、破折号 {form_error('old_password')}</td>
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
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}