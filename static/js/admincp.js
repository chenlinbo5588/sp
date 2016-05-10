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
	
	// 全选 start
	$('.checkall').click(function(){
		var group = $(this).prop("name");
		$("input[group="+group+"]").prop("checked" , $(this).prop("checked") );
	});


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
	
	
    
    
    districtSelect('bind');
    
    $.validator.addMethod("phoneChina",function(value,element,params){  
    	if(regMobile.test(value)){
    		return true;
    	}else{
    		return false;
    	}
    },"必须是有效的手机号码");
});



/**
 * 删除功能逻辑
 * @param postdata
 * @param url
 * @returns
 */
function doDelete(postdata,url){
	$.ajax({
		type:"POST",
		url:url,
		data: postdata,
		success:function(json){
			alert(json.message);
			refreshFormHash(json.data);
			for(var i = 0; i < postdata['id'].length; i++){
				$("#row" + postdata['id'][i]).remove();
			}
			
		},
		error:function(event,request, settings){
			alert("删除出错");
		}
	});
}


function bindDeleteEvent(){
	$("a.delete").bind("click",function(){
		if(confirm('您确定要删除吗')){
			var url = $(this).attr("data-url");
	  		doDelete({ "formhash" : formhash , "id": [$(this).attr("data-id")] } ,url);
		}
  	});
  	
  	$("#deleteBtn").bind("click",function(){
  		
  		var ids = [];
  		var url = $(this).attr("data-url");
  		
  		var checkboxName = $(this).attr('data-checkbox');
  		
  		$("input[name='" + checkboxName + "']:checked").each(function(){
  			ids.push($(this).val());
  		});
  		
  		if(ids.length == 0){
  			alert("请先勾选");
  		}else{
  			
  			if(confirm('您确定要删除吗')){
  				doDelete({ "formhash" : formhash ,"id": ids }, url);
  	  		}
  		}
  		
  	});
}


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
		
		$.post(url ,{ formhash : formhash , fieldname : fieldname, enabled : enabled , id : expressId } , function(json){
			if(json.message = '保存成功'){
				if(that.hasClass("enabled")){
					that.removeClass("enabled").addClass("disabled");
				}else{
					that.removeClass("disabled").addClass("enabled");
				}
			}
			
			refreshFormHash(json.data);
		} , 'json');
	});
	
}
