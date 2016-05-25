$('#registerForm').validate({
		        errorPlacement: function(error, element){
		        	error.appendTo(element.parent().next(".tiparea"));
		        },
		        onfocusout:false,
		        onkeyup:false,
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