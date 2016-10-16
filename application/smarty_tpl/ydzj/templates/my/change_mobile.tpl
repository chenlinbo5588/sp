{include file="common/my_header.tpl"}
	{$stepHTML}
	<div class="muted">您更换了手机号之后，为了保留原先聊天窗口中好友关系,聊天窗口将还是以原先的手机账号登陆</div>
	{form_open(site_url($uri_string),"id='editForm'")}
	<input type="hidden" name="step" value="{$step}"/>
	<table class="fulltable style1">
	   <tbody>
	{if $step == 1}
			<tr>
				<td class="w120">手机号码</td>
				<td><input type="hidden" name="mobile" id="mobile" value="{$profile['basic']['mobile']}"/>{mask_mobile($profile['basic']['mobile'])} <input class="master_btn greenBtn mcode" data-mcode="#mobile" type="button" name="authCodeBtn" value="免费获得验证码"/></td>
			</tr>
			<tr>
				<td>手机验证码</td>
				<td><input type="text" class="w25pre" name="mobile_auth_code" value="" placeholder="填写您手机上收到的6位数字验证码"/>{form_error('mobile_auth_code')}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" class="master_btn" name="tijiao" value="下一步"/></td>
			</tr>
	{else if $step == 2}
            <tr>
                <td class="w120">新手机号码</td>
                <td><input type="text" class="w25pre" name="newmobile" id="newmobile" value="{set_value('newmobile')}" placeholder="请输入新的手机号码"/><input class="master_btn greenBtn mcode" data-mcode="#newmobile" type="button" name="authCodeBtn" value="免费获得验证码"/>{form_error('newmobile')}</td>
            </tr>
            <tr>
                <td>手机验证码</td>
                <td><input type="text" class="w25pre" name="mobile_auth_code" value="" placeholder="填写您手机上收到的6位数字验证码"/>{form_error('mobile_auth_code')}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" class="master_btn" name="tijiao" value="下一步"/></td>
            </tr>
	{else if $step == 3}
	        <tr>
	           <td>
	               <p>您已成功修改手机号码.</p>
	           </td>
	        </tr>
	{/if}
	           
	       </tbody>
        </table>
    </form>
    
    <script>
    
    </script>
    <script src="{resource_url('js/getcode.js')}" type="text/javascript"></script>
{include file="common/my_footer.tpl"}

