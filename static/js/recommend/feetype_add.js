$(function(){
	$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
	bindAjaxSubmit("#infoform");
	
	$("body").delegate('.dynBtn','click',function(){
		
		var that = $(this);
		console.log(that);
		
		var html = $("#templateRow").html();
				
		var newRow = $(html);
		
		if(that.val() == '添加'){
			$("#feeConfigTable tbody").append(newRow);
		}else{
			
			if(that.parent().parent().index() != 0){
				that.parent().parent().remove();
			}
		}
	});
	
});