;$(function(){
	
	bindDeleteEvent();
	
    $( ".datepicker" ).datepicker();
    $(".repub").bind("click",function(){
        var selected = $("input[name='id[]']:checked").size();
        if(selected == 0){
            showToast('error', '请先勾选');
            return ;
        }
        
        var hpids = getcookie('repub');
        var ids = [];
        
        if(hpids){
            ids = hpids.split('|');
        }
        
        if(ids.length > 50){
        	showToast('warning', '最多可待重发50个');
        	return;
        }
        
        $("input[name='id[]']:checked").each(function(){
            var currrentId = $(this).val();
            var hasExists = false;
            
            for(var i = 0; i < ids.length; i++){
                if(currrentId == ids[i]){
                    hasExists = true;
                }
            }
            
            if(!hasExists){
                ids.push(currrentId);
            }
        });
        
        if(ids.length){
        	$("#repub .title em").html(ids.length);
        	for(var i = 0; i < 2; i++){
        		$("#repub").show().animate({ right: "-10px" }, 100).animate({ right: "10px" }, 100);
        	}
        }
        
        setcookie('repub',ids.join('|'),86400);
    });
});