$(function(){
	
	$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
	
	bindAjaxSubmit("#infoform");
	
	$('.fancybox').fancybox();
	
	$( "#address" ).autocomplete({
		source: function( request, response ) {
			
			$.ajax( {
	            url: searchAddressUrl,
	            dataType: "json",
	            data: {
	              term: request.term,
	              resident_id:$("input[name=resident_id]:checked").val(),
	            },
	            success: function( data ) {
	              response( data );
	            }
	          } 
			);
        },
		minLength: 2,
		select: function( event, ui ) {
			$("input[name=yezhu_name]").val(ui.item.name);
			$("input[name=mobile]").val(ui.item.mobile);
		}
    });
	
	
	$( "#worker_mobile" ).autocomplete({
		source: searchWorkerUrl,
		minLength: 2,
		select: function( event, ui ) {
			$("input[name=worker_mobile]").val(ui.item.mobile);
			$("input[name=worker_name]").val(ui.item.name);
		}
    });
	
	
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
		}
	];
	
	for(var i = 0; i < uploadConfig.length; i++){
		MySwfUploader(uploadConfig[i]);
	}
});