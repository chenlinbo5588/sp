$(function(){
	$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
	bindAjaxSubmit("#infoform");
		
		
	$( "#mobile" ).autocomplete({
      source: searchYezhuUrl,
      minLength: 2,
      select: function( event, ui ) {
		$("input[name=yezhu_name]").val(ui.item.name);
		$("input[name=yezhu_id]").val(ui.item.id);
        //log( "Selected: " + ui.item.value + " aka " + ui.item.id );
      }
    });
    
});