{include file="./site_common.tpl"}
<style type="text/css">

img.responed {
	display: block;
}

.handler {
	position:relative;
}

.fromdiv {
	background:#dadada;
	padding:5px 20px;
	text-align:center;
}

.fromdiv input {
}


.t2 {
	background:#be0701;
	color:#cb3232;
	font-size:14px;
	font-weight:bold;
	height:37px;
	line-height:37px;
	text-align:center;
	text-indent:0;
	border:0px;
	border-radius:5px;
    -webkit-border-radius:5px;
    -moz-border-radius:5px;
    -o-border-radius:5px;
    -webkit-appearance: none;
	width:22%;
	color:#feffff;
	position: relative;
    top: 2px;
}

.fromdiv input.t1 {
	height:37px;
	line-height:37px;
	border:1px solid #c1b3b3;
	width: 73%;
	
}

.formdiv2 .t2 {
	color:#fa8f03;
}

</style>
	<div id="wrap">
		<div>
			<div class="hide">10万模拟资金 0基础3步学会贵金属投资</div>
   			<img class="responed" src="{resource_url('img/pg2/pic1.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg2/pic2.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg2/pic3.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg2/pic4.jpg')}"/>
   		</div>
   		<div>
   			<div class="hide">为什么投资贵金属</div>
   			<div class="hide">简单:0基础3步学会炒白银，不上班也能有钱花，快乐做自己</div>
   			<div class="hide">快速:连续操盘一个月，快速赚取人生第一桶金，成功与财富块人一步</div>
   			<div class="hide">自由:白天上班，晚上炒银自由赚外快，工作赚取两不误</div>
   			<div class="hide">赚取:抓住一次非农行情，严格操作，行情波动可达100点</div>
   			<img class="responed" src="{resource_url('img/pg2/pic5.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg2/pic6.jpg')}"/>
   		</div>
		<div>
   			<img class="responed" src="{resource_url('img/pg2/pic7.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg2/pic8.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg2/pic9.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg2/pic9.jpg')}"/>
   		</div>
   		<div class="handler">
   			<img class="responed" src="{resource_url('img/pg2/pic10.jpg')}"/>
   		</div>
   		<div class="fromdiv" >
   			<a name="md1">&nbsp;</a>
   			<div class="hide">免费申请模拟账户，领取50万操盘资金</div>
			{form_open(site_url('index/site1'|cat:'#md1'),'id="registerForm1"')}
			<input class="t1" type="text" name="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/>
			<input class="t2" type="submit" name="tj" value="模拟开户"/>
			<div class="tiparea">{form_error('mobile')}</div>
			</form>
			
		</div>
   		<div class="handler">
   			<img class="responed" src="{resource_url('img/pg2/pic11.jpg')}"/>
   		</div>
   		<div class="fromdiv formdiv2" >
   			<a name="md2">&nbsp;</a>
   			<div class="hide">您已等不急，跃跃欲试，马上开了实盘账户</div>
			{form_open(site_url('index/site1'|cat:'#md2'),'id="registerForm2"')}
			<input class="t1" type="text" name="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/>
			<input class="t2" type="submit" name="tj" value="实盘开户"/>
			<div class="tiparea">{form_error('mobile')}</div>
			</form>
		</div>
   		<div>
   			{include file="./site_f1.tpl"}
   			<div><img class="responed" src="{resource_url('img/pg2/pic12.jpg')}"/></div>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/pg2/pic13.jpg')}"/></div>
   		</div>
	</div><!-- //end of wrap -->
	<script type="text/javascript" src="{resource_url('js/jquery.validation.min.js')}"></script>
	<script>
	$(function(){
		$('#registerForm1').validate({
	        errorPlacement: function(error, element){
	        	//console.log(error);
	        	//console.log(element);
	            error.appendTo(element.parent().find(".tiparea"));
	        },
	        rules : {
	        	mobile: {
	                required : true,
	                phoneChina:true,
	            }
	            
	        },
	        messages : {
	        	mobile: {
	                required : '手机号码不能为空',
	           }
	        }
	    });
		
		$('#registerForm2').validate({
	        errorPlacement: function(error, element){
	        	//console.log(error);
	        	//console.log(element);
	            error.appendTo(element.parent().find(".tiparea"));
	        },
	        rules : {
	        	mobile: {
	                required : true,
	                phoneChina:true,
	            }
	            
	        },
	        messages : {
	        	mobile: {
	                required : '手机号码不能为空',
	           }
	        }
	    });
	
	});	
	</script>
</body>
</html>