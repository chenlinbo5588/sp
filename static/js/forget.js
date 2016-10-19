$(function(){
    var emailSending = false;
    var currentWay = $("input[name=find_way]:checked").val();
    
    if(currentWay == 'way_mobile'){
    	$(".way_email").hide();
    }else{
    	$(".way_mobile").hide();
    }
    
    
    
    
    $("input[name=find_way]").bind('click',function(){
          var that = $(this);
          var way = that.val();
          
          if(way == 'way_email'){
              $(".way_email").show();
              $(".way_mobile").hide();
          }else{
              $(".way_email").hide();
              $(".way_mobile").show();
          }
    });
    
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
    
    $(".step2 input[name=auth_code]").bind('keyup',function(){
    	var code = $(this).val();
    	if(code.length == 4){
    		codeValidation($(this),code,$("#mobile_authcode"));
    	}else{
    		$(this).removeClass('valid');
    		$(this).removeClass("showloading")
    		$("#mobile_authcode").addClass("grayed").attr("disabled",true);
    	}
    });
    
    $("input[name=emailCodeBtn]").bind("click",function(){
       
       if(emailSending){
           return ;
       }
       
       emailSending = true;
       
       var title = $(this).val();
       if(!/重发/.test(title)){
    	   $(this).val($(this).attr('data-retext'));
       }
       
       $("#email_code").html("我们已经发送了一封邮件到您的邮箱中，请您及时查收!");
       
        $.ajax({
           type:'POST',
           url:emailUrl,
           data: { email: $("input[name=email]").val() },
           dataTpe:'json',
           success:function(json){
               emailSending = false;
               
           },
           error:function(){
               emailSending = false;
           }
        });
    });
    
});