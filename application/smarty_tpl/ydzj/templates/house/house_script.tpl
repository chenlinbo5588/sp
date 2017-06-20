<script>
	function del_file_upload(file_id){
		if(!window.confirm('您确定要删除吗?')){
			return;
		}
		
		
		
		$.getJSON('{site_url("house/delimg")}' ,{ file_id : file_id , person_id: $("input[name=owner_id]").val(), house_id : $("input[name=hid]").val() }, function(result){
		    if(result){
		        $('#' + file_id).remove();
		    }else{
		        alert('删除失败');
		    }
		});
	  }
	
	  function add_uploadedfile(file_data)
	  {
	  		var newImg = '<li id="' + file_data.id + '" class="picture"><input type="hidden" name="file_id[]" value="' + file_data.id + '" /><div class="size-80x80"><span class="thumb"><i></i><a href="' + file_data.image_url_b + '" class="fancybox" data-fancybox-group="gallery"><img src="' + file_data.image_url_m + '" alt="" width="80px" height="80px"/></a></span></div><p><span><a href="javascript:del_file_upload(\'' + file_data.id + '\');">删除</a></span></p></li>';
		    $('#thumbnails').prepend(newImg);
	   }

</script>