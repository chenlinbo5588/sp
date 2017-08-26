    <script>
    	function add_uploadedfile(file_data)
	  	{
	  		var delLink = '<a class="deleteFile" data-id="' + file_data.id + '" href="javascript:void(0);" data-url="{site_url('budongchan/deleteFile?bdc_id='|cat:$info['id'])}" data-title="' + file_data.orig_name + '">删除</a>';
	  		var newItem = '<tr id="file' + file_data.id + '"><td><input type="hidden" name="file_id[]" value="' + file_data.id + '"/>' + delLink + '</td><td><strong>' + file_data.orig_name + '</strong></td><td>' + file_data.username + '</td><td>' + file_data.gmt_create + '</td></tr>';
		    $('#ftb{$profile['basic']['dept_id']} tbody').prepend(newItem);
	   	}
	   	
    	$(function(){
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
	
	            createSwfUpload(index + 1,$(this).attr("data-url"),{ formhash: formhash, lsno: "{$info['lsno']}",bdc_id: "{$info['id']}" },$(this).attr("data-allowsize"),$(this).attr("data-allowfile"),handler);
	        });
    	})
    </script>