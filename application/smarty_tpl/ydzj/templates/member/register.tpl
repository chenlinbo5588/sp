{include file="common/header.tpl"}

<div id="register" class="handle_area">
    <div>{$feedback}</div>
    {form_open(site_url('member/register'),'id="registerForm"')}
        <input type="hidden" name="inviter" value="{$inviter}"/>
        <input type="hidden" name="inviteFrom" value="{$inviteFrom}"/>
        <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
        {form_error('mobile')}
        <div class="row">
            <label class="side_lb" for="mobile_text">手机号：</label>
            <input id="mobile_text" class="at_txt" type="text" name="mobile" value="{set_value('mobile')}" placeholder="请输入您常用的11位手机号码"/>
        </div>
        <div class="row">
            <label class="side_lb" for="authcode_text"></label>
            <input class="primaryBtn greenBtn at_txt" type="button" name="authCodeBtn" value="发送手机验证码"/>
        </div>
        {form_error('auth_code')}
        <div class="row">
            <label class="side_lb" for="authcode_text">验证码：</label><input id="authcode_text" class="at_txt" type="text" name="auth_code" value="{set_value('auth_code')}" placeholder="请输入4位数字验证码"/>
        </div>
        {form_error('nickname')}
        <div class="row">
            <label class="side_lb" for="nickname_text">昵称：</label><input id="nickname_text" class="at_txt" type="text" name="nickname" value="{set_value('nickname')}" placeholder="如:阿波"/>
        </div>
        {form_error('psw')}
        <div class="row">
            <label class="side_lb" for="password_text">登陆密码：</label><input id="password_text" class="at_txt" type="password" name="psw" value="{set_value('psw')}" placeholder="登陆密码"/>
        </div>
        {form_error('psw_confirm')}
        <div class="row">
            <label class="side_lb" for="psw_confirm_text">密码确认：</label><input id="psw_confirm_text" class="at_txt" type="password" name="psw_confirm" value="{set_value('psw_confirm')}" placeholder="登陆密码确认"/>
        </div>
        {form_error('agreee_licence')}
        <div class="row">
            <label class="side_lb" for="agreee_licence_text">&nbsp;</label><label><input id="agreee_licence_text" type="checkbox" name="agreee_licence" value="yes" {set_checkbox('agreee_licence','yes')}/>同意注册条款&nbsp;</label><a href="javascript:void(0);">显示注册条款</a>
        </div>
        <div class="row"><input class="primaryBtn" type="submit" name="register" value="注册"/></div>
    </form>
    <div class="row center" style="margin:0 0 20px 0;"><a class="" href="{site_url('member/login')}?teamjoin={$param}" title="登陆">登陆</a>
</div>
<script>

var authCodeURL ="{site_url('api/register/authcode')}";
</script>
<script src="{base_url('js/register.js')}" type="text/javascript"></script>
{include file="common/footer.tpl"}