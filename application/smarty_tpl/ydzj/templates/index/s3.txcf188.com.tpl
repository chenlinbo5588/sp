{include file="./site_common.tpl"}
<style type="text/css">

#regbg {
	background:#084355;
	padding: 20px 0;
}

img.responed {
	display: block;
}

#reg {
	margin: 0 auto;
	width:95%;
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
	margin:2px 0;
}


.username .side_lb,.mobile label, .auth_code .side_lb {
	float:left;
	height:35px;
	line-height:35px;
	display:block;
	width:15%;
	color:#fff;
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

.btn2 input {
	background:url('{resource_url("img/btns/btn2_2.png")}') repeat-x left top;
	height:34px;
	line-height:34px;
	text-align:center;
	border:0;
	color:#fe0050;
	font-size:15px;
	font-weight:bold;
	width:100%;
}

.btn2 .lf , .btn2 .rf {
	position:absolute;
	width:3px;
	height:34px;
	background:url('{resource_url("img/btns/btn2_1.png")}') no-repeat left top;
}

.btn2 .lf {
	left:0;
	top:0p;x
}

.btn2 .rf {
	right:0px;
	top:0px;
	background:url('{resource_url("img/btns/btn2_3.png")}') no-repeat left top;
}

</style>
	<div id="wrap">
		<div>
   			<img class="responed" src="{resource_url('img/pg4/pic1.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg4/pic2.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg4/pic3.png')}"/>
   		</div>
   		<div class="cv">
   			<a href="http://jq.qq.com/?_wv=1027&k=2FKSrRM" target="_blank">大宗商品交易Vip指导群</a>
   			<img class="responed" src="{resource_url('img/pg4/pic4.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg4/pic5.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg4/pic6.png')}"/>
   		</div>
		<div>
			<div class="hide">沥青投资4大优势</div>
   			<img class="responed" src="{resource_url('img/pg4/pic7.png')}"/>
   		</div>
   		<div>
   			<div class="hide">33倍杠杆，杠杆交易，10-100灵活可控，降低交易成本</div>
   			<img class="responed" src="{resource_url('img/pg4/pic8.png')}"/>
   		</div>
   		<div>
   			<div class="hide">每日可多次交易，提供资金利用率，较少风险</div>
   			<img class="responed" src="{resource_url('img/pg4/pic9.png')}"/>
   		</div>
   		<div>
   			<div class="hide">22小时不间断交易，方便客户抓住交易机会</div>
   			<img class="responed" src="{resource_url('img/pg4/pic10.png')}"/>
   		</div>
   		<div>
   			<div class="hide">双向交易，涨跌均可获利,大大增加获利几率</div>
   			<img class="responed" src="{resource_url('img/pg4/pic11.png')}"/>
   		</div>
   		<div id="regbg">
   			<div id="reg">
   				<a name="md">&nbsp;</a>
	   			{form_open(site_url('index/site3'|cat:'#md'),'id="registerForm"')}
		        {include file="./site_form_hidden.tpl"}
	   			<div class="username clearfix"><label class="side_lb">姓名</label><input type="text" class="txt noround" autocomplete="off" name="username" value="{set_value('username')}" placeholder="请输入用户名称"/></div>
	   			<div class="tiparea"></div>
	   			<div class="mobile clearfix"><label class="side_lb">手机号码</label><input type="text" class="txt noround" autocomplete="off" name="mobile" id="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/></div>
	   			<div class="tiparea"></div>
	   			<div class="auth_code clearfix"><label class="side_lb">验证码</label><input type="text" class="txt noround" name="auth_code" autocomplete="off" value="" placeholder="请输入您的验证码"/><img class="nature t4" id="authImg" src="{site_url('captcha')}?w=100&h=37" title="点击图片刷新"/></div>
	   			<div class="tiparea"></div>
	   			<div class="refresh"><a href="javascript:void(0);">看不清，点击验证码刷新</a></div>
	   			<div class="btn2 clearfix"><div class="lf"></div><input class="t4" type="submit" name="tj" value="我要体验"/><div class="rf"></div></div>
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
	var imgUrl = "{site_url('captcha')}?w=100&h=37";
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