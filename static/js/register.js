$(function(){
    var imgCode1 = $.fn.imageCode({ wrapId: "#authImg", captchaUrl : captchaUrl });
	setTimeout(imgCode1.refreshImg,500);
	
	var codeValidating = false;
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
    
    $("input[name=auth_code]").bind("focusout",function(){
    	var code = $(this).val();
    	
    	if(code.length == 4 ){
    		codeValidation($(this),code,$("#mobile_authcode"));
    	}else{
    		$(this).addClass("error");
    		$(this).removeClass("showloading")
    		
    		$("#mobile_authcode").addClass("grayed").attr("disabled",true);
    	}
    	
    });
    
});