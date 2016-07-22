{include file="common/main_header.tpl"}
{config_load file="member.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
        <li><a href="{admin_site_url('member')}" ><span>{#manage#}</span></a></li>
        <li><a href="{admin_site_url('member/add')}" class="current"><span>{#add#}</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {form_open_multipart(admin_site_url('member/add'),'id="user_form"')}
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="mobile">登陆账号(中国大陆手机号码):</label>{form_error('mobile')}</td>
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