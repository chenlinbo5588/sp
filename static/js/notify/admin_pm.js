/**
 * 消息中心
 */
$(function(){
	
	
	var validation = $("#notifyForm").validate({
		rules: {
			send_group: {
				required:true
			},
			title : {
				required: true,
				minlength: 1,
				maxlength:30
			}
		}
	});
	
	
	$("#notifyForm").submit(function(){
		if(!validation.valid()){
			return false;
		}
		
		return true;
	});
	

	
	
	KindEditor.ready(function(K) {
		editor1 = K.create('textarea[name="content"]', {
            uploadJson : uploadUrl,
            extraFileUploadParams:{ formhash: formhash },
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
	
});