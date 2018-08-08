function call_back(resp){
    refreshFormHash(resp);
    $("#old_pic").val($('#avatar').val());
    
    $('#avatar').val(resp.picname);
    $('#view_img').attr('src',resp.url);
    
    $("#avatarDlg" ).dialog( "close" );
}


function resizeDlg(){
	$("#avatarDlg").dialog({
	    position: { my : "center", at: "center", of: window }
  	});
}


$(function(){
	
	$( ".datepicker" ).datepicker();
	$('.fancybox').fancybox();
	
	
	var uploadConfig = [
		{
			fileId : '#fileupload',
			uploadUrl : uploadUrls.img.uploadUrl,
			deleteUrl : uploadUrls.img.deleteUrl,
			fileAllow :  "*.jpg",
			uploadLimit: 0,
			buttonText: '选择图片',
			deleteBtn : 'a.delLink',
			appendId : '#imageList',
			successHandler : function(file_data){
			    var newImg = '<li id="img' + file_data.id + '" class="picture"><input type="hidden" name="img_file_id[]" value="' + file_data.id + '" /><div class="size-64x64"><span class="thumb"><i></i><a class="fancybox" href="' + file_data.img_b +'" data-fancybox-group="gallery"><img src="' + file_data.img_m + '" alt="" width="64px" height="64px"/></a></span></div><p><span><a class="delLink" data-id="' + file_data.id + '" href="javascript:void(0);">删除</a></span></p></li>';
			    $(this.appendId).prepend(newImg);
			},
			deleteFileHandler: function(clickedObj){
				if(!window.confirm('您确定要删除吗?')){
			        return;
			    }
			    
				var fileId = clickedObj.attr('data-id');
				
			    $.getJSON(this.deleteUrl, {  file_id : fileId } , function(result){
			    	refreshFormHash(result.data);
			    	
			        if(result){
			            $('#img' + fileId).remove();
			        }else{
			        	showToast('error','删除失败');
			        }
			    });
			}
		},
		
		{
			fileId : '#other_fileupload',
			uploadUrl : uploadUrls.file.uploadUrl,
			deleteUrl : uploadUrls.file.deleteUrl,
			fileAllow :  "*.pdf;*.doc;*.docx",
			uploadLimit: 0,
			buttonText: '选择文件',
			deleteBtn : 'a.delLink',
			appendId : '#fileList',
			successHandler : function(file_data){
			    var newFile = '<tr id="file' + file_data.id + '"><td><input type="hidden" name="file_id[]" value="' + file_data.id + '" /><a target="_blank" href="' + file_data.url + '">' + file_data.file_name + '</a></td><td>' +  file_data.file_size + '</td><td><a class="delLink" data-id="' + file_data.id + '" href="javascript:void(0);">删除</a></td></tr>';
			    $(this.appendId).prepend(newFile);
			},
			deleteFileHandler: function(clickedObj){
				if(!window.confirm('您确定要删除吗?')){
			        return;
			    }
			    
				var fileId = clickedObj.attr('data-id');
				
			    $.getJSON(this.deleteUrl, {  file_id : fileId } , function(result){
			    	refreshFormHash(result.data);
			    	
			        if(result){
			            $('#file' + fileId).remove();
			        }else{
			        	showToast('error','删除失败');
			        }
			    });
			}
		}
	];
	
	for(var i = 0; i < uploadConfig.length; i++){
		MySwfUploader(uploadConfig[i]);
	}
	
	
	avatarUpload({
		uploadId : '#avatarFile',
		uploadUrl : commonUploadUrl,
		cutUrl : cutUrl
	});
	
	
	KindEditor.ready(function(K) {
		remarkEditor = K.create('textarea[name="remark"]', {
            /*uploadJson : KEUploadUrl,
            filePostName:'Filedata',*/
            extraFileUploadParams:{ formhash: formhash },
            items : [
             		'undo', 'redo', '|', 'cut', 'copy', 'paste',
             		'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
             		'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
             		'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
             		'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
             		'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|',  'table', 'hr', 'pagebreak'
             	],
            allowImageUpload : true,
            allowFlashUpload : false,
            allowMediaUpload : false,
            formatUploadUrl : false,
            allowFileManager : false,
            afterCreate : function() {
            	
            },
            afterChange : function() {
            	$("input[name=formhash]").val(formhash);
            },
            afterUpload : function(url,data) {
            	formhash = data.formhash;
            }
        });
    });
	
	
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