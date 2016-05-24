{include file="./site_common.tpl"}
<style type="text/css">

body {
	background:#ededed;
}

.formdiv {
	position:relative;
}

#wrap {

}

#regbg {
    width: 100%;
    padding-bottom: 20px;
}

img.responed {
	display: block;
}

#reg {
	margin: 0 auto;
	padding:0 10px;
}


.formdiv {
	background:#f3f3f3;
}


form label.error {
	padding-left:0px;
    margin-left:0px;
	background:none;
	display: block;
    width: 100%;
    text-align: center;
}

.username ,.mobile, .auth_code {
	margin:6px 0;
}


.username .side_lb,.mobile label, .auth_code .side_lb {
	float:left;
	height:35px;
	line-height:35px;
	display:block;
	width:15%;
	color:#FFFFFF;
}

.username .txt,.mobile .txt , .auth_code .txt {
	width:100%;
	height:37px;
	line-height:37px;
}

.auth_code {
	position:relative;
}

.auth_code .txt {
	width:60%;
}
.auth_code .getCode {
	height:37px;
	line-height:37px;
	position:absolute;
	margin-right:0;
	right:-1px;
	top:0;
	border:2px solid #fc9401;
	font-size:15px;
	color:#fd9301;
	background:#fff;
	width:38%;
}


.auth_code .grayed {
	background:#d0d0d0;
}


.refresh {
	padding:2px 0;
}

.refresh a {
	display:block;
	width:100%;
	text-align:center;
	color:#61615D;
}

.btn2 {
	position:relative;
}

.btn2 input {
	background:#d31717;
	height:37px;
	line-height:37px;
	text-align:center;
	border:0;
	color:#fff;
	font-size:15px;
	font-weight:bold;
	width:100%;
}

.cv {
	margin-top:15px;
}


</style>
	<div id="wrap">
		<div>
   			<img class="responed" src="{resource_url('img/pg8/pic1.png')}"/>
   		</div>
   		<div class="cv">
   			<img class="responed" src="{resource_url('img/pg8/pic2.png')}"/>
   		</div>
   		<div class="cv">
   			<img class="responed" src="{resource_url('img/pg8/pic3.png')}"/>
   		</div>
   		<div class="cv">
   			<img class="responed" src="{resource_url('img/pg8/pic4.png')}"/>
   		</div>
   		<div class="cv">
   			<img class="responed" src="{resource_url('img/pg8/pic5.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg8/pic6.png')}"/>
   		</div>
   		<div class="formdiv">
	   		<div id="regbg">
	   			<div class="hide"></div>
	   			<div id="reg">
	   				<a name="md">&nbsp;</a>
		   			{form_open(site_url('index/index'|cat:'#md'),'id="registerForm"')}
			        {include file="./site_form_hidden.tpl"}
		   			<div class="mobile"><input type="text" class="txt noround" autocomplete="off" name="mobile" id="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/></div>
		   			<div class="tiparea">{form_error('mobile')}</div>
		   			<div class="auth_code"><input type="text" class="txt noround" name="auth_code" autocomplete="off" value="" placeholder="请输入您的验证码"/><input type="button" class="getCode noround" name="authCodeBtn" value="获取验证码"/></div>
		   			<div class="tiparea">{form_error('auth_code')}</div>
		   			<div class="btn2 clearfix"><input class="t4" type="submit" name="tj" value="申请资金"/></div>
		   			</form>
		   		</div>
	   		</div>
	   	</div>
	</div><!-- //end of wrap -->
	<div id="wrap2">
		<div class="cv">
   			{include file="./site_f1.tpl"}
   			<div><img class="responed" src="{resource_url('img/pg8/pic7.png')}"/></div>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/pg8/pic8.png')}"/></div>
   		</div>
	</div>
	<script type="text/javascript">
	var authCodeURL ="{site_url('api/register/authcode')}";
	{include file="./site_alert.tpl"}
	$(function(){
		$('#registerForm').validate({
	        errorPlacement: function(error, element){
	        	//console.log(error);
	        	//console.log(element);
	        	error.appendTo(element.parent().next(".tiparea"));
	        },
	        rules : {
	        	mobile: {
	                required : true,
	                phoneChina:true,
	            },
	            auth_code : {
	            	required:true,
	                digits: true,
	                minlength: 6,
	                maxlength: 6
	            }
	        },
	        messages : {
	        	mobile: {
	                required : '手机号码不能为空',
	            },
	            auth_code : {
	            	required : '请输入6位数字验证码',
	                digits: '请输入6位数字验证码',
	                minlength: '请输入6位数字验证码',
	                maxlength: '请输入6位数字验证码'
	            }
	        }
	    });
	});
	</script>
	<script type="text/javascript" src="{resource_url('js/register.js')}?v=1.1"></script>
</body>
</html>