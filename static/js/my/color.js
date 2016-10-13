;$(function(){
	var addDlg;
	var insending = false;
	
	var handler = function(dlg,formid){
		if(!validation.valid()){
			return false;
		}
		
		if(insending){
			return false;
		}
		
		insending = true;
		dlg.find(".loading_bg").show();
		
		$.ajax({
			type:'POST',
			url:$(formid).attr('action'),
			data: $(formid).serialize(),
			success:function(json){
				insending = false;
				dlg.find(".loading_bg").hide();
				
				if(check_success(json.message)){
					dlg.dialog('close');
					showToast('success',json.message);
					
					setTimeout(function(){
						location.reload();
					},1000);
					
					
				}else{
					showToast('error',json.message);
				}
			},
			error:function(){
				showToast('error',"操作失败，服务器错误");
				insending = false;
				dlg.find(".loading_bg").hide();
			}
		})
		
	};
	
	
	var validation = $("#colorAddForm").validate({
		submitHandler:function(){
			handler(addDlg,"#colorAddForm");
		},
		rules: {
			color_name: {
				required:true,
				remote: color_url,
				onkeyup:false,
				onchange:false,
				minlength: 1,
				maxlength:30
			}
		},
		messages: {
			color_name:{
				remote: "该颜色已定义"
			}
			
		}
	});
	
	
	addDlg = $("#addColorDlg" ).dialog({
		autoOpen: false,
		width: 260,
		modal: true,
	    open:function(){
		   validation.resetForm();
		   $(this).find(".loading_bg").hide();
	    }
	});
	
	
	$("input[name=addcolor]").bind("click",function(){
		addDlg.dialog('open');
	});
});