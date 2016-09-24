/**
 * 个人中心
 */
$(function(){
	var dialog;
	
	function change_email(){
		if(!validation.valid()){
			return false;
		}
		
		$("#editEmailForm").submit();
		return true;
	}
	
	var validation = $("#editEmailForm").validate({
		rules: {
			newemail: {
				required: true,
				email: true
			}
		}
	});
	
	
	dialog = $("#dialog" ).dialog({
		autoOpen: false,
		width: 300,
		modal: true,
	      buttons: {
	        "保存": change_email,
	        "关闭": function() {
	          dialog.dialog( "close" );
	        }
	   },
	   
	   open:function(){
		   validation.resetForm();
	   }
	});
	
	$("#edit_email").bind("click",function(){
		dialog.dialog('open');
	});
})