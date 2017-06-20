/**
 * 个人中心
 */
$(function(){
	var dialog, cutDlg;
	
	var insending = false;
	
	var change_email = function(){
		if(!validation.valid()){
			return false;
		}
		
		if(insending){
			return false;
		}
		
		insending = true;
		dialog.find(".loading_bg").show();
		
		$.ajax({
			type:'POST',
			url:$("#editEmailForm").attr('action'),
			data: $("#editEmailForm").serialize(),
			success:function(json){
				insending = false;
				dialog.find(".loading_bg").hide();
				
				if(check_success(json.message)){
					dialog.dialog('close');
					$("#emailAddr").html($("input[name=newemail]").val());
					$("#emailVerfiyText").html("未认证邮箱");
					showToast('success',json.message);
				}else{
					showToast('error',json.message);
				}
			},
			error:function(){
				showToast('error',"操作失败，服务器错误");
				insending = false;
				dialog.find(".loading_bg").hide();
			}
		})
	};
	
	
	var save_avatar = function(){
		$("#cutForm").submit();
	}
	
	var validation = $("#editEmailForm").validate({
		submitHandler:function(){
			change_email();
		},
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
		resize:false,
		/*
	      buttons: {
	        "保存": change_email,
	        "关闭": function() {
	        	$(this).dialog( "close" );
	        }
	   },
	   */
	   open:function(){
		   validation.resetForm();
	   }
	});
	
	cutDlg = $("#imgCut").dialog({
		autoOpen: false,
		width: 800,
		modal: true,
	      buttons: {
	        "保存": save_avatar,
	        "关闭": function() {
	        	$(this).dialog( "close" );
	        }
	   },
	   
	   open:function(){
		   $('#cropbox').Jcrop({
	           	aspectRatio: 1,
	           	allowResize: false,
	           	setSelect: [ 0, 0, min_width, min_height],
	           	onSelect: updateCoords,
	           	onDblClick:save_avatar
           });
	   }
	});
	
	$("#edit_email").bind("click",function(){
		dialog.dialog('open');
	});
	
	var updateCoords = function(c){
		$('#x').val(c.x);
		$('#y').val(c.y);
		$('#w').val(c.w);
		$('#h').val(c.h);
	}
	
	/*
	$('#file_upload').uploadify({
        'fileTypeDesc' : '图片文件',
        'buttonText' : '选择图片文件',
        'fileTypeExts' : '*.jpg;*.png',
        'swf'      : swfUrl,
        'uploader' : uploadUrl,
        'onUploadSuccess' : function(file, data, response) {
        	var json = $.parseJSON(data);
        	
        	refreshFormHash(json);
        	
			if (json.error == 0) {
				$("input[name=old_avatar]").val($("input[name=avatar]").val());
            	$("input[name=old_avatar_id]").val($("input[name=avatar_id]").val());
            	
                $("input[name=avatar_id]").val(json.id);
                $("input[name=avatar]").val(json.url);
                $("#srcImg").html('<img id="cropbox" src="' + json.url + '"/>');
                
                cutDlg.dialog("option",{my: "center", at: "center", of: window }).dialog('open');
			} else {
				alert(json.msg);
			}
        }
    });
	*/
	
	
	$("input[name=copylink]").bind('click',function(){
		$("#tuiguang").select();
		showToast('warning','请按 Ctrl+C 完成复制');
	})
	
})