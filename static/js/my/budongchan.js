;$(function(){
	
	
	
	$("input[name=to_dept]").bind('click',function(){
		var checked = $(this).prop('checked');
		$(".senderList li").removeClass('selected');
		
		if(checked){
			$(this).parents('li').addClass('selected');
		}
	});
	
	if($("input[name=to_dept]").size() == 1){
		$(".senderList li").addClass('selected');
		$("input[name=to_dept]:eq(0)").prop('checked',true);
	}
	
	
	$("a.loguser").bind('click',function(){
		var that = $(this);
		var url = that.attr('data-url');
		
		var dlg = $("#showDlg" ).dialog({
			title: "联系方式",
			autoOpen: false,
			width: 220,
			position: that ? { my: "center", at: "center", of: that } : null,
			modal: true,
		   open:function(){
			  
		   }
		}).html('正在获取联系方式，请稍后...').dialog("open");
		
		
		
		$.get(url, { } ,function(data){
			dlg.html(data);
		});
	});
	
	
	$.loadingbar({ urls: urlsConfig ,text:"操作中,请稍后..." ,container : '#bdcWrap' });
	bindDeleteEvent({ linkClass:'a.deleteFile',rowPrefix: '#file' });
	bindAjaxSubmit("#addForm");
	
});