{include file="common/main_header.tpl"}
{config_load file="member.conf"}
  {form_open_multipart(admin_site_url('member/add'),'id="user_form"')}
    <table class="table tb-type2">
      <tbody>
      	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="username">登陆账号:</label>{form_error('username')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['username']|escape}" name="username" id="username" class="txt"></td>
          <td class="vatop tips">英文字母、数字、中文,最少2个字符，最多10个字符</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="mobile">手机号码:</label>{form_error('mobile')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['mobile']}" name="mobile" id="mobile" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        {include file="./field_common.tpl"}
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
{include file="member/member_common.tpl"}
{include file="common/main_footer.tpl"}