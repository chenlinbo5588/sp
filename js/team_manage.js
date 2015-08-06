/**
 * 队伍维护
 */
$.loadingbar({ autoHide: true ,text:"更新中,请稍后..."});
$(function(){
	$("input[name=kickoff]").bind("click",function(e){
		
		var li = $(this).closest("li");
		var inputId = $(this).attr("data-id");
		
		li.css({
			background:"#000",
			opacity:0.2
		});
		
		$(inputId).val("yes");
	});
	
});