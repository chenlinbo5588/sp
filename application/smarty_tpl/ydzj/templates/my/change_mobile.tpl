{include file="./my_header.tpl"}
	<div class="w-step-row">
		<div id="stepVerify" class="w-step5 w-step-cur">原手机号码验证</div>
		<div id="stepInfo" class="w-step5 w-step-cur-future">绑定新得手机号码</div>		
		<div class="w-step5 w-step-future-future">更换结果</div>
	</div>
	
	<div class="warning">您更换了手机号之后，为了保留原先聊天窗口中好友关系,聊天窗口将还是以原先的手机账号登陆</div>
	{form_open(site_url($uri_string),"id='editForm'")}
	<table class="fulltable style1">
		<tbody>
			<tr>
				<td class="w120">手机账号</td>
				<td><input type="hidden" name="mobile" id="mobile" value="{$profile['basic']['mobile']}"/>{mask_mobile($profile['basic']['mobile'])} <input class="master_btn greenBtn mcode" data-mcode="#mobile" type="button" name="authCodeBtn" value="免费获得验证码"/></td>
			</tr>
			<tr>
				<td>手机验证码</td>
				<td><input type="text" class="w25pre" name="mobile_auth_code" value="" placeholder="填写您手机上收到的6位数字验证码"/>{form_error('mobile_auth_code')}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" class="master_btn" name="tijiao" value="下一步"/></a></td>
			</tr>
		</tbody>
	</table>
	</form>
{include file="./my_footer.tpl"}

