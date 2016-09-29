{include file="common/header.tpl"}
	<div class="boxz">
	    <ul id="register" class="panel">
	    {form_open(site_url('member/forget'),'id="forgetForm"')}
	        <li class="title"><h1>找回密码</h1></li>
	        <li class="tip">{$feedback}</li>
	        
	        <li class="row">
	           <label><input type="radio" name="find_way" value="way_email" {set_radio('find_way','way_email')}/>通过邮箱找回</label>
	           <label><input type="radio" name="find_way" value="way_mobile" {set_radio('find_way','way_mobile')}/>通过手机找回</label>
            </li>
            <li class="tip way_email">{form_error('email')}</li>
            <li class="row rel">
                <input id="mobile_text" class="at_txt" type="text" name="email" value="{set_value('email')}" placeholder="请输入您绑定的邮箱地址"/>
            </li>
	        <li class="tip way_email">{form_error('mobile')}</li>
	        <li class="row rel">
	            <input id="mobile_text" class="at_txt" type="text" name="mobile" value="{set_value('mobile')}" placeholder="请输入您常用的11位手机号码"/>
	            <input id="mobile_authcode" class="master_btn greenBtn" type="button" name="authCodeBtn" value="免费获取验证码"/>
	        </li>
	        <li>{form_error('auth_code')}</li>
            <li class="row rel">
                <input id="authcode_text" class="at_txt" type="text" autocomplete="off" name="auth_code" value="{set_value('auth_code')}" placeholder="请输入4位验证码"/>
                <div class="codeimg" title="点击图片刷新">正在获取验证码...</div>
            </li>
	        <li class="tip">{form_error('auth_code')}</li>
	        <li class="row">
	            <input id="authcode_text" class="at_txt" type="text" name="auth_code" value="{set_value('auth_code')}" placeholder="请输入您收到的6位数字验证码"/>
	        </li>
	        <li class="row"><input class="master_btn" type="submit" name="register" value="注册"/></li>
	    </form>
	    </ul>
	</div>
	<script>
	   var authCodeURL ="{site_url('api/register/authcode')}";
	</script>
	<script src="{resource_url('js/register.js')}" type="text/javascript"></script>
{include file="common/footer.tpl"}