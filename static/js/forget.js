$(function(){
    var emailSending = false;
    $(".way_mobile").hide();
    
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
    
    $("input[name=emailCodeBtn]").bind("click",function(){
       
       if(emailSending){
           return ;
       }
       
       emailSending = true;
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