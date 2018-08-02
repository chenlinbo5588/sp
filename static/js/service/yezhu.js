$(function(){
	
	$( ".datepicker" ).datepicker();
	
	$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
	bindAjaxSubmit("#infoform");
	
	
	$("#id_no").bind("focusout",function(){
		var idNo = $.trim($(this).val());
		var idType = $("#id_type option:selected").html();
		if(('身份证' == idType || '驾驶证' == idType) && idNo.length >= 15){
			var sex = parseInt(idNo.substring(idNo.length - 2, idNo.length - 1));
			
			if(sex % 2 == 0){
				$("select[name=sex]").find("option:eq(0)").attr("selected","selected");
			}else{
				$("select[name=sex]").find("option:eq(1)").attr("selected","selected");
			}
			
			var birthday = idNo.substring(6,14);
			$("#birthday").val(birthday.substring(0,4) + '-' + birthday.substring(4,6) + '-' + birthday.substring(6,8));
			
			var currentYear = (new Date()).getFullYear();
			var age = currentYear - parseInt(birthday.substring(0,4));
			$("#age").val(age);
			
			if(typeof(province_idcard[idNo.substring(0,3) + "000"])){
				var provinceName = province_idcard[idNo.substring(0,3) + "000"];
				
				$("#jiguan option").each(function(){
					if(provinceName == $(this).html()){
						$(this).attr("selected","selected");
					}
				});
			}
		}
		
	});
});