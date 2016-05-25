<div id="regbg">
   			<div id="reg">
	   			{form_open(site_url('index/index'),'id="registerForm"')}
		        {include file="./site_form_hidden.tpl"}
	   			<div class="username"><input type="text" class="txt noround" autocomplete="off" name="username" value="{set_value('username')}" placeholder="请输入用户名称"/></div>
	   			<div class="tiparea">{form_error('username')}</div>
	   			<div class="mobile"><input type="text" class="txt noround" autocomplete="off" name="mobile" id="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/></div>
	   			<div class="tiparea">{form_error('mobile')}</div>
	   			<div class="auth_code"><input type="text" class="txt noround" name="auth_code" autocomplete="off" value="" style="width:70%" placeholder="请输入您的验证码"/><input type="button" class="getCode noround" name="authCodeBtn" value="获取验证码"/></div>
	   			<div class="tiparea">{form_error('auth_code')}</div>
	   			<div class="sb"><input class="txt" type="submit" value="免费注册" /></div>
	   			</form>
	   		</div>
   		</div>