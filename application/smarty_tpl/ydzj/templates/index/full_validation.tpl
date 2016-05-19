	<script type="text/javascript">
	var authCodeURL ="{site_url('api/register/authcode')}";
	{include file="./site_alert.tpl"}
	$(function(){
		$('#registerForm').validate({
	        errorPlacement: function(error, element){
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
	        	username : {
	        		required : '请输入用户名称',
	        		minlength: '最少输入1个字符',
	                maxlength: '最多输入20个字符'
	        	},
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
	});
	</script>
	<script type="text/javascript" src="{resource_url('js/register.js')}?v=1.1"></script>