/**
 * 个人中心
 */
$(function(){
	var dialog, cutDlg;
	
	var change_email = function(){
		if(!validation.valid()){
			return false;
		}
		
		$("#editEmailForm").submit();
		return true;
	};
	
	
	var save_avatar = function(){
		$("#cutForm").submit();
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
	
	cutDlg = $("#imgCut").dialog({
		autoOpen: false,
		width: 800,
		modal: true,
	      buttons: {
	        "保存": save_avatar,
	        "关闭": function() {
	        	cutDlg.dialog( "close" );
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
	
	
	
	KindEditor.ready(function(K) {
		var uploadbutton = K.uploadbutton({
			button : K('#uploadButton')[0],
			fieldName : 'imgFile',
			extraParams : {  },
			url : uploadUrl,
			afterUpload : function(data) {
				refreshFormHash(data);
				if (data.error === 0) {
					$("input[name=old_avatar]").val($("input[name=avatar]").val());
	            	$("input[name=old_avatar_id]").val($("input[name=avatar_id]").val());
	            	
	                $("input[name=avatar_id]").val(data.id);
	                $("input[name=avatar]").val(data.url);
					
	                $("#srcImg").html('<img id="cropbox" src="' + data.url + '"/>');
	                
	                cutDlg.dialog('open');
				} else {
					alert(data.msg);
				}
			},
			afterError : function(str) {
				alert('自定义错误信息: ' + str);
			}
		});
		uploadbutton.fileBox.change(function(e) {
			uploadbutton.submit();
		});
	});
	
})