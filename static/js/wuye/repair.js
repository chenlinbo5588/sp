$(function(){
	$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
	bindAjaxSubmit("#infoform");
		
		
	$( "#address" ).autocomplete({
      source: searchAddressUrl,
      minLength: 2,
      select: function( event, ui ) {
		$("input[name=yezhu_name]").val(ui.item.name);
		$("input[name=mobile]").val(ui.item.mobile);
        //log( "Selected: " + ui.item.value + " aka " + ui.item.id );
      }
    });
});