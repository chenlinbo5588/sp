/**
 * 注册
 */
$(function(){
	$("input[name=authCodeBtn]").bind("click",function(e){
		
		var mobile = $("input[name=mobile]").val();
		var username = $("input[name=username]").val();
		var timer = null;
		var that = $(this);
		
		if(!regMobile.test(mobile)){
			alert("请输入正确的手机号码");
			return ;
		}
		
		that.addClass("grayed").prop("disabled",true).val("剩余60秒");
		var sec = 59;
		
		if(timer == null){
			timer = setInterval(function(){
				if(sec < 1){
					sec = 59;
					that.removeClass('grayed').prop("disabled",false).val('重新获取验证码');
					
					if(timer){
						clearInterval(timer);
					}
				}else{
					that.val("剩余" + sec + "秒");
					sec--;
				}
				
			},1000);
		}
		
		
		$.ajax({
			type:"POST",
			url:authCodeURL,
			dataType:"json",
			data:{phoneNo: mobile , username: username, formhash : $("input[name=formhash]").val()},
			success:function(resp){
				$("input[name=formhash]").val(resp.data.formhash);
			},
			failed:function(resp){
				$("input[name=formhash]").val(resp.data.formhash);
			}
		})
		
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
                /*
                remote   : {
                    url :'{site_url('common/member_check')}',
                    type:'get',
                    data:{
                    	keyword: 'mobile',
                    	value : function(){
                            return $('#member_mobile').val();
                        }
                    }
                }
                */
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
                required : '手机号码不能为空',
                /*remote   : '手机号码已经被注册，请您更换一个'*/
            },
            auth_code : {
            	required : '请输入6位数字验证码',
                digits: '请输入6位数字验证码',
                minlength: '请输入6位数字验证码',
                maxlength: '请输入6位数字验证码'
            }
        }
    });
	
	
	$("#registerForm").bind("submit",function(e){
		var mobile = $("input[name=mobile]").val();

		
		
		if($("input[name=username]").val() == '' ){
			alert("请输入用户名称");
			$("input[name=username]").focus();
			return false;
		}
		
		
		if(!regMobile.test(mobile)){
			alert("请输入正确的手机号码");
			$("input[name=mobile]").focus();
			return false;
		}
		
		
		if($("input[name=auth_code]").val() == '' ){
			alert("请输入手机验证码");
			$("input[name=auth_code]").focus();
			return false;
		}
		
		return true;
		
	});
	
	
	
	
});