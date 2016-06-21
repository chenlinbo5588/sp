{include file="common/main_header.tpl"}
{config_load file="member.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>会员管理</h3>
      <ul class="tab-base">
        <li><a href="{admin_site_url('member/index')}"><span>管理</span></a></li>
        {include file="common/sub_topnav.tpl"}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  {$feedback}
  {form_open_multipart(admin_site_url('member/edit/?uid='|cat:$uid),'id="user_form"')}
  	<input type="hidden" name="uid" value="{$info['uid']}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label>用户名称:</label>{form_error('username')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['username']|escape}" name="username" id="username" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="mobile">手机号码:</label>{form_error('mobile')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="mobile" value="{$info['mobile']}" name="mobile" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="required">性别:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><ul>
              <li>
                <label><input type="radio" value="S" {if $info['sex'] == 'S'}checked{/if} name="sex">保密</label>
              </li>
              <li>
                <label><input type="radio" value="M" {if $info['sex'] == 'M'}checked{/if} name="sex">男</label>
              </li>
              <li>
                <label><input type="radio" value="F" {if $info['sex'] == 'F'}checked{/if} name="sex">女</label>
              </li>
            </ul></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="qq">QQ:</label>{form_error('qq')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['qq']}" id="qq" name="qq" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="required">微信号:</label>{form_error('weixin')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['weixin']}" id="weixin" name="weixin" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2"><label class="required">{#reg_domain#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">{$info['reg_domain']}</td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>{#channel_name#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">{$info['channel_name']}</td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2"><label class="required">{#channel_code#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">{$info['channel_code']}</td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2"><label class="required">{#channel_word#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">{$info['channel_word']}</td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2"><label class="required">{#reg_origname#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">{$info['reg_origname']}</td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>{#reg_orig#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">{$info['reg_orig']}</td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>{#reg_date#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">{$info['reg_date']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td class="vatop tips"></td>
        </tr>
         <tr>
          <td colspan="2" class="required"><label>{#reg_ip#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">{$info['reg_ip']}</td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          {if $action != 'detail'}<td colspan="15"><input type="submit" name="submit" value="保存" class="msbtn"/></td>{/if}
        </tr>
      </tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}