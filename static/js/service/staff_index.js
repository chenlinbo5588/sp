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
	
	
	//需要弹窗的按钮事件
    var popWindowFn = function(btnObj,options,data){
    	var actionUrl = btnObj.attr('data-url');
    	
    	var tempDlg = $(options.selector ).dialog({
            autoOpen: false,
            width: options.width,
            height: options.height,
            modal: true,
            title:options.title,
            open:function(){
            	
            }
        });
    	
    	//增加随机值
    	data['rand'] = Math.random();
    	
    	$.get(actionUrl, data, function(resp){
    		
    		tempDlg.html(resp).dialog({ position:btnObj ? { my : "center", at:"center", of: btnObj} : null }).dialog('open');
    		//$.loadingbar({ urls: options.urls ,text:"操作中,请稍后..." ,container : '#sendorDlg' });
    		bindAjaxSubmit(options.formid);
		});
    }
    
    //删除
    bindDeleteEvent();
    
    //提交审核
    bindOpEvent({ selector : ".handleVerifyBtn" }, { } , successFn);
    
    $(".verifyBtn").bind('click',function(){
    	var ids = getIDS($(this));
    	
    	if(ids.length == 0){
  			showToast('error','请选勾选.');
  		}else{
  			popWindowFn($(this),{ selector : "#verifyDlg", width:500, height: 'auto',title : $(this).attr('data-title'),formid: $(this).attr('data-ajaxformid'), urls : [] }, {  opName: '审核', event_id: 0, id: ids } );
  		}
    	
    });
    
});