$(function(){
	
	$( ".datepicker" ).datepicker();
	
	
	bindOpEvent({ selector : ".opBtn" });
	
	var detailDlg = $("#pmDetailDlg").dialog({
		autoOpen: false,
		width: 800,
		height: 500,
		modal: true,
		resize:false,
		 buttons: {
			 /*
			"上一条":function(){
				
			},
			"下一条":function(){
			},*/
	        "关闭": function() {
	        	$(this).dialog( "close" );
	        }
	   },
		open:function(){
	   }
	});
	
	$(".popwin").bind('click',function(){
		var url = $(this).attr("data-url");
		
		detailDlg.html('<div class="loading_bg">读取中...</div><div id="pmDetail"></div>').dialog('open');
		
		$("#pmDetail").load(url, function(html){
			detailDlg.find(".loading_bg").hide();
		});
	});

});
