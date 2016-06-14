{include file="./site_common.tpl"}
<style type="text/css">

body {
	background:#311804;
}

#regbg {
	padding: 20px 0;
    width: 100%;
    background:#c0eaff;
}

img.responed {
	display: block;
}

#reg {
	margin: 0 auto;
	padding:0 10px;
	width:90%;
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
	color:#0D0D0D;
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

#reg .getCode {
	height: 37px;
	line-height:37px;
	text-align:center;
	background:#d3d5da;
	border:0px;
	position: absolute;
    right: 0;
    font-size: 14px;
    width:28%;
    border-radius:0px;
    -webkit-border-radius:0px;
    -moz-border-radius:0px;
    -o-border-radius:0px;
    -webkit-appearance:none;
}

.btn2 input {
	height:43px;
	text-align:center;
	border:0;
	color:#fff;
	font-size:15px;
	font-weight:bold;
	width:80%;
	margin:0 auto;
	display:block;
	text-indent:-1000em;
	background:url('{resource_url("img/btns/reg_btn.png")}') no-repeat center center;
}

</style>
	<div id="wrap">
		<div>
			<div class="hide">现货白银投资 赢金财经金属投资服务品牌</div>
   			<img class="responed" src="{resource_url('img/new_pg12/pic1.jpg')}"/>
   		</div>
   		<div>
   			<div class="hide">5大投资优势 低成本 高收益 多机会 易操作 市场公平</div>
   			<img class="responed" src="{resource_url('img/new_pg12/pic2.jpg')}"/>
   		</div>
   		<div>
   			<div class="hide">平台五大优势 数据同步共享 开户流程优化 交易过程放心 5分钟即可完成 账号系统生成</div>
   			<img class="responed" src="{resource_url('img/new_pg12/pic3.jpg')}"/>
   		</div>
   		<div class="cv">
   			<img class="responed" src="{resource_url('img/new_pg12/pic4.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg12/pic5.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg12/pic6.png')}"/>
   		</div>
   		<div id="regbg">
   			<div class="hide">免费体验，入市首选指标 《K线入门宝典》</div>
   			<div id="reg">
   				<a class="anchor" name="md">&nbsp;</a>
	   			{form_open(site_url('index/index/'|cat:$siteIndex|cat:'#md'),'id="registerForm"')}
		        {include file="./site_form_hidden.tpl"}
	   			<div class="mobile clearfix"><label class="side_lb">手机号码</label><input type="text" class="txt noround" autocomplete="off" name="mobile" id="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/></div>
	   			<div class="tiparea"></div>
	   			<div class="auth_code clearfix"><label class="side_lb">验证码</label><input type="text" class="txt noround" name="auth_code" autocomplete="off" value="" placeholder="请输入您的验证码"/><input type="button" class="getCode noround" name="authCodeBtn" value="获取验证码"/></div>
	   			<div class="tiparea"></div>
	   			<div class="btn2"><input class="t4" type="submit" name="tj" value="免费申请账号"/></div>
	   			</form>
	   		</div>
	   	</div>
	   	<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/pg12/pic7.png')}"/></div>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/pg12/pic8.png')}"/></div>
   		</div>
	</div><!-- //end of wrap -->
	<script type="text/javascript">
	var authCodeURL ="{site_url('api/register/authcode')}";
	{include file="./site_alert.tpl"}
	$(function(){
		{include file="./site_alert.tpl"}
		$('#registerForm').validate({
	        errorPlacement: function(error, element){
	            error.appendTo(element.parent().next(".tiparea"));
	        },
	        onfocusout:false,
		    onkeyup:false,
	        rules : {
	        	mobile: {
	                required : true,
	                phoneChina:true,
	            },
	            auth_code : {
	            	required:true,
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
	                minlength: '请输入6位数字验证码',
	                maxlength: '请输入6位数字验证码'
	            }
	        }
	    });
	});	
	</script>
	{include file="./js_mobile_authcode.tpl"}
</body>
</html>