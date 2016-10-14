;$(function(){
	var addDlg,editColorDlg;
	var insending = false;
	
	var handler = function(validationObj,dlg,formid){
		
		if(!validationObj.valid()){
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
			handler(validation,addDlg,"#colorAddForm");
		},
		rules: {
			color_name: {
				required:true,
				minlength: 1,
				maxlength:30
			}
		}
	});
	
	
	var validationEdit = $("#colorEditForm").validate({
		submitHandler:function(){
			handler(validationEdit,editColorDlg,"#colorEditForm");
		},
		rules: {
			color_name: {
				required:true,
				minlength: 1,
				maxlength:30
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
	
	editColorDlg = $("#editColorDlg" ).dialog({
		autoOpen: false,
		width: 260,
		modal: true,
	    open:function(){
	    	validationEdit.resetForm();
		   $(this).find(".loading_bg").hide();
	    }
	});
	
	
	$("input[name=addcolor]").bind("click",function(){
		addDlg.dialog('open');
	});
	
	$("a.edit").bind("click",function(){
		var colorName = $(this).attr('data-title');
		var id = $(this).attr("data-id");
		editColorDlg.find("input[name=color_name]").val(colorName);
		editColorDlg.find("input[name=id]").val(id);
		editColorDlg.dialog('open');
	});
});