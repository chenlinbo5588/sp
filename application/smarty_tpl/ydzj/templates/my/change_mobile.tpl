{include file="common/my_header.tpl"}
	<div class="w-step-row">
		<div class="w-step3 {if $step > 1}w-step-past{else if $step == 1} w-step-cur{/if}">原手机号码验证</div>
		<div class="w-step3 {if $step > 2}w-step-past-past{else if $step == 2}w-step-past-cur{else}w-step-cur-future{/if}">绑定新得手机号码</div>		
		<div class="w-step3 {if $step < 3}w-step-future-future{else}w-step-past-cur{/if}">更换结果</div>
	</div>
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
				<td><input type="submit" class="master_btn" name="tijiao" value="下一步"/></a></td>
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

