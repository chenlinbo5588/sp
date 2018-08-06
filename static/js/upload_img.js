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

$(function(){
	
	function add_uploadedfile(file_data)
	{
	    var newImg = '<li id="' + file_data.id + '" class="picture"><input type="hidden" name="file_id[]" value="' + file_data.id + '" /><div class="size-64x64"><span class="thumb"><i></i><a class="fancybox" href="' + file_data.img_b +'" data-fancybox-group="gallery"><img src="' + file_data.img_m + '" alt="" width="64px" height="64px"/></a></span></div><p><span><a href="javascript:del_file_upload(\'' + file_data.id + '\');">删除</a></span></p></li>';
	    $('#thumbnails').prepend(newImg);
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
        	var jsonResp = $.parseJSON(data);
        	
        	if(!jsonResp.error){
        		add_uploadedfile(jsonResp);
        	}else{
        		showToast('error', jsonResp.msg);
        	}
        	
        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数
           // if(img_id_upload.length>0)
           // alert('成功上传的文件有：'+encodeURIComponent(img_id_upload));
        }  
        // Put your options here
    });
	
});