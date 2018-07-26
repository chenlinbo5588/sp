$(function(){
	$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
	bindAjaxSubmit("#infoform");
	
	$( "#resident_name" ).autocomplete({
      source: searchUrl,
      minLength: 2,
      select: function( event, ui ) {
    	  
      }
    });
	
});
