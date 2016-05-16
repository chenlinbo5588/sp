{include file="./site_common.tpl"}
<style type="text/css">
img.responed {
	display: block;
}

.handler {
	position:relative;
	background:#ffc836;
	text-align:center;
	padding-bottom: 15px;
}

.fromdiv {
	padding:5px 0;
	border:2px solid #e58a02;
	border-radius:5px;
    -webkit-border-radius:5px;
    -moz-border-radius:5px;
    -o-border-radius:5px;
    width:90%;
    margin:0 auto;
}

.fromdiv h3 {
	color:#b50005;
	padding:5px 0;
	font-weight:bold;
	font-size:15px;
}

.fromdiv .t1 {
	height:35px;
	line-height:35px;
	width:40%;
	
}

.fromdiv .t2 {
	height:35px;
	line-height:35px;
	width:28%;
}

.fromdiv .t3 {
	width:80px;
	line-height:35px;
	height:35px;
	float:right;
	margin: 0 3px 0 0;
}

.fromdiv .t4 {
	background:#9a1515;
	width:98%;
	line-height:35px;
	height:35px;
	border-radius:5px;
    -webkit-border-radius:5px;
    -moz-border-radius:5px;
    -o-border-radius:5px;
    color: #fff;
    margin: 5px auto;
    border: 0px;
    -webkit-appearance: none;
}

.cv a {
	display: block;
    height: 210px;
    text-indent: -1000em;
    position: absolute;
    width: 100%;
}

</style>
	<div id="wrap">
		<div>
   			<img class="responed" src="{resource_url('img/pg3/pic1.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg3/pic2.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg3/pic3.jpg')}"/>
   		</div>
   		<div class="cv">
   			<a href="http://jq.qq.com/?_wv=1027&k=2FKSrRM" target="_blank">大宗商品交易Vip指导群</a>
   			<img class="responed" src="{resource_url('img/pg3/pic4.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg3/pic5.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg3/pic6.jpg')}"/>
   		</div>
		<div>
			<div class="hide">沥青投资4大优势</div>
   			<img class="responed" src="{resource_url('img/pg3/pic7.jpg')}"/>
   		</div>
   		<div>
   			<div class="hide">33倍杠杆，杠杆交易，10-100灵活可控，降低交易成本</div>
   			<img class="responed" src="{resource_url('img/pg3/pic8.jpg')}"/>
   		</div>
   		<div>
   			<div class="hide">每日可多次交易，提供资金利用率，较少风险</div>
   			<img class="responed" src="{resource_url('img/pg3/pic9.jpg')}"/>
   		</div>
   		<div>
   			<div class="hide">22小时不间断交易，方便客户抓住交易机会</div>
   			<img class="responed" src="{resource_url('img/pg3/pic10.jpg')}"/>
   		</div>
   		<div>
   			<div class="hide">双向交易，涨跌均可获利,大大增加获利几率</div>
   			<img class="responed" src="{resource_url('img/pg3/pic11.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg3/pic12.jpg')}"/>
   		</div>
   		<div class="handler">
   			<a name="md">&nbsp;</a>
   			{form_open(site_url('index/index'|cat:'#md'),'id="registerForm"')}
   			{include file="./site_form_hidden.tpl"}
   			<div class="fromdiv" >
   				<h3>模拟交易,不投入资金,没有风险</h3>
   				<input class="t1" type="text" name="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/>
   				<input class="t2" id="authcode_text" class="at_txt" type="text" name="auth_code" value="" placeholder="请输入4位验证码"/>
   				<img class="nature t3" id="authImg" src="{site_url('captcha')}?w=100&h=35" title="点击图片刷新"/>
   				<div class="tiparea">{form_error('mobile')}{form_error('auth_code')}{$feedback}</div>
   				<div class="refresh"><a href="javascript:void(0);">看不清，点击验证码刷新</a></div>
   				<div><input class="t4" type="submit" name="tj" value="免费申请模拟账户"/></div>
   			</div>
   			</form>
   		</div>
   		<div class="cv">
   			<a href="http://jq.qq.com/?_wv=1027&k=2FKSrRM" target="_blank">大宗商品交易Vip指导群</a>
   			<img class="responed" src="{resource_url('img/pg3/pic13.jpg')}"/>
   		</div>
   		<div>
   			{include file="./site_f1.tpl"}
   			<div><img class="responed" src="{resource_url('img/pg3/pic14.jpg')}"/></div>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/pg3/pic15.jpg')}"/></div>
   		</div>
	</div><!-- //end of wrap -->
	<script>
	var imgUrl = "{site_url('captcha')}?w=100&h=35";
	$(function(){
		{include file="./site_alert.tpl"}
		
		$("img.t3,.refresh").bind("click",function(){
			$("#authImg").attr("src",imgUrl + "&t=" + Math.random());
		});
		
		$('#registerForm').validate({
	        errorPlacement: function(error, element){
	        	//console.log(error);
	        	//console.log(element);
	            error.appendTo(element.parent().find(".tiparea"));
	        },
	        rules : {
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