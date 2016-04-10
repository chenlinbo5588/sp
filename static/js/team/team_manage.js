/**
 * 队伍维护
 */
$.loadingbar({ autoHide: true ,text:"更新中,请稍后..."});
$(function(){
	
	if(errorInputKey.length > 0){
		var name = "", name0 ="";
		
		for(var i = 0 ; i < errorInputKey.length; i++){
			name = errorInputKey[i].replace(/\[/g,"\\[").replace(/\]/g,"\\]");
			
			if(0 == i){
				name0 = name;
			}
			
			$("input[name=" + name + "]").addClass("validation_error");
		}
		
		$("input[name=" + name0 + "]").addClass("validation_error").focus();
		
	}
	
	$(".delmask").bind("click",function(e){
		var li = $(this).closest("li");
		var inputId = $("input[name=kickoff]",li).attr("data-id");
		$("#" + inputId).val("");
		
		$(this).slideToggle("normal");
		
		
	});
	
	$("input[name=kickoff]").bind("click",function(e){
		
		var li = $(this).closest("li");
		var inputId = $(this).attr("data-id");
		
		$("#" + inputId).val("yes");
		$(".delmask",li).slideToggle("normal");
		
	});
	
});