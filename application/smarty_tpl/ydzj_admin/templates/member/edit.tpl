{include file="common/main_header.tpl"}
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>会员管理</h3>
      <ul class="tab-base">
        <li><a href="{admin_site_url('member')}" ><span>管理</span></a></li>
        <li><a href="{admin_site_url('member/add')}" ><span>新增</span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>编辑</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  {$feedback}
  {form_open_multipart(admin_site_url('member/edit/'|cat:$id),'id="user_form"')}
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label>登陆账号:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            
            <p>{$info['mobile']}</p>
            <p><img src="{base_url($info['avatar_middle'])}"/></p>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="member_nickname">昵称:</label>{form_error('member_nickname')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" {if $inpost}value="{set_value('member_nickname')}"{else}value="{$info['nickname']|escape}"{/if} name="member_nickname" id="member_nickname" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="member_passwd">密码:</label>{form_error('member_passwd')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="member_passwd" value="{set_value('member_passwd')}" name="member_passwd" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="member_passwd2">密码确认:</label>{form_error('member_passwd2')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="member_passwd2" value="{set_value('member_passwd2')}" name="member_passwd2" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="member_email">电子邮箱:</label>{form_error('member_email')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text"  id="member_email" {if $inpost}value="{set_value('member_email')}"{else}value="{$info['email']|escape}"{/if} name="member_email" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="member_username">真实姓名:</label>{form_error('member_username')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="member_username" {if $inpost}value="{set_value('member_username')}"{else}value="{$info['username']|escape}"{/if} name="member_username" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>性别:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><ul>
              <li>
                <label><input type="radio" value="S" {if $inpost}{set_radio('member_sex','S')}{else}{if $info['sex'] == 'S'}checked{/if}{/if} name="member_sex">保密</label>
              </li>
              <li>
                <label><input type="radio" value="M" {if $inpost}{set_radio('member_sex','M')}{else}{if $info['sex'] == 'M'}checked{/if}{/if} name="member_sex">男</label>
              </li>
              <li>
                <label><input type="radio" value="F" {if $inpost}{set_radio('member_sex','F')}{else}{if $info['sex'] == 'F'}checked{/if}{/if} name="member_sex">女</label>
              </li>
            </ul></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="member_qq">QQ:</label>{form_error('member_qq')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" {if $inpost}value="{set_value('member_qq')}"{else}value="{$info['qq']}"{/if} id="member_qq" name="member_qq" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="member_weixin">微信号:</label>{form_error('member_weixin')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" {if $inpost}value="{set_value('member_weixin')}"{else}value="{$info['weixin']}"{/if} id="member_weixin" name="member_weixin" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>头像:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <input type="hidden" name="avatar_id" value=""/>
            <span class="type-file-show">
            <img class="show_image" src="{base_url('img/preview.png')}">
            <div class="type-file-preview" style="display: none;"><img id="view_img"></div>
            </span>
            <span class="type-file-box">
              <input type='text' name='member_avatar' id='member_avatar' class='type-file-text' />
              <input type='button' name='button' id='button' value='' class='type-file-button' />
              <input name="_pic" type="file" class="type-file-file" id="_pic" size="30" hidefocus="true" />
            </span>
            </td>
          <td class="vatop tips">支持格式jpg</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>允许发表言论:</label>{form_error('allowtalk')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff">
            <label for="allowtalk_1" class="cb-enable{if $info['allowtalk'] == 'Y'} selected{/if}"><span>允许</span></label>
            <label for="allowtalk_2" class="cb-disable{if $info['allowtalk'] == 'N'} selected{/if}" ><span>禁止</span></label>
            <input id="allowtalk_1" name="allowtalk" value="Y" {if $inpost}{set_radio('allowtalk','Y')}{else}{if $info['allowtalk'] == 'Y'}checked{/if}{/if} type="radio"/>
            <input id="allowtalk_2" name="allowtalk" value="N" {if $inpost}{set_radio('allowtalk','N')}{else}{if $info['allowtalk'] == 'N'}checked{/if}{/if} type="radio"/></td>
          <td class="vatop tips">如果禁止该项则会员不能发表咨询和发送站内信</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>允许登录:</label>{form_error('freeze')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff">
            <label for="memberstate_1" class="cb-enable{if $info['freeze'] == 'N'} selected{/if}" ><span>允许</span></label>
            <label for="memberstate_2" class="cb-disable{if $info['freeze'] == 'Y'} selected{/if}" ><span>禁止</span></label>
            <input id="memberstate_1" name="memberstate" value="N" {if $inpost}{set_radio('memberstate','N')}{else}{if $info['freeze'] == 'N'}checked{/if}{/if} type="radio"/>
            <input id="memberstate_2" name="memberstate" value="Y" {if $inpost}{set_radio('memberstate','Y')}{else}{if $info['freeze'] == 'Y'}checked{/if}{/if} type="radio"/></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>积分:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">积分&nbsp;<strong class="red">{$info['credits']}</strong>&nbsp;</td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>可用余额:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">可用&nbsp;<strong class="red">{$info['money']}</strong>&nbsp;元</td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>冻结金额:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">冻结&nbsp;<strong class="red">{$info['money_freeze']}</strong>&nbsp;元</td>
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
</div>
<script type="text/javascript" src="{js_url('js/dialog/dialog.js')}" id="dialog_js"></script>
<script type="text/javascript" src="{js_url('js/jquery-ui/jquery.ui.js')}"></script>
<script type="text/javascript" src="{js_url('js/ajaxfileupload/ajaxfileupload.js')}"></script>
<script type="text/javascript" src="{js_url('js/jquery.Jcrop/jquery.Jcrop.js')}"></script>
<link href="{css_url('js/jquery.Jcrop/jquery.Jcrop.min.css')}" rel="stylesheet" type="text/css"/>
{include file="member/member_common.tpl"}

{include file="common/main_footer.tpl"}