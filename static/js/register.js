$(function(){
    var imgCode1 = $.fn.imageCode({ 
    	wrapId: "#authImg", 
    	captchaUrl : captchaUrl,
    	callbackFn:function(json){
    		$("#mobile_authcode").addClass("grayed").attr("disabled",true);
    	}
    });
    
	setTimeout(imgCode1.refreshImg,500);
	
	var codeValidating = false, usernameValidating = false;
	
    var codeValidation = function(inputElem,code,targetElem){
    	if(codeValidating){
    		return;
    	}
    	
    	codeValidating = true;
    	inputElem.addClass("showloading");
    	
    	var faildFn = function(){
    		inputElem.removeClass("showloading").addClass("error");
    		targetElem.addClass("grayed").attr("disabled",true);
    		codeValidating = false;
    	};
    	
    	$.ajax({
           type:'POST',
           url:captchaCheck,
           data: { captcha: code },
           dataTpe:'json',
           success:function(json){
        	   codeValidating = false;
        	   if(/成功/.test(json.message)){
        		   inputElem.removeClass('showloading error').addClass("valid");
            	   targetElem.removeClass("grayed").attr("disabled",false);
        	   }else{
        		   faildFn();
        	   }
           },
           error:function(){
        	   faildFn();
           }
        });
    }
    
    /**
     * 检查用户名称
     */
    $("input[name=username]").bind("focusout",function(){
    	var that = $(this);
    	var val = that.val();
    	
    	if(val.length >= 2 ){
    		if(usernameValidating){
        		return;
        	}
        	
    		usernameValidating = true;
        	that.addClass("showloading");
        	
        	var faildFn = function(str){
        		that.removeClass("showloading").addClass("error");
        		$("#tip_username").html('<label class="error">' + str + '</label>');
        		usernameValidating = false;
        	};
        	
        	$.ajax({
               type:'POST',
               url:usernameCheck,
               data: { username: val },
               dataTpe:'json',
               success:function(json){
            	   usernameValidating = false;
            	   if(/成功/.test(json.message)){
            		   $("#tip_username").html('');
            		   that.removeClass('showloading error').addClass("valid");
            	   }else{
            		   faildFn(json.message);
            	   }
               },
               error:function(){
            	   faildFn('服务器错误');
               }
            });
    		
    	}else{
    		if(val.length != 0){
    			$(this).addClass("error");
        		$(this).removeClass("showloading");
    		}
    	}
    });
    
    
    $("input[name=email]").bind('focusout',function(){
    	var val = $(this).val();
    	if(val.length > 0){
    		if(!regEmail.test(val)){
    			$(this).removeClass('valid').addClass('error');
    		}else{
    			$(this).removeClass('error').addClass('valid');
    		}
    	}else{
    		$(this).removeClass('valid');
    	}
    });
    
    $("input[name=qq]").bind('focusout',function(){
    	var val = $(this).val();
    	if(val.length > 0){
    		if(!/^\d{4,12}$/.test(val)){
    			$(this).removeClass('valid').addClass('error');
    		}else{
    			$(this).removeClass('error').addClass('valid');
    		}
    	}else{
    		$(this).removeClass('valid');
    	}
    });
    
    
    $("input[name=psw],input[name=psw_confirm]").bind('focusout',function(){
    	var val = $(this).val();
    	var name = $(this).attr('name');
    	
    	if(val.length > 0){
    		if(!regPsw.test(val)){
    			$(this).removeClass('valid').addClass('error');
    			$("#tip_" + name).html('<label class="error">密码中含有非法字符</label>');
    		}else{
    			if(name == 'psw_confirm'){
    				if($("input[name=psw_confirm]").val() != $("input[name=psw]").val()){
        				$(this).removeClass('valid').addClass('error');
        				$("#tip_" + name).html('<label class="error">两次密码不一致</label>');
        			}else{
        				$(this).removeClass('error').addClass('valid');
        			}
    			}else{
    				$(this).removeClass('error').addClass('valid');
    				
    			}
    		}
    	}else{
    		$(this).removeClass('valid');
    	}
    });
    
    
    $("input[name=mobile]").bind('focusout',function(){
    	var val = $(this).val();
    	if(val.length > 0){
    		if(!regMobile.test(val)){
    			$(this).removeClass('valid').addClass('error');
    		}else{
    			$(this).removeClass('error').addClass('valid');
    		}
    	}else{
    		$(this).removeClass('valid');
    	}
    });
    
    
    $("input[name=auth_code]").bind('keyup',function(){
    	var code = $(this).val();
    	if(code.length == 4){
    		codeValidation($(this),code,$("#mobile_authcode"));
    	}else{
    		$(this).removeClass('valid');
    		$(this).removeClass("showloading")
    		$("#mobile_authcode").addClass("grayed").attr("disabled",true);
    	}
    });
    
});