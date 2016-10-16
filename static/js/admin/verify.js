;$(function(){
	var reasonDialog;
	var insending = false;
	
	var handler = function(validationObj,dlg,formid,afterSuccess){
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
					
					if(typeof(afterSuccess) != 'undefined'){
						afterSuccess(json);
					}
					
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
	
	
	//bindOpEvent("input.updateBtn,a.verifypass");
    
	$('.fancybox').fancybox({
        closeBtn  : true
    });
    
    
	var validation = $("#verifyForm").validate({
		submitHandler:function(){
			handler(validation,reasonDialog, "#verifyForm",function(json){
				$("#row" + json.data.id).remove();
			});
		},
		rules: {
			remark : {
				required: true,
				minlength: 1,
				maxlength:30
			}
		}
	});
	
	
	reasonDialog = $("#reasonDlg" ).dialog({
		autoOpen: false,
		modal: true,
	   open:function(){
		   validation.resetForm();
		   $(this).find(".loading_bg").hide();
	   }
	});
	
	
	$("a.verify").bind("click",function(){
		var title = $(this).attr('data-title');
		var id = $(this).attr('data-id');
		
		if($(this).hasClass('pass')){
			reasonDialog.find("input[name=pass]").val(1);
		}else{
			reasonDialog.find("input[name=pass]").val(-1);
		}
		
		reasonDialog.find("input[name=id]").val(id);
		reasonDialog.dialog('option',{'title':title,'position': { 'my' : 'right center','at':'right center','of' : $(this) }} ).dialog('open');
	});
	
})