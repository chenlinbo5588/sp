$(function(){
	$("input.mcode").bind("click",function(e){
		var that = $(this);
		var srcInputID = that.attr('data-mcode');
		
		var mobile = $(srcInputID).val();
		var timer = null;
		
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
					that.removeClass('grayed').prop("disabled",false).val('重新发送验证码');
					
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
			data:{phoneNo: mobile },
			success:function(resp){
				refreshFormHash(resp);
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
			}
		})
	});
});