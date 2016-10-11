/**
 * 消息中心
 */
$(function(){
	var privatePmDlg;
	var insending = false;
	
	//console.log(validation.valid());
	var sendPm = function(){
		if(!validation.valid()){
			return false;
		}
		
		if(insending){
			return false;
		}
		
		insending = true;
		privatePmDlg.find(".loading_bg").show();
		
		$.ajax({
			type:'POST',
			url:$("#privatePmForm").attr('action'),
			data: $("#privatePmForm").serialize(),
			success:function(json){
				insending = false;
				privatePmDlg.find(".loading_bg").hide();
				
				if(check_success(json.message)){
					privatePmDlg.dialog('close');
					showToast('success',json.message);
				}else{
					showToast('error',json.message);
				}
			},
			error:function(){
				showToast('error',"操作失败，服务器错误");
				insending = false;
				privatePmDlg.find(".loading_bg").hide();
			}
		})
		
	};
	
	
	var validation = $("#privatePmForm").validate({
		submitHandler:function(){
			sendPm();
		},
		rules: {
			to_username: {
				required:true,
				remote: username_url
			},
			title : {
				required: true,
				minlength: 1,
				maxlength:30
			},
			content : {
				required: true,
				minlength: 1,
				maxlength:200
			}
		},
		messages: {
			to_username:{
				remote: "用户名不存在"
			}
			
		}
	});
	
	
	privatePmDlg = $("#privatePmDlg" ).dialog({
		autoOpen: false,
		width: 500,
		modal: true,
		
		/*
	    buttons: {
	        "保存": sendPm,
	        "关闭": function() {
	        	$(this).dialog( "close" );
	        }
	   },
	   */
	   open:function(){
		   validation.resetForm();
		   $(this).find(".loading_bg").hide();
	   }
	});
	
	
	$("#jsUserSendMsg").bind("click",function(){
		privatePmDlg.dialog('open');
	});
	
	var detailDlg = $("#pmDetailDlg").dialog({
		autoOpen: false,
		width: 800,
		height: 500,
		modal: true,
		resize:false,
		 buttons: {
			 /*
			"上一条":function(){
				
			},
			"下一条":function(){
			},*/
	        "关闭": function() {
	        	$(this).dialog( "close" );
	        }
	   },
		open:function(){
	   }
	});
	
	$(".popwin").bind('click',function(){
		var url = $(this).attr("data-url");
		
		detailDlg.html('<div class="loading_bg">读取中...</div><div id="pmDetail"></div>').dialog('open');
		$("#pmDetail").load(url, function(html){
			detailDlg.find(".loading_bg").hide();
		});
	});
	
})