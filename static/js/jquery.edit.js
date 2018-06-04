(function($) {
 $.fn.inline_edit= function(options) {
     var settings = $.extend({}, {open: false, type:'POST',dataType:'json',clickNameSpace:'inlineEdit' }, options);
     return $(this).each(function() {
    	 $(this).click(onClick);
     });

     function onClick() {
         var span = $(this);
         var old_value = $(this).html();
         var fieldName = $(this).attr("data-fieldname");
         var id = $(this).attr("data-id");
         
         $('<input type="text">')
         .insertAfter($(this))
         .focus()
         .select()
         .val(old_value)
         .focusout(function(){
             var new_value = $(this).val();
             if(old_value != new_value && new_value != '') {
            	 var isReq = $(this).data('requesting');
            	 if(isReq){
            		 return;
            	 }
            	 
            	 $(this).data('requesting', true);
            	 
            	 $.ajax({
            		 url:settings.url,
            		 type:settings.type,
            		 dataType:settings.dataType,
            		 data: {fieldname:fieldName,id:id,value:new_value},
            		 success:function(data){
            			 $(this).data('requesting', false);
            			 
            			 if(!check_success(data.message)){
            				 showToast('error',data.message);
            				 span.show().text(old_value);
            			 }else{
            				 span.show().text(new_value);
            			 }
            			 
            		 },
            		 error:function(xhr, textStatus, errorThrown){
            			 showToast('error','系统错误');
            			 span.show().text(old_value);
            			 
            			 $(this).data('requesting', false);
            		 }
            	 });
            	 
                 
             } else {
                 span.show().text(old_value);
             }
             
             $(this).remove();
         })
         $(this).hide();
     }
}
})(jQuery);

(function($) {
 $.fn.inline_edit_confirm = function(options) {
     var settings = $.extend({}, {open: false}, options);
     return this.each(function() {
         $(this).click(onClick);
     });

     function onClick() {
         var $span = $(this);
         var old_value = $(this).text();
         var fieldName = $(this).attr("data-fieldname");
         var id = $(this).attr("data-id");
         var $input = $('<input type="text">');
         var $btn_submit = $('<a class="inline-edit-submit" href="javascript:;">确认</a>');
         var $btn_cancel = $('<a class="inline-edit-cancel" href="javascript:;">取消</a>');

         $input.insertAfter($span).focus().select().val(old_value);
         $btn_submit.insertAfter($input);
         $btn_cancel.insertAfter($btn_submit);
         $span.hide();

         $btn_submit.click(function(){
             var new_value = $input.val();
             if(new_value !== '' && new_value !== old_value) {
            	 
            	 var isReq = $(this).data('requesting');
            	 if(isReq){
            		 return;
            	 }
            	 
            	 $(this).data('requesting', true);
            	 
            	 $.ajax({
            		 url:settings.url,
            		 type:settings.type,
            		 dataType:settings.dataType,
            		 data: {fieldname:fieldName,id:id,value:new_value},
            		 success:function(data){
            			 $(this).data('requesting', false);
            			 
            			 if(!check_success(data.message)){
            				 showToast('error',data.message);
            				 span.show().text(old_value);
            			 }else{
            				 span.show().text(new_value);
            			 }
            			 
            		 },
            		 error:function(xhr, textStatus, errorThrown){
            			 $(this).data('requesting', false);
            		 }
            	 });
             }
             show();
         });

         $btn_cancel.click(function() {
             show();
         });

         function show() {
             $span.show();
             $input.remove();
             $btn_submit.remove();
             $btn_cancel.remove();
         }
     }
};
})(jQuery);


