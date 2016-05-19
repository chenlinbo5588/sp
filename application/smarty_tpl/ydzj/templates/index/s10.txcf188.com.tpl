{include file="./site_common.tpl"}
<style type="text/css">
body {
	background:#800001;
}


#regbg {
	background:#e9e9e9;
	position: relative;
	padding:15px 25px;
}

img.responed {
	display: block;
}

#regbg div {
	margin:5px 0;
	position: relative;
}


#reg .txt {
	width:100%;
	border:1px solid #e0e0e0;
	height:37px;
	line-height:37px;
    text-indent: 40px;
}

.username .txt {
	background:#fff url('{resource_url("img/pg1/name_bg.png")}') no-repeat 10px center;
}

.mobile .txt {
	background:#fff url('{resource_url("img/pg1/mobile_bg.png")}') no-repeat 10px center;
}

.auth_code .txt {
	background:#fff url('{resource_url("img/pg1/code_bg.png")}') no-repeat 10px center;
}

#reg .getCode {
	height: 37px;
	line-height:37px;
	text-align:center;
	background:#d3d5da;
	border:0px;
	position: absolute;
    right: 0;
    width:28%;
    border-radius:0px;
    -webkit-border-radius:0px;
    -moz-border-radius:0px;
    -o-border-radius:0px;
    -webkit-appearance:none;
}

#reg .sb {
	
}

#reg .sb input {
	background:url('{resource_url("img/btns/btn_middle.png")}') repeat-x left top;
	color:#cb3232;
	font-size:22px;
	height:37px;
	line-height:37px;
	text-align:center;
	text-indent:0;
}

form label.error {
	display:block;
}

</style>
	<div id="wrap">
   		<div>
   			<img class="responed" src="{resource_url('img/pg10/pic1.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg10/pic2.png')}"/>
   		</div>
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
	   			<div class="sb"><input class="txt" type="submit" value="免费注册" src="{resource_url('img/pg10/reg_btn.jpg')}" /></div>
	   			</form>
	   		</div>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg10/pic3.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg10/pic4.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg10/pic5.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg10/pic6.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg10/pic7.png')}"/>
   		</div>
   		<div>
   			{include file="./site_f1.tpl"}
   			<div><img class="responed" src="{resource_url('img/pg10/pic8.jpg')}"/></div>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/pg10/pic9.png')}"/></div>
   		</div>
	</div><!-- //end of wrap -->
	{include file="./full_validation.tpl"}
</body>
</html>