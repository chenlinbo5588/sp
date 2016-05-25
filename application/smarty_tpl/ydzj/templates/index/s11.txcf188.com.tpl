{include file="./site_common.tpl"}
<style type="text/css">

body {
	background:#545454;
}


{include file="./s1_formcss.tpl"}

#regbg2 {
	padding: 0px 0 20px;
    background:#0d81a8;
}

#reg2 {
	margin: 0 auto;
	padding:0 10px;
	width:80%;
}

#reg2 .txt {
    text-indent: 40px;
}

.stock ,.mobile, .auth_code {
	margin:5px 0;
}

.stock .txt,.mobile .txt {
	width:100%;
	height:37px;
	line-height:37px;
	1float:right;
}

#reg2 .t4 {
	background:url("{resource_url('img/new_pg11/btn_bg.jpg')}") repeat-x left center;
	height: 39px;
	text-align:center;
	border:0px;
	position: absolute;
	color:#f75526;
	font-weight:bold;
	font-size: 16px;
    right: -1px;
    width:28%;
    border-radius:0px;
    -webkit-border-radius:0px;
    -moz-border-radius:0px;
    -o-border-radius:0px;
    -webkit-appearance:none;
}



</style>
	<div id="wrap">
		<div>
   			<img class="responed" src="{resource_url('img/new_pg11/pic1.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg11/pic2.jpg')}"/>
   		</div>
   		<div id="regbg">
   			<div id="reg">
	   			{form_open(site_url('index/index'),'id="registerForm"')}
	   			<input type="hidden" name="muti_rule" value="yes"/>
	   			<input type="hidden" name="rule" value="rule1"/>
		        {include file="./site_form_hidden.tpl"}
	   			<div class="username"><input type="text" class="txt noround" autocomplete="off" name="username" value="{set_value('username')}" placeholder="请输入用户名称"/></div>
	   			<div class="tiparea">{form_error('username')}</div>
	   			<div class="mobile"><input type="text" class="txt noround" autocomplete="off" name="mobile" id="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/></div>
	   			<div class="tiparea">{form_error('mobile')}</div>
	   			<div class="auth_code"><input type="text" class="txt noround" name="auth_code" autocomplete="off" value="" style="width:70%" placeholder="请输入您的验证码"/><input type="button" class="getCode noround" name="authCodeBtn" value="获取验证码"/></div>
	   			<div class="tiparea">{form_error('auth_code')}</div>
	   			<div class="sb"><input class="txt" type="submit" value="免费注册" /></div>
	   			</form>
	   		</div>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg11/pic3.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg11/pic4.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg11/pic5.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg11/pic6.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg11/pic7.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg11/pic8.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg11/pic9.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg11/pic10.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg11/pic11.jpg')}"/>
   		</div>
   		<div id="regbg2">
   			<div id="reg2">
   				<a class="anchor" name="md2">&nbsp;</a>
	   			{form_open(site_url('index/index'|cat:'#md2'),'id="registerForm2"')}
	   			<input type="hidden" name="muti_rule" value="yes"/>
	   			<input type="hidden" name="rule" value="rule2"/>
		        {include file="./site_form_hidden.tpl"}
	   			<div class="stock"><input type="text" class="txt noround" autocomplete="off" name="stock" value="{set_value('stock')}" placeholder="请输入股票名称或代码"/></div>
	   			<div class="tiparea">{form_error('stock')}</div>
	   			<div class="mobile" style="position:relative;"><input type="text" class="txt noround" autocomplete="off" style="width:70%" name="mobile" id="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/><input class="t4" type="submit" name="tj" value="立即诊断"/></div>
	   			<div class="tiparea">{form_error('mobile')}</div>
	   			</form>
	   		</div>
	   	</div>
	   	<div>
   			{include file="./site_f1.tpl"}
   			<div><img class="responed" src="{resource_url('img/new_pg11/pic12.jpg')}"/></div>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/new_pg11/pic13.jpg')}"/></div>
   		</div>
	</div><!-- //end of wrap -->
	{include file="./full_validation.tpl"}
	<script type="text/javascript">
	$(function(){
	    $('#registerForm2').validate({
	        errorPlacement: function(error, element){
	        	//console.log(error);
	        	//console.log(element);
	            error.appendTo(element.parent().next(".tiparea"));
	        },
	        rules : {
	        	stock : {
	        		required : true,
	        		minlength: 1,
	                maxlength: 50
	        	},
	        	mobile: {
	                required : true,
	                phoneChina:true,
	            }
	        },
	        messages : {
		        stock : {
	        		required : '请输入股票名称或代码',
	        		minlength: '最少输入1个字符',
	                maxlength: '最多输入50个字符'
	        	},
	        	mobile: {
	                required : '手机号码不能为空',
	            }
	        }
	    });
	});	
	</script>
</body>
</html>