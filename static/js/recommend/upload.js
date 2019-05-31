function bindOpEvent1(pSetting,customDataFn, customSuccessFn,customErrorFn){
    	var defaultSetting = { selector: '.notexists',forceChecked: true};
    	var setting = $.extend(defaultSetting,pSetting);
    	
    	var successCallback = function(ids,json){
    		if(check_success(json.message)){
    			showToast('success',json.message);
    			
    			if(typeof(ids) != 'undefined'){
    				for(var i = 0; i < ids.length; i++){
    					$("#row" + ids[i]).remove();
    				}
    			}
    		}else{
    			showToast('error',json.message);
    		}
    	}
    	
    	var errorCallback = function(){
    		showToast('error',"执行出错,服务器异常，请稍后再次尝试");
    	}
    	
    	
    	$(setting.selector).bind("click",function(){
    		var triggerObj = $(this);
    		var url = triggerObj.attr("data-url");
      		var title = triggerObj.attr('data-title');
      		var ids = triggerObj.attr('ids');
      		
    		ui_confirm({
    			'trigger':triggerObj,
    			'customDataFn': customDataFn,
    			'postData': { id : ids },
    			'url': url,
    			'titleHTML': title,
    			'dataType' : 'json',
    			'successFn': customSuccessFn ? customSuccessFn : successCallback,
    			'errorFn'  : customErrorFn ? customErrorFn : errorCallback
    		});
      	});
    	
    }


$(function(){

    
	var successFn = function(ids,resp){
    	if(check_success(resp.message)){
    		showToast('success',resp.message);
    		
    		if(typeof(resp.data.redirectUrl) != 'undefined'){
				setTimeout(function(){
					location.href = resp.data.redirectUrl;
				},resp.data.wait ? resp.data.wait : 1000);
			}else{
				setTimeout(function(){
					location.reload();
				},2000);
			}
		}else{
			showToast('error',resp.message);
		}
	};
	
	var failedFn = function(xhr){
		showToast('服务器错误');
	};
	
    //提交审核
    bindOpEvent1({ selector : ".opBtn" }, { } , successFn);
    
    
    
    
});