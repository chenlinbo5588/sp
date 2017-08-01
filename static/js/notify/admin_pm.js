/**
 * 消息中心
 */
$(function(){
	
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
	
	
	$("input[name=msg_mode]").bind('click',function(){
		var val = $(this).val();
		
		switch(val){
			case '0':
				$(".userlist").hide();
				break;
			case '1':
			case '2':
				$(".userlist").show();
				break;
		}
		
	})
	
	
});