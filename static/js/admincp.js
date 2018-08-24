$(function(){
	//自定义radio样式
	$(".cb-enable").click(function(){
		var parent = $(this).parents('.onoff');
		$('.cb-disable',parent).removeClass('selected');
		$(this).addClass('selected');
		$('.checkbox',parent).prop('checked', true);
	});
	
	$(".cb-disable").click(function(){
		var parent = $(this).parents('.onoff');
		$('.cb-enable',parent).removeClass('selected');
		$(this).addClass('selected');
		$('.checkbox',parent).prop('checked', false);
	});
	
	
	// 显示隐藏预览图 start
	$('.show_image').hover(
		function(){
			$(this).next().css('display','block');
		},
		function(){
			$(this).next().css('display','none');
		}
	);
	

	// 可编辑列（input）变色
	$('.editable').hover(
		function(){
			$(this).removeClass('editable').addClass('editable2');
		},
		function(){
			$(this).removeClass('editable2').addClass('editable');
		}
	);
	
	// 提示操作 展开与隐藏
	$("#prompt tr:odd").addClass("odd");
	$("#prompt tr:not(.odd)").hide();
	$("#prompt tr:first-child").show();
		
	$("#prompt tr.odd").click(function(){
		$(this).next("tr").toggle();
		$(this).find(".title").toggleClass("ac");
		$(this).find(".arrow").toggleClass("up");
		
	});

	// 可编辑列（area）变色
	$('.editable-tarea').hover(
		function(){
			$(this).removeClass('editable-tarea').addClass('editable-tarea2');
		},
		function(){
			$(this).removeClass('editable-tarea2').addClass('editable-tarea');
		}
	);
	
	/*
    districtSelect('bind');
    
    $.validator.addMethod("phoneChina",function(value,element,params){  
    	if(regMobile.test(value)){
    		return true;
    	}else{
    		return false;
    	}
    },"必须是有效的手机号码");
    
    */
});




function bindOnOffEvent(){
	$(".yes-onoff a").bind("click",function(){
		var fieldname = $(this).attr('data-fieldname');
		var enabled = 0;
		var expressId = $(this).attr('data-id');
		var that = $(this);
		var url = $(this).attr('data-url');
		
		if($(this).hasClass("enabled")){
			enabled = 0;
		}else{
			enabled = 1;
		}
		
		$.post(url ,{ formhash : formhash , fieldname : fieldname, value : enabled , id : expressId } , function(json){
			if(/成功/.test(json.message)){
				if(that.hasClass("enabled")){
					that.removeClass("enabled").addClass("disabled");
				}else{
					that.removeClass("disabled").addClass("enabled");
				}
			}else{
				
				showToast('error',json.message);
			}
			
			refreshFormHash(json.data);
		} , 'json');
	});
	
}
