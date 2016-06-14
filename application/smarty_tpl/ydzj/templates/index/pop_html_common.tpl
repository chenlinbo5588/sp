<div id="maskbg"></div>
	<div id="regbg" style="display:none;">
		<div id="reg">
			<h4><strong>{$popWinTitle}</strong><a name="md">X</a></h4>
   			{form_open(site_url('index/index/'|cat:$siteIndex|cat:'#md'),'id="registerForm"')}
	        {include file="./site_form_hidden.tpl"}
   			<div class="mobile"><input type="text" class="txt noround" autocomplete="off" name="mobile" id="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/></div>
   			<div class="tiparea">{form_error('mobile')}</div>
   			<div class="auth_code"><input type="text" class="txt noround" name="auth_code" autocomplete="off" value="" placeholder="请输入您的验证码"/><input type="button" class="getCode noround" name="authCodeBtn" value="获取验证码"/></div>
   			<div class="tiparea">{form_error('auth_code')}</div>
   			<div class="btn2"><input class="t4" type="submit" name="tj" value="{$popBtnTitle}"/></div>
   			</form>
   		</div>
	</div>