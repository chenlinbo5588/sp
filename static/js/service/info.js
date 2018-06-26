$(function(){
	
	KindEditor.ready(function(K) {
		remarkEditor = K.create('textarea[name="remark"]', {
            uploadJson : KEUploadUrl,
            filePostName:'Filedata',
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
	
	
	function add_uploadedfile(file_data)
	{
	    var newImg = '<li id="' + file_data.id + '" class="picture"><input type="hidden" name="file_id[]" value="' + file_data.id + '" /><div class="size-64x64"><span class="thumb"><i></i><a class="fancybox" href="' + file_data.img_b +'" data-fancybox-group="gallery"><img src="' + file_data.img_m + '" alt="" width="64px" height="64px"/></a></span></div><p><span><a href="javascript:del_file_upload(\'' + file_data.id + '\');">删除</a></span></p></li>';
	    $('#thumbnails').prepend(newImg);
	}
	
	
	function del_file_upload(file_id)
	{
	    if(!window.confirm('您确定要删除吗?')){
	        return;
	    }
	    
	    $.getJSON(deleteImgUrl + '&file_id=' + file_id + "&id=" + $("input[name=id]").val(), function(result){
	    	refreshFormHash(result.data);
	    	
	        if(result){
	            $('#' + file_id).remove();
	        }else{
	            alert('删除失败');
	        }
	    });
	}
	
	
	$('#fileupload').uploadify({
        'auto'     : true,//自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : uploadifySwfUrl,
        'uploader' : uploadUrl,
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
         
        'buttonText' : '选择图片',//设置按钮文本
        'multi'    : true,//允许同时上传多张图片
        'uploadLimit' : 0,//一次最多只允许上传N张图片,0表示无显示
        'removeCompleted' : true,//是否消失进度
        'successTimeout': 30,    //成功等待时间
        'fileTypeExts' : '*.jpg;',//限制允许上传的图片后缀
        'fileSizeLimit' : uploadSizeLimit + 'KB',//限制上传的图片不得超过2M
   
        'removeCompleted' : true,//是否消失进度
        'onUploadSuccess' : function(file, data, response) {
        	//每次成功上传后执行的回调函数，从服务端返回数据到前端
        	add_uploadedfile($.parseJSON(data));
        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数
           // if(img_id_upload.length>0)
           // alert('成功上传的文件有：'+encodeURIComponent(img_id_upload));
        }  
        // Put your options here
    });
	
});