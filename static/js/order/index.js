$(function(){
	//$('.fixedBar').fixedBar({ css : 'position:fixed;top:60px;background:#fff;'});
	
	
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
	
	
    //删除
    bindDeleteEvent();

    
    //功能确认对话框
    bindOpEvent({ selector : ".opBtn" }, { } , successFn);
    
    bindOpEvent( { selector : ".ggBtn", forceChecked : false });
    
    $(".refundLink").bind('click',function(){
    	var ids = getIDS($(this));
  		popWindowFn($(this),{ selector : "#verifyDlg", width:500, height: 'auto',title : $(this).attr('data-title'),formid: $(this).attr('data-ajaxformid'), urls : [] }, {  opName: '退款', event_id: 0, id: ids } );
    });
    
    
   
    
    $(".verifyBtn").bind('click',function(){
    	var ids = getIDS($(this));
    	
    	if(ids.length == 0){
  			showToast('error','请选勾选.');
  		}else{
  			popWindowFn($(this),{ selector : "#verifyDlg", width:500, height: 'auto',title : $(this).attr('data-title'),formid: $(this).attr('data-ajaxformid'), urls : [] }, {  opName: '审核', event_id: 0, id: ids } );
  		}
    	
    });
  

    
});