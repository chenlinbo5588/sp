$(function(){
	
	bindDeleteEvent({ },function(ids,json){
		
		if(check_success(json.message)){
			showToast('success',json.message);
			for(var i = 0; i < ids.length; i++){
	            $("#row" + ids[i]).remove();
	            $(".row" + ids[i]).remove();
	        }
			
		}else{
			showToast('error',json.message);
		}
		
        refreshFormHash(json.data);
    },function(){
        showToast('error','删除错误');
    });
	
  	$(configUrls.targetId).delegate("img[nc_type='flex']", "click",function(){
  		var obj = $(this);
		var status = obj.attr('status');
		
		
		if(status == 'open'){
			var pr = obj.parent('td').parent('tr');
			var id = obj.attr('fieldid');
			obj.attr('status','close');
		
			//ajax
			$.ajax({
				url: configUrls.dataUrl + '?pid='+id,
				success: function(html){
					if($.trim(html) != ''){
						$("span.editable").unbind( "click" );
						$(".yes-onoff a").unbind('click');
						
						$(html).insertAfter(pr);
						
						bindFn();
						bindOnOffEvent();
					}else{
						showToast('warning','已无更多数据' ,{ hideAfter : 2000 } );
					}
					
					obj.attr('status','close');
					obj.attr('src',obj.attr('src').replace("tv-expandable","tv-collapsable"));
				},
				error: function(){
					alert('获取信息失败');
				}
			});
		} else if(status == 'close'){
			$(".row"+obj.attr('fieldid')).remove();
			obj.attr('src',obj.attr('src').replace("tv-collapsable","tv-expandable"));
			obj.attr('status','open');
		}
	});
    
    var bindFn = function(){
    	$("span.editable").inline_edit({ 
	    	url: configUrls.inlineUrl,
	    	clickNameSpace:'inlineEdit'
	    });
    }
    
    bindFn();
    bindOnOffEvent();
});