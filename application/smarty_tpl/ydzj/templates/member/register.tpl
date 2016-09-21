{include file="common/header.tpl"}
	<div class="boxz">
	    <ul id="register" class="panel">
	    {form_open(site_url('member/register'),'id="registerForm"')}
	        <input type="hidden" name="inviter" value="{$inviter}"/>
	        <input type="hidden" name="inviteFrom" value="{$inviteFrom}"/>
	        <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
	        <li class="title"><h1>注册</h1></li>
	        <li class="tip">{$feedback}</li>
	        <li class="tip">{form_error('mobile')}</li>
	        <li class="row rel">
	            <input class="at_txt" type="text" name="mobile" value="{set_value('mobile')}" placeholder="请输入您常用的11位手机号码"/>
	            <input id="mobile_authcode" class="master_btn greenBtn" type="button" name="authCodeBtn" value="免费获取验证码"/>
	        </li>
	        <li class="tip">{form_error('mobile_auth_code')}</li>
            <li class="row">
                <input class="at_txt" type="text" name="mobile_auth_code" value="{set_value('mobile_auth_code')}" placeholder="请输入您手机收到的6位数字验证码"/>
            </li>
            <li class="tip">{form_error('nickname')}</li>
            <li class="row">
                <input class="at_txt" type="text" name="nickname" value="{set_value('nickname')}" placeholder="请输入您的昵称,字母、数字、中文,最长10个字"/>
            </li>
            <li class="tip">{form_error('email')}</li>
            <li class="row">
                <input class="at_txt" type="text" name="email" value="{set_value('email')}" placeholder="请输入您常用的邮箱地址"/>
            </li>
	        <li class="tip">{form_error('qq')}</li>
            <li class="row">
                <input class="at_txt" type="text" name="qq" value="{set_value('qq')}" placeholder="请输入QQ号码"/>
            </li>
	        <li class="tip">{form_error('psw')}</li>
	        <li class="row">
	            <input class="at_txt" type="password" name="psw" value="{set_value('psw')}" placeholder="密码长度6~12位,字母、数字、半角下划线、@符号"/>
	        </li>
	        <li class="tip">{form_error('psw_confirm')}</li>
	        <li class="row">
	            <input class="at_txt" type="password" name="psw_confirm" value="{set_value('psw_confirm')}" placeholder="登陆密码确认"/>
	        </li>
	        <li>{form_error('auth_code')}</li>
            <li class="row rel">
                <input class="at_txt" type="text" autocomplete="off" name="auth_code" value="{set_value('auth_code')}" placeholder="请输入右侧图片中4位验证码"/>
                <div class="codeimg" title="点击图片刷新">正在获取验证码...</div>
            </li>
	        <li class="row"><input class="master_btn" type="submit" name="register" value="注册"/></li>
	    </form>
	    </ul>
	</div>
	<script>
	   var authCodeURL ="{site_url('api/register/authcode')}",imgUrl = "{site_url('captcha/index')}";
	   var refreshImg = function(json){
           $(".codeimg").html(json.img);
        };
        
        
	   $(function(){
	   
	       $(".codeimg").bind("click",function(){
                setTimeout(function(){
                    $.getJSON(imgUrl + "?t=" + Math.random(),refreshImg);
                    $(".codeimg").html("正在刷新....");
                },300);
            });
	       
	       setTimeout(function(){
	            $.getJSON('{site_url('captcha/index')}',function(json){
	                $(".codeimg").html(json.img);
	            });
	        },300);
	        
        
	       $("#registerForm").bind("submit",function(e){
		        var mobile = $("input[name=mobile]").val();
		        if(!regMobile.test(mobile)){
		            alert("请输入正确的手机号码");
		            $("input[name=mobile]").focus();
		            return false;
		        }
		        
		        
		        if($("input[name=auth_code]").val() == '' ){
		            alert("请输入手机验证码");
		            $("input[name=auth_code]").focus();
		            return false;
		        }
		        
		        
		        return true;
		    });
	   });
	</script>
	<script src="{resource_url('js/register.js')}" type="text/javascript"></script>
{include file="common/footer.tpl"}