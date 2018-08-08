    <link rel="stylesheet" type="text/css" href="{resource_url('js/uploadify/uploadify.css')}" />
	<script type="text/javascript" src="{resource_url('js/uploadify/jquery.uploadify.min.js')}"></script>
	<script>
		var uploadifySwfUrl = "{resource_url('js/uploadify/uploadify.swf')}";
		function MySwfUploader(pParam){
			
			$(pParam.appendId).delegate(pParam.deleteBtn,'click',function(){
				pParam.deleteFileHandler($(this));
			});
			
			$(pParam.fileId).uploadify({
		        'auto'     : true,//自动上传
		        'removeTimeout' : 1,//文件队列上传完成1秒后删除
		        'swf'      : uploadifySwfUrl,
		        'uploader' : pParam.uploadUrl,
		        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
		         
		        'buttonText' : pParam.buttonText,//设置按钮文本
		        'multi'    : true,//允许同时上传多张图片
		        'uploadLimit' : pParam.uploadLimit,//一次最多只允许上传N张图片,0表示无限制
		        'removeCompleted' : true,//是否消失进度
		        'successTimeout': 30,    //成功等待时间
		        'fileTypeExts' : pParam.fileAllow,//限制允许上传的图片后缀
		        'fileSizeLimit' : {config_item('image_max_filesize')} + 'KB',//限制上传的图片不得超过2M
		   
		        'removeCompleted' : true,//是否消失进度
		        'onUploadSuccess' : function(file, data, response) {
		        	//每次成功上传后执行的回调函数，从服务端返回数据到前端
		        	var jsonResp = $.parseJSON(data);
		        	
		        	if(!jsonResp.error){
		        		pParam.successHandler(jsonResp);
		        	}else{
		        		showToast('error', jsonResp.msg);
		        	}
		        },
		        'onQueueComplete' : function(queueData) {
		           // 上传队列全部完成后执行的回调函数
		           // if(img_id_upload.length>0)
		           // alert('成功上传的文件有：'+encodeURIComponent(img_id_upload));
		        }  
		        // Put your options here
		    });
		}
	</script>
