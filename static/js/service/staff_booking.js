$(function(){
	$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
	bindAjaxSubmit("#infoform");
		
		
	$( "#staff_mobile" ).autocomplete({
      source: searchMobileUrl,
      minLength: 2,
      select: function( event, ui ) {
		$("input[name=staff_name]").val(ui.item.name);
		$("input[name=staff_sex]").val(ui.item.sex);
        //log( "Selected: " + ui.item.value + " aka " + ui.item.id );
      }
    });
});