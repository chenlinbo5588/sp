{include file="./site_common.tpl"}
<style type="text/css">

body {
	background:#5e605f;
}


#regbg {
	background:#084355;
	padding: 10px 0 20px;
}

img.responed {
	display: block;
}

#reg {
	margin: 0 auto;
	padding:0 10px;
	width: 90%;
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
	margin:5px 0;
}


.username .side_lb,.mobile label, .auth_code .side_lb {
	float:left;
	height:35px;
	line-height:35px;
	display:block;
	width:19%;
	color:#fff;
}

.username .txt,.mobile .txt , .auth_code .txt {
	width:80%;
	height:35px;
	line-height:35px;
	float:right;
}

.auth_code {
	position:relative;
}

.auth_code .txt {
	
}
.auth_code img {
	position:absolute;
	margin-right:0;
	right:0;
	top:0;
}


.refresh {
	padding:2px 0;
}

.refresh a {
	display:block;
	width:100%;
	text-align:center;
	color:#FFFA14;
}

.btn2 {
	position:relative;
}

.btn2 .t4 {
	background:url('{resource_url("img/new_pg4/reg_btn.jpg")}') no-repeat center center;
	height:39px;
	line-height:39px;
	text-align:center;
	border:0;
	color:#fe0050;
	font-size:15px;
	font-weight:bold;
	width:100%;
	text-indent:-1000px;
}

</style>
	<div id="wrap">
		<div>
			<div class="hide">0基础K线从入门到精通 抄底逃顶</div>
   			<img class="responed" src="{resource_url('img/pg4/pic1.png')}"/>
   		</div>
   		<div>
   			<div class="hide">技术指标完美猎杀 菜鸟也能变高手</div>
   			<img class="responed" src="{resource_url('img/pg4/pic2.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg4/pic3.png')}"/>
   		</div>
   		<div class="cv">
   			<div class="hide">K线组合发信号 高抛低吸制胜千里</div>
   			<img class="responed" src="{resource_url('img/pg4/pic4.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg4/pic5.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg4/pic6.png')}"/>
   		</div>
		<div>
   			<img class="responed" src="{resource_url('img/pg4/pic7.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg4/pic8.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg4/pic9.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg4/pic10.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg4/pic11.png')}"/>
   		</div>
   		<div id="regbg">
   			<div class="hide">免费体验，入市首选指标 《K线入门宝典》</div>
   			<div id="reg">
   				<a name="md">&nbsp;</a>
	   			{form_open(site_url('index/index'|cat:'#md'),'id="registerForm"')}
		        {include file="./site_form_hidden.tpl"}
	   			<div class="username clearfix"><label class="side_lb">姓名</label><input type="text" class="txt noround" autocomplete="off" name="username" value="{set_value('username')}" placeholder="请输入用户名称"/></div>
	   			<div class="tiparea">{form_error('username')}</div>
	   			<div class="mobile clearfix"><label class="side_lb">手机号码</label><input type="text" class="txt noround" autocomplete="off" name="mobile" id="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/></div>
	   			<div class="tiparea">{form_error('mobile')}</div>
	   			<div class="auth_code clearfix"><label class="side_lb">验证码</label><input type="text" class="txt noround" id="authcode_text" name="auth_code" autocomplete="off" value="" placeholder="请输入您的验证码"/><img class="nature t4" id="authImg" src="{site_url('captcha')}?w=100&h=37&type=num" title="点击图片刷新"/></div>
	   			<div class="tiparea">{form_error('auth_code')}</div>
	   			<div class="refresh"><a href="javascript:void(0);">看不清，点击验证码刷新</a></div>
	   			<div class="btn2 clearfix"><input class="t4" type="submit" name="tj" value="我要体验"/></div>
	   			</form>
	   		</div>
   		</div>
   		
   		<div>
   			{include file="./site_f1.tpl"}
   			<div><img class="responed" src="{resource_url('img/pg4/pic13.png')}"/></div>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/pg4/pic14.jpg')}"/></div>
   		</div>
	</div><!-- //end of wrap -->
	<script>
	var imgUrl = "{site_url('captcha')}?w=100&h=37&type=num";
	$(function(){
		{include file="./site_alert.tpl"}
		
		$("#authImg,.refresh").bind("click",function(){
			$("#authImg").attr("src",imgUrl + "&t=" + Math.random());
		});
		
		$('#registerForm').validate({
	        errorPlacement: function(error, element){
	        	//console.log(error);
	        	//console.log(element);
	            error.appendTo(element.parent().next(".tiparea"));
	        },
	        onfocusout:false,
		    onkeyup:false,
	        rules : {
	        	username : {
	        		required : true,
	        		minlength: 1,
	                maxlength: 20
	        	},
	        	mobile: {
	                required : true,
	                phoneChina:true,
	            },
	            auth_code : {
	            	required:true,
	                minlength: 4,
	                maxlength: 4
	            }
	            
	        },
	        messages : {
		        username : {
	        		required : '请输入姓名',
	        		minlength: '最少输入1个字符',
	                maxlength: '最多输入20个字符'
	        	},
	        	mobile: {
	                required : '手机号码不能为空',
	           },
	           auth_code : {
	            	required : '请输入4位验证码',
	                minlength: '请输入4位验证码',
	                maxlength: '请输入4位验证码'
	            }
	        }
	    });
	});	
	</script>
</body>
</html>