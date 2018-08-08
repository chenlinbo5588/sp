function avatarUpload(pParam){
	$(pParam.uploadId).uploadify({
        'auto'     : true,//自动上传
        'removeTimeout' : 1,//文件队列上传完成1秒后删除
        'swf'      : uploadifySwfUrl,
        'uploader' : pParam.uploadUrl,
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择图片',//设置按钮文本
        'multi'    : false,//允许同时上传多张图片
        'uploadLimit' : 0,//
        'queueSizeLimit':1,//上传队列只能1张
        'removeCompleted' : true,//是否消失进度
        'successTimeout': 30,    //成功等待时间
        'fileTypeExts' : '*.jpg;',//限制允许上传的图片后缀
        'fileSizeLimit' : uploadSizeLimit + 'KB',//限制上传的图片不得超过2M
   
        'removeCompleted' : true,//是否消失进度
        'onUploadSuccess' : function(file, data, response) {
        	//每次成功上传后执行的回调函数，从服务端返回数据到前端
        	var jsonResp = $.parseJSON(data);
        	
        	var doCutFn = function(cutUrl,jsonResp){
        		$.get(cutUrl , {url: jsonResp.url ,id: jsonResp.id , x: 200, y: 200 , resize: 0, ratio: 1} , function(html){
            		$("#avatarDlg").html(html).dialog({
            			  title:"裁切头像",
    	  	    	      autoOpen: false,
    	  	    	      height: 'auto',
    	  	    	      width: '65%',
    	  	    	      modal: true,
    	  	    	      position: { my : "center", at: "center", of: window }
    	          	}).dialog("open");
            	});
        	}
        	
        	setTimeout(function(){
        		doCutFn(pParam.cutUrl,jsonResp);
        	},500);
        	
        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数
           // if(img_id_upload.length>0)
           // alert('成功上传的文件有：'+encodeURIComponent(img_id_upload));
        }  
        // Put your options here
    });
	
}