   {include file="./house_form.tpl"}
   	{include file="./house_script.tpl"}
	<script>
		
		$(function(){
	  		$(".date" ).datepicker({
	  			changeMonth: true,
      			changeYear: true
	  		});
	  		
	  		$(".Uploader").each(function(index){
                var handler = {
                    success:function(file,serverData){
                        try {
                            var progress = new FileProgress(file, this.customSettings.progressTarget);
                            progress.setComplete();
                            progress.setStatus("Complete.");
                            progress.toggleCancel(false);

                            if(typeof(this.customSettings.callback) != "undefined"){
                                this.customSettings.callback(file,serverData);
                            }
                            
                            var response = $.parseJSON(serverData);
                            
                            if(response.data.error == 0){
                            	add_uploadedfile(response.data);
                            }

                        } catch (ex) {
                            this.debug(ex);
                        }
                    }
                };

                createSwfUpload(index + 1,$(this).attr("data-url"),{ owner_id: "{$info['owner_id']}" , house_id : "{$info['hid']}" },$(this).attr("data-allowsize"),$(this).attr("data-allowfile"),handler);
            });
	  		
	  		$.loadingbar({ urls: [ new RegExp('{site_url('house/edit')}') ],text:"操作中,请稍后..." ,container : '#buildingInfoDlg'});
	  		
	  		bindAjaxSubmit("#user_form");
	  });
	  
	</script>