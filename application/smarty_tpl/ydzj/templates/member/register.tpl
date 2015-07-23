{include file="common/header.tpl"}

<div id="register" class="handle_area">
    <div>{$feedback}</div>
    {form_open(site_url('member/register'))}
        <div class="row">
            <label class="side_lb" for="email_text">用户名:</label><input id="email_text" class="at_txt" type="text" name="email" value="{set_value('email')}" placeholder="如:example@163.com"/>
        </div>
        {form_error('email')}
        <div class="row">
            <label class="side_lb" for="nickname_text">昵称:</label><input id="nickname_text" class="at_txt" type="text" name="nickname" value="{set_value('nickname')}" placeholder="如:阿波"/>
        </div>
        {form_error('nickname')}
        <div class="row">
            <label class="side_lb" for="password_text">登陆密码:</label><input id="password_text" class="at_txt" type="password" name="psw" value="{set_value('psw')}" placeholder="密码"/>
        </div>
        {form_error('psw')}
        <div class="row">
            <label class="side_lb" for="psw_confirm_text">密码确认:</label><input id="psw_confirm_text" class="at_txt" type="password" name="psw_confirm" value="{set_value('psw_confirm')}" placeholder="密码确认"/>
        </div>
        {form_error('psw_confirm')}
        <div class="row">
            <label class="side_lb" for="agreee_licence_text">&nbsp;</label><input id="agreee_licence_text" type="checkbox" name="agreee_licence" value="yes" {set_checkbox('agreee_licence','yes')}/>同意注册条款&nbsp;<a href="javascript:void(0);">显示注册条款</a>
        </div>
        {form_error('agreee_licence')}
        <div class="row"><input class="primaryBtn" type="submit" name="register" value="注册"/></div>
        <div class="row"><a href="{site_url('member/login')}" title="登陆">登陆</a>
    </form>
</div>


{include file="common/footer.tpl"}