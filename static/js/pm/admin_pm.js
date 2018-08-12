$(function(){
	
	KindEditor.ready(function(K) {
		editor1 = K.create('textarea[name="content"]', {
			uploadJson : commonUploadUrl ,
            filePostName:'Filedata',
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
