{include file="common/main_header.tpl"}
  {form_open_multipart(admin_site_url('member/edit?id='|cat:$info['uid']),'id="user_form"')}
    <table class="table tb-type2">
      <tbody>
      	<tr class="noborder">
          <td colspan="2" class="required"><label>用户UID:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <p>{$info['uid']}</p>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>登陆账号:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <p>{$info['username']}</p>
            <p>{if $info['avatar_m']}<img src="{resource_url($info['avatar_m'])}"/>{else}暂无头像{/if}</p>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>手机号码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <p><input type="text"  id="email" value="{$info['mobile']|escape}" name="mobile" class="txt">{form_error('mobile')}</p>
          </td>
          <td class="vatop tips"></td>
        </tr>
        {include file="./field_common.tpl"}
        <tr>
          <td colspan="2" class="required"><label>积分:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">积分&nbsp;<strong class="red">{$info['credits']}</strong>&nbsp;</td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
{include file="member/member_common.tpl"}
{include file="common/main_footer.tpl"}