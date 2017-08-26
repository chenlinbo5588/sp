<link rel="stylesheet" type="text/css" href="{resource_url('css/swfupload.css')}" media="all" />
<script type="text/javascript" src="{resource_url('js/swfup/swfupload.js')}"></script>
<script type="text/javascript" src="{resource_url('js/swfup/swfupload.queue.js')}"></script>
<script type="text/javascript" src="{resource_url('js/swfup/fileprogress.js')}"></script>
<script type="text/javascript" src="{resource_url('js/swfup/handlers.js')}"></script>
<script>



function createSwfUpload(index,upload_url,postdata,allowSize, allowFile, handlers){
    if(!postdata){
        postdata = { };
    }
    
    postdata = $.extend(postdata, { "PHPSESSID" : '{$PHPSESSID}' } );
     
    
    var upload = new SWFUpload({
        // Backend Settings
        upload_url: upload_url,
        post_params: postdata,
        // File Upload Settings
        file_size_limit : allowSize,
        file_types : allowFile ,
        file_types_description : "选择文件",
        file_upload_limit : "0",
        file_queue_limit : "0",
        // Event Handler Settings (all my handlers are in the Handler.js file)
        file_dialog_start_handler : fileDialogStart,
        file_queued_handler : fileQueued,
        file_queue_error_handler : fileQueueError,
        file_dialog_complete_handler : fileDialogComplete,
        upload_start_handler : uploadStart,
        upload_progress_handler : uploadProgress,
        upload_error_handler : uploadError,
        upload_success_handler : handlers.success ? handlers.success : uploadSuccess,
        upload_complete_handler : uploadComplete,
        button_placeholder_id : "UploaderPlaceholder_" + index,
        // Button Settingsimg/fee
        button_image_url : "{resource_url('img/swfup/XPButtonNoText_61x22.png')}",
        button_width: 61,
        button_height: 22,
        button_text : '选择文件',
        button_text_left_padding: 2,
        button_text_top_padding: 2,
        button_window_mode: "TRANSPARENT",
        button_cursor: SWFUpload.CURSOR.HAND,
        // Flash Settings
        flash_url : "{resource_url('js/swfup/swfupload.swf')}",
        custom_settings : {
            progressTarget : "UploaderProgress_" + index,
            cancelButtonId : "UploaderCancelBtn_" + index
        },
        // Debug Settings
        debug: false
    });

    return upload;
}




</script>
