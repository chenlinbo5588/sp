/**
 * 货柜列表
 */
;$(function(){
	var goodsCodeDlg,titleDlg,confirmDlg;
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
	
	
	var validation = $("#slotForm").validate({
		submitHandler:function(){
			handler(goodsCodeDlg,'#slotForm');
		},
		rules: {
			goods_code : {
				required: true,
				minlength: 1,
				maxlength:10
			}
		}
	});
	
	var titleValidation = $("#slotTitleForm").validate({
		submitHandler:function(){
			handler(titleDlg,'#slotTitleForm');
		},
		rules: {
			title : {
				required: true,
				minlength: 1,
				maxlength:10
			}
		}
	});
	
	
	goodsCodeDlg = $("#goodsCodeDlg" ).dialog({
        autoOpen: false,
        width: 280,
        modal: true,
        open:function(){
           validation.resetForm();
           $(this).find(".loading_bg").hide();
        }
    });
	
	titleDlg = $("#titleDlg" ).dialog({
        autoOpen: false,
        width: 280,
        modal: true,
        open:function(){
        	titleValidation.resetForm();
        	$(this).find(".loading_bg").hide();
        }
    });
	
	/*
	confirmDlg = $("#confirmOf" ).dialog({
        autoOpen: false,
        width: 280,
        modal: true,
        open:function(){
        	$(this).find(".loading_bg").hide();
        }
    });
	*/
	
	$(".setgc").bind("click",function(){
		var id = $(this).closest(".slot_item").attr("data-id");
		var title = $(this).attr("data-title");
		$("input[name=slot_id]").val(id);
		
		goodsCodeDlg.dialog('option','title',title).dialog('open');
	});
	
	$(".mtitle").bind("click",function(){
		var id = $(this).closest(".slot_item").attr("data-id");
		var title = $(this).attr("data-title");
		$("input[name=slot_id]").val(id);
		$("#slotTitleForm input[name=title]").val(title);
		
		titleDlg.dialog('open');
	});
	
	/*
	$(".slot_item .onoff").bind("click",function(){
		var title = $(this).attr("data-title");
		var dlgTitle = "";
		if(title == "启用"){
			dlgTitle = "停用";
		}else if(title == "停用"){
			dlgTitle = "启用";
		}
		
		
		confirmDlg.dialog("option","title",dlgTitle).dialog('open');
	});
	*/
})