/**
 * 队伍
 */
$.loadingbar({ autoHide: true ,text:"更新中,请稍后..."});
$(function(){
	$("#seeSample").bind("click",function(){
		var that = $(this);
		if(that.data("sampleShow")){
			that.data("sampleShow",false);
			$("#samplWrap").slideUp("fast",function(){
				that.html("查看合影范例");
			});
		}
		
		var imgObj = $("#samplWrap img");
		imgObj.attr("src",imgObj.attr("data-src"));
		$("#samplWrap").show();
		that.data("sampleShow",true);
		that.html("收起合影范例");
	});
	
	$("#createTeamForm").bind("submit",function(){
		
		$("input.validation_error").removeClass("validation_error");
		
		if($.trim($("input[name=title]").val()) == ''){
			$("input[name=title]").addClass("validation_error").focus();
			$("#tip_title").html(FormErrorHtml('请输入球队名称'));
			return false;
		}
		
		return true;
		
	});
	
});