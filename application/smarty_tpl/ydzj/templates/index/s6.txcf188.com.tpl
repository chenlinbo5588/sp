{include file="./site_common.tpl"}
<style type="text/css">

body {
	background:#001d3a;
}


.formdiv {
	position:relative;
}

#wrap {

}

#regbg {
	padding: 20px 0;
    width: 100%;
}

img.responed {
	display: block;
}

#reg {
	margin: 0 auto;
	padding:0 10px;
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
	margin:5px 0 10px 0;
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
	width:84%;
	height:35px;
	line-height:35px;
	float:right;
}

.auth_code {
	position:relative;
}

.auth_code .txt {
	
}
.auth_code .getCode {
	width:100px;
	height:37px;
	position:absolute;
	margin-right:0;
	right:0;
	top:0;
	border:0;
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
	height:34px;
	line-height:34px;
	text-align:center;
	border:0;
	color:#fff;
	font-size:15px;
	font-weight:bold;
	width:100%;
}


.cv {
	margin-top:20px;
}

.fillcol {
	background:#c0eaff;
	height:160px;
}

</style>
	<div id="wrap">
		<div>
   			<img class="responed" src="{resource_url('img/pg7/pic1.png')}"/>
   			<img class="responed" src="{resource_url('img/pg7/pic2.png')}"/>
   			<img class="responed" src="{resource_url('img/pg7/pic3.png')}"/>
   		</div>
   		<div class="formdiv">
	   		<div id="regbg">
	   			<div class="hide"></div>
	   			<div id="reg">
	   				<a name="md">&nbsp;</a>
		   			{form_open(site_url('index/index'|cat:'#md'),'id="registerForm"')}
			        {include file="./site_form_hidden.tpl"}
		   			<div class="username clearfix"><label class="side_lb">姓名</label><input type="text" class="txt noround" autocomplete="off" name="username" value="{set_value('username')}" placeholder="请输入用户名称"/></div>
		   			<div class="tiparea">{form_error('username')}</div>
		   			<div class="mobile clearfix"><label class="side_lb">手机号码</label><input type="text" class="txt noround" autocomplete="off" name="mobile" id="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/></div>
		   			<div class="tiparea">{form_error('mobile')}</div>
		   			<div class="auth_code clearfix"><label class="side_lb">验证码</label><input type="text" class="txt noround" name="auth_code" autocomplete="off" value="" placeholder="请输入您的验证码"/><input type="button" class="getCode noround" name="authCodeBtn" value="获取验证码"/></div>
		   			<div class="tiparea">{form_error('auth_code')}</div>
		   			<div class="btn2 clearfix"><input class="t4" type="submit" name="tj" value="点击下载"/></div>
		   			</form>
		   		</div>
	   		</div>
	   	</div>
   		<div class="cv">
   			<img class="responed" src="{resource_url('img/pg7/pic4.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg7/pic5.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg7/pic6.png')}"/>
   		</div>
   		<div  class="cv">
   			<img class="responed" src="{resource_url('img/pg7/pic7.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg7/pic8.png')}"/>
   		</div>
   		<div class="cv">
   			{include file="./site_f1.tpl"}
   			<img class="responed" src="{resource_url('img/pg7/pic9.png')}"/>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/pg7/pic10.png')}"/></div>
   		</div>
	</div><!-- //end of wrap -->
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
	        	username : {
	        		required : true,
	        		minlength: 1,
	                maxlength: 20
	        	},
	        	mobile: {
	                required : true,
	                phoneChina:true,
	                /*
	                remote   : {
	                    url :'{site_url('common/member_check')}',
	                    type:'get',
	                    data:{
	                    	keyword: 'mobile',
	                    	value : function(){
	                            return $('#member_mobile').val();
	                        }
	                    }
	                }
	                */
	            },
	            auth_code : {
	            	required:true,
	                digits: true,
	                minlength: 6,
	                maxlength: 6
	            }
	        },
	        messages : {
	        	username : {
	        		required : '请输入用户名称',
	        		minlength: '最少输入1个字符',
	                maxlength: '最多输入20个字符'
	        	},
	        	mobile: {
	                required : '手机号码不能为空',
	                /*remote   : '手机号码已经被注册，请您更换一个'*/
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