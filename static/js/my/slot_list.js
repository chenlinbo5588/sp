/**
 * 货柜列表
 */
;$(function(){
	var goodsCodeDlg;
	var insending = false;
	
	var handler = function(){
		if(!validation.valid()){
			return false;
		}
		
		if(insending){
			return false;
		}
		
		insending = true;
		goodsCodeDlg.find(".loading_bg").show();
		
		$.ajax({
			type:'POST',
			url:$("#slotForm").attr('action'),
			data: $("#slotForm").serialize(),
			success:function(json){
				insending = false;
				goodsCodeDlg.find(".loading_bg").hide();
				
				if(check_success(json.message)){
					goodsCodeDlg.dialog('close');
					showToast('success',json.message);
					
					
					
				}else{
					showToast('error',json.message);
				}
			},
			error:function(){
				showToast('error',"操作失败，服务器错误");
				insending = false;
				goodsCodeDlg.find(".loading_bg").hide();
			}
		})
		
	};
	
	
	var validation = $("#slotForm").validate({
		submitHandler:function(){
			handler();
		},
		rules: {
			goods_code : {
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
	
	$(".setgc").bind("click",function(){
		var id = $(this).attr("data-id");
		var title = $(this).attr("data-title");
		$("input[name=slot_id]").val(id);
		
		goodsCodeDlg.dialog('option','title',title).dialog('open');
	});
	
	
	$(".mtitle").bind("click",function(){
		var id = $(this).attr("data-id");
		var title = $(this).attr("data-title");
		$("input[name=slot_id]").val(id);
		
		goodsCodeDlg.dialog('option','title',title).dialog('open');
	});
})