{include file="common/main_header_navs.tpl"}
  {config_load file="member.conf"}
  {form_open_multipart(site_url($uri_string),'id="user_form"')}
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="member_mobile">登陆账号(中国大陆手机号码):</label>{form_error('member_mobile')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{set_value('member_mobile')}" name="member_mobile" id="member_mobile" class="txt"></td>
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
          <td class="vatop rowform"><input type="text"  id="member_email" value="{set_value('member_email')}" name="member_email" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="member_username">真实姓名:</label>{form_error('member_username')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="member_username" value="{set_value('member_username')}" name="member_username" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>性别:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><ul>
              <li>
                <label><input type="radio" checked="checked" value="0" {set_radio('member_sex','0')} name="member_sex">保密</label>
              </li>
              <li>
                <label><input type="radio" value="1" {set_radio('member_sex','1')} name="member_sex">男</label>
              </li>
              <li>
                <label><input type="radio" value="2" {set_radio('member_sex','2')} name="member_sex">女</label>
              </li>
            </ul></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="member_qq">QQ:</label>{form_error('member_qq')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{set_value('member_qq')}" id="member_qq" name="member_qq" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="member_weixin">微信号:</label>{form_error('member_weixin')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{set_value('member_weixin')}" id="member_weixin" name="member_weixin" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>头像:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <input type="hidden" name="avatar_id" value=""/>
            <span class="type-file-show">
            <img class="show_image" src="{resource_url('img/preview.png')}">
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
            <label for="allowtalk_1" class="cb-enable selected"><span>允许</span></label>
            <label for="allowtalk_2" class="cb-disable" ><span>禁止</span></label>
            <input id="allowtalk_1" name="allowtalk" value="Y" {set_radio('allowtalk','Y')} type="radio"/>
            <input id="allowtalk_2" name="allowtalk" value="N" {set_radio('allowtalk','N')} type="radio"/></td>
          <td class="vatop tips">如果禁止该项则会员不能发表咨询和发送站内信</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>允许登录:</label>{form_error('freeze')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff">
            <label for="memberstate_1" class="cb-enable selected" ><span>允许</span></label>
            <label for="memberstate_2" class="cb-disable" ><span>禁止</span></label>
            <input id="memberstate_1" name="memberstate" value="N" {set_radio('memberstate','N')} type="radio"/>
            <input id="memberstate_2" name="memberstate" value="Y" {set_radio('memberstate','Y')} type="radio"/></td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
{include file="member/member_common.tpl"}
<script type="text/javascript">
$(function(){
    
    $('#user_form').validate({
        errorPlacement: function(error, element){
            error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            member_mobile: {
                required : true,
                phoneChina:true,
                remote   : {
                    url :'{site_url('common/member_check')}',
                    type:'get',
                    data:{
                    	keyword: 'mobile',
                    	value : function(){
                            return $('#member_mobile').val();
                        }
                    }
                }
            },
            member_passwd: {
                maxlength: 20,
                minlength: 6
            },
            member_passwd2: {
                maxlength: 20,
                minlength: 6,
                equalTo:"#member_passwd"
            },
            member_email   : {
                email : true
                
            },
            member_qq : {
                digits: true,
                minlength: 5,
                maxlength: 11
            }
        },
        messages : {
            member_mobile: {
                required : '登陆账号不能为空',
                remote   : '登陆账号已经被注册，请您换一个'
            },
            member_passwd : {
                maxlength: '密码长度应在6-20个字符之间',
                minlength: '密码长度应在6-20个字符之间'
            },
            member_passwd2 : {
                maxlength: '密码长度应在6-20个字符之间',
                minlength: '密码长度应在6-20个字符之间',
                equalTo: '两次密码不一致'
            },
            member_email  : {
                email   : '请您填写有效的电子邮箱'
            },
            member_qq : {
                digits: '请输入正确的QQ号码',
                minlength: '请输入正确的QQ号码',
                maxlength: '请输入正确的QQ号码'
            }
        }
    });
});
</script>
{include file="common/main_footer.tpl"}