{include file="./site_common.tpl"}
<style type="text/css">

body {
	background:#a60000;
	position:relative;
}

#wrap {
	position: relative;
}

#regbg {
	background:#fff;
	position:absolute;
	width:80%;
	top:0px;
	left:0px;
	z-index: 4;
}

img.responed {
	display: block;
}

#reg {
	margin: 0 auto;
	padding:10px;
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

.username .txt,.mobile .txt , .auth_code .txt {
	width:100%;
	height:35px;
	line-height:35px;
}

.auth_code {
	position:relative;
}

.auth_code .getCode {
	height:37px;
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

.btn2 input {
	background:#d31717;
	line-height:34px;
	text-align:center;
	border:0;
	color:#fff;
	font-size:15px;
	font-weight:bold;
	width:100%;
}

.cv {
	position:relative;
}

.cv a {
	position: absolute;
    width: 30%;
    height: 60px;
    top: 40px;
    right: 0px;
    text-indent: -1000em;
}

#maskbg {
	position:absolute;
	background:rgba(62, 55, 55, 0.52);
	top:0;
	left:0;
	z-index:3;
}

#reg h4 a {
	float:right;
	display: block;
    width: 16px;
    height: 16px;
    cursor: pointer
    
}



</style>
	<div id="wrap">
		
		<div class="cv">
			<a href="javascript:void(0);">立即免费订阅</a>
   			<img class="responed" src="{resource_url('img/new_pg6/pic1.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg6/pic2.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg6/pic3.jpg')}"/>
   		</div>
   		<div class="cv">
   			<a href="javascript:void(0);" style="top:0px;">免费订阅</a>
   			<img class="responed" src="{resource_url('img/new_pg6/pic4.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg6/pic5.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg6/pic6.jpg')}"/>
   		</div>
		<div>
   			<img class="responed" src="{resource_url('img/new_pg6/pic7.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg6/pic8.jpg')}"/>
   		</div>
   		<div>
   			{include file="./site_f1.tpl"}
   			<div><img class="responed" src="{resource_url('img/new_pg6/pic9.jpg')}"/></div>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/new_pg6/pic10.jpg')}"/></div>
   		</div>
	</div><!-- //end of wrap -->
	<div id="maskbg"></div>
	<div id="regbg" style="visibility:hidden;">
		<div id="reg">
			<h4><strong>订阅</strong><a name="md">X</a></h4>
   			{form_open(site_url('index/index'|cat:'#md'),'id="registerForm"')}
	        {include file="./site_form_hidden.tpl"}
   			<div class="mobile"><input type="text" class="txt noround" autocomplete="off" name="mobile" id="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/></div>
   			<div class="tiparea">{form_error('mobile')}</div>
   			<div class="auth_code"><input type="text" class="txt noround" name="auth_code" autocomplete="off" value="" placeholder="请输入您的验证码"/><input type="button" class="getCode noround" name="authCodeBtn" value="获取验证码"/></div>
   			<div class="tiparea">{form_error('auth_code')}</div>
   			<div class="btn2"><input class="t4" type="submit" name="tj" value="免费订阅"/></div>
   			</form>
   		</div>
	</div>
	<script type="text/javascript">
		var authCodeURL ="{site_url('api/register/authcode')}";
		{include file="./site_alert.tpl"}
		$(function(){
			$('#registerForm').validate({
		        errorPlacement: function(error, element){
		        	error.appendTo(element.parent().next(".tiparea"));
		        },
		        rules : {
		        	mobile: {
		                required : true,
		                phoneChina:true
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
		                required : '手机号码不能为空'
		            },
		            auth_code : {
		            	required : '请输入6位数字验证码',
		                digits: '请输入6位数字验证码',
		                minlength: '请输入6位数字验证码',
		                maxlength: '请输入6位数字验证码'
		            }
		        }
		    });
		    
		
			$("#reg h4 a").bind("click",function(){
				$("#maskbg").hide();
				$("#regbg").hide();
			});
			
			$(".cv").bind("click",function(){
				$(".tiparea").html('');
				
				$("#maskbg").css({ width:'100%',height:'100%'} ).show();
				
				var doc_height = $(document).height();
				var pop_height = $("#regbg").height();
				var left = 0;
                var top  = 0;
                var dialog_width    = $("#regbg").width();
                var dialog_height   = $("#regbg").height();
                
                left = $(window).scrollLeft() + ($(window).width() - dialog_width) / 2;
                top  = $(window).scrollTop()  + ($(window).height() - dialog_height) / 2;

                $("#regbg").css({ left:left + 'px', top:top + 'px',visibility: 'visible'}).show();
			});
		});
	</script>
	<script type="text/javascript" src="{resource_url('js/register.js')}?v=1.1"></script>
</body>
</html>