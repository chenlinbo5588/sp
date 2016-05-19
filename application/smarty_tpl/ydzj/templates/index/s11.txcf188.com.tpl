{include file="./site_common.tpl"}
<style type="text/css">

body {
	background:#444444;
}

#regbg1 {
	margin: -15px auto 0;
	background:url("{resource_url('img/pg11/formbg.jpg')}") repeat left top;
}

#regbg2 {
	padding: 0px 0 20px;
    background:#fff;
}

img.responed {
	display: block;
}

#reg1,#reg2 {
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

#reg1 form label.error {
	color:#fff;
}

.stock ,.mobile, .auth_code {
	margin:5px 0;
}


.stock .side_lb,.mobile label, .auth_code .side_lb {
	float:left;
	height:35px;
	line-height:35px;
	display:block;
	width:15%;
	color:#0D0D0D;
}

.stock .txt,.mobile .txt , .auth_code .txt {
	width:100%;
	height:35px;
	line-height:35px;
	1float:right;
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

.btn2 {
	position:relative;
}

.btn2 input {
	background:#ffde00;
	height:34px;
	line-height:34px;
	text-align:center;
	border:0;
	color:#fff;
	font-size:18px;
	font-weight:bold;
	width:100%;
}



</style>
	<div id="wrap">
		<div>
   			<img class="responed" src="{resource_url('img/pg11/pic1.png')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg11/pic2.jpg')}"/>
   		</div>
   		<div id="regbg1">
   			<div id="reg1">
   				<a name="md1">&nbsp;</a>
	   			{form_open(site_url('index/index'|cat:'#md1'),'id="registerForm1"')}
		        {include file="./site_form_hidden.tpl"}
	   			<div class="stock clearfix"><input type="text" class="txt noround" autocomplete="off" name="stock" value="{set_value('stock')}" placeholder="请输入股票名称或代码"/></div>
	   			<div class="tiparea"></div>
	   			<div class="mobile clearfix"><input type="text" class="txt noround" autocomplete="off" name="mobile" id="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/></div>
	   			<div class="tiparea"></div>
	   			<div class="btn2 clearfix"><input class="t4" type="submit" name="tj" value="立即诊断"/></div>
	   			</form>
	   		</div>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg11/pic3.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg11/pic4.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg11/pic5.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg11/pic6.jpg')}"/>
   		</div>
   		<div id="regbg2">
   			<div id="reg2">
   				<a name="md2">&nbsp;</a>
	   			{form_open(site_url('index/index'|cat:'#md2'),'id="registerForm2"')}
		        {include file="./site_form_hidden.tpl"}
	   			<div class="stock clearfix"><input type="text" class="txt noround" autocomplete="off" name="stock" value="{set_value('stock')}" placeholder="请输入股票名称或代码"/></div>
	   			<div class="tiparea"></div>
	   			<div class="mobile clearfix"><input type="text" class="txt noround" autocomplete="off" name="mobile" id="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/></div>
	   			<div class="tiparea"></div>
	   			<div class="btn2 clearfix"><input class="t4" type="submit" name="tj" value="立即诊断"/></div>
	   			</form>
	   		</div>
	   	</div>
	   	<div>
   			{include file="./site_f1.tpl"}
   			<div><img class="responed" src="{resource_url('img/pg11/pic7.jpg')}"/></div>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/pg11/pic8.jpg')}"/></div>
   		</div>
	</div><!-- //end of wrap -->
	<script>
	$(function(){
		{include file="./site_alert.tpl"}
		$('#registerForm1').validate({
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