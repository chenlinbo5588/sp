$(function(){
	$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
	bindAjaxSubmit("#infoform");
	
	$("body").delegate('.dynBtn','click',function(){
		
		var that = $(this);
		console.log(that);
		
		var html = $("#templateRow").html();
		
		//html.replace(/\[\]/,'\\[\\]');
		
		var newRow = $(html);
		
		if(that.val() == '添加'){
			$("#feeConfigTable tbody").append(newRow);
		}else{
			
			if(that.parent().parent().index() != 0){
				that.parent().parent().remove();
			}
		}
	});
	
	$("body").delegate(".changType","change",function(){
		
		var feeName = $(this).val();
		
		var wuyeTypeSelect = $(this).parent().siblings().find(".wuyeType");
		
		wuyeTypeSelect.html('');
		
		var option = [];
		if(feeName == '物业费'){
			for(var typeName in wuyeTypeJson){
				option.push( "<option value='"+typeName+"'>" + typeName + "</option>");
			}
		}
		if(feeName == '车位费'){
			for(var typeName in parkingTypeJson){
				option.push( "<option value='"+typeName+"'>" + typeName + "</option>");
			}
		}
		wuyeTypeSelect.html(option.join(''));
	});
});