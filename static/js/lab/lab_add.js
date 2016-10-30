/**
 * 添加实验室
 */
$(function(){
	tree=new dhtmlXTreeObject("treeboxbox_tree1","100%","100%",0);
    tree.setImagePath(treeImgUrl);
    
    
    function tonclick(id){
        var isFind = false;
        
        //不是自己的不给选
        if(!isFounder){
            for(var i = 0 ; i < user_labs.length ; i++ )
            {
                if(id != 0 && user_labs[i] == id){
                   isFind = true;
                }
            }
        }else {
        	isFind = true;
        }
        
        if(isFind == false && id == current_pid){
            isFind = true;
        }
        
        if(isFind == false){
            $("#pid_tip").html( tree.getItemText(id) + " 不在属于您管辖范围，不能指定");
            $("input[name=pid]").val("");
            return ;
        }else{
            $("#pid_tip").html("");
        }
        
        if(id == 'root'){
            $("input[name=pid]").val(0);
        }else{
            $("input[name=pid]").val(id);
        }
        
    };
    tree.enableHighlighting(true);
    tree.enableSmartXMLParsing(true);
    
    tree.setOnClickHandler(tonclick);
    
    function treeLoading(){
        $("#loading_img").show();
    }
    
    function treeLoaded(){
        $("#loading_img").hide();
    }
    
    function tondblclick(id){
        if(id == 'root'){
            return;
        }else{
        	location.href= labEditUrl + "?id=" + id + "&" + Math.random();
        }
    }
    
    tree.setOnDblClickHandler(tondblclick);
    tree.setOnLoadingStart(treeLoading);
    tree.setOnLoadingEnd(treeLoaded); 
    
    
    setTimeout(function(){
	    tree.loadXML(treeXMLUrl,function(){
	        var parent = 0,i = 0;
	        
	        if(current_id){
	        	//修改页
	        	tree.selectItem(current_id);
	        }else{
	        	if(current_pid){
	        		tree.selectItem(current_pid);
	        	}
	        }
	        
	        treeExpand(tree);
	        
	        // 用户管理的实验室
	        for(i = 0 ; i < user_labs.length ; i++ )
	        {
	            if(user_labs[i] != 0){
	                parent = tree.getParentId(user_labs[i]);
	                if(parent != 0){
	                    tree.openItem(parent);
	                }
	                
	                tree.setItemColor(user_labs[i], "blue", "#EC1336");
	                //tree.setItemText(user_labs[i], "【我的】" + tree.getItemText(user_labs[i]), "您的实验室:" + tree.getItemText(user_labs[i]));
	            }
	        }
	        
	        for(i = 0; i <  manager_labs.length; i++){
	            tree.setItemColor(manager_labs[i], "blue", "#EC1336");
	        }
	    });
	 },500);
    
    var successHandler = function(json){
      	if(json.message == '操作成功'){
      		showToast('success',json.message);
    		setTimeout(function(){
    			location.href= labEditUrl + id + "&" + Math.random();
    		},1000);
      	}else{
      		showToast('error',json.message);
      	}
    };
    
    function searchUser(page){
        //$("#user_loading").show();
        $.ajax({
            type:"get",
            cache:false,
            url:labManagerUrl,
              data: { id: current_id ,page: page,username: $("#search_username").val()},
              success:function(data){
              	$("#userlist").html(data);
              	//dialog.dialog( "open" );
              }
        });
    }
    
    
    
    bindAjaxSubmit('form');
    bindDeleteEvent();
    /*
	dialog = $( "#labMemberDlg" ).dialog({
      autoOpen: false,
      height: 'auto',
      width: 500,
      modal: true,
      buttons: [
      	{
	      text: "确定",
	      icons: {
	        primary: "ui-icon-heart"
	      },
	      click: function() {
	      
	      	if(!confirm("确定要进行管理操作吗？")){
	      		return;
	      	}
	      	
	      	$.ajax({
                type:"POST",
                url:labManagerUrl,
                dataType:"json",
                data: "id=" + current_id + "&" + $("form[name=memberForm]").serialize(),
                success:successHandler
           });
	      }
	    },
	    {
	      text: "取消",
	      icons: {
	        primary: "ui-icon-heart"
	      },
	      click: function() {
	         $( this ).dialog( "close" );
	      }
	    }
	  ],
      close: function() { }
	});
	
	  $("body").delegate("#search_btn","click",function(){
	      searchUser(1);
	  });
	  
	  
	  $("#addUserBtn").bind("click",function(){
	  	$.ajax({
	  		type:"GET",
	  		url:labManagerUrl + "?id=" + current_id,
	  		success:function(resp){
	  			$("#userlist").html(resp);
	  			dialog.dialog( "open" );
	  		}
	  	});
	  });
	  */
	  $.loadingbar({ urls: [ new RegExp($("form[name=labForm]").attr('action')) ], templateData:{ message:"努力加载中..." } ,container: "#handleDiv" });
	  
	  //$.loadingbar({ urls: [ new RegExp(labManagerUrl) ], templateData:{ message:"努力加载中..." } , container: "#labMemberDlg" });
	  
});