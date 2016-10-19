{include file="common/my_header.tpl"}
	{$stepHTML}
	<div class="muted">您更换了手机号之后，为了保留原先聊天窗口中好友关系,聊天窗口将还是以原先的手机账号登陆</div>
	{form_open(site_url($uri_string),"id='editForm'")}
	<table class="fulltable style1 step{$step}">
	   <tbody>
	{if $step == 1}
			<tr>
				<td class="w120">验证码</td>
				<td>
					<div class="rel clearfix">
						<input class="fl" type="text" autocomplete="off" name="auth_code" value="" placeholder="请输入右侧图片中4位验证码"/>
	            		<div class="fl" class="codeimg" id="authImg" title="点击图片刷新">正在获取验证码...</div>
            		</div>
            	</td>
			</tr>
			<tr>
				<td class="w120">手机号码</td>
				<td><input type="hidden" name="mobile" id="mobile" value="{$profile['basic']['mobile']}"/>{mask_mobile($profile['basic']['mobile'])} <input class="master_btn greenBtn grayed mcode" disabled id="mobile_authcode1" data-mcode="#mobile" type="button" name="authCodeBtn" value="免费获得验证码"/></td>
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
				<td class="w120">验证码</td>
				<td>
					<div class="rel clearfix">
						<input class="fl" type="text" autocomplete="off" name="auth_code" value="" placeholder="请输入右侧图片中4位验证码"/>
	            		<div class="fl" class="codeimg" id="authImg" title="点击图片刷新">正在获取验证码...</div>
            		</div>
            	</td>
			</tr>
            <tr>
                <td class="w120">新手机号码</td>
                <td><input type="text" class="w25pre" name="newmobile" id="newmobile" value="{set_value('newmobile')}" placeholder="请输入新的手机号码"/><input class="master_btn greenBtn grayed mcode" disabled id="mobile_authcode1"  data-mcode="#newmobile" type="button" name="authCodeBtn" value="免费获得验证码"/>{form_error('newmobile')}</td>
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
    	$(function(){
    		var captchaCheck = "{site_url('captcha/check_captcha')}";
    		var imgCode1 = $.fn.imageCode({ wrapId: "#authImg", captchaUrl : captchaUrl });
		    setTimeout(imgCode1.refreshImg,500);
		    
		    var codeValidating = false;
		    var codeValidation = function(inputElem,code,targetElem){
		    	
		    	if(codeValidating){
		    		return;
		    	}
		    	
		    	codeValidating = true;
		    	inputElem.addClass("showloading");
		    	
		    	var faildFn = function(){
		    		inputElem.removeClass("showloading").addClass("error");
		    		targetElem.addClass("grayed").attr("disabled",true);
		    		codeValidating = false;
		    	};
		    	
		    	$.ajax({
		           type:'POST',
		           url:captchaCheck,
		           data: { captcha: code },
		           dataTpe:'json',
		           success:function(json){
		        	   codeValidating = false;
		        	   if(/成功/.test(json.message)){
		        		   inputElem.removeClass('showloading').addClass("valid");
		            	   targetElem.removeClass("grayed").attr("disabled",false);
		        	   }else{
		        		   faildFn();
		        	   }
		           },
		           error:function(){
		        	   faildFn();
		           }
		        });
		    };
		    
		    $("input[name=auth_code]").bind('keyup',function(){
		    	var code = $(this).val();
		    	if(code.length == 4){
		    		codeValidation($(this),code,$("#mobile_authcode1"));
		    	}else{
		    		$(this).removeClass('valid');
		    		$(this).removeClass("showloading")
		    		$("#mobile_authcode1").addClass("grayed").attr("disabled",true);
		    	}
		    });
    	});
    </script>
    <script src="{resource_url('js/getcode.js')}" type="text/javascript"></script>
{include file="common/my_footer.tpl"}

