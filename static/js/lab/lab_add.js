/**
 * 添加实验室
 */
$(function(){
	tree=new dhtmlXTreeObject("treeboxbox_tree1","100%","100%",0);
    tree.setImagePath(treeImgUrl);
    
    function tonclick(id){
        var isFind = false;
        
        if(isFounder){
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
        	location.href= labEditUrl + id + "&" + Math.random();
        }
    }
    
    tree.setOnDblClickHandler(tondblclick);
    tree.setOnLoadingStart(treeLoading);
    tree.setOnLoadingEnd(treeLoaded); 
    
    
    setTimeout(function(){
	    tree.loadXML(treeXMLUrl,function(){
	        var parent = 0;
	        
	        
	        if(current_id){
	        	tree.selectItem(current_id);
	        }else{
	        	if(current_pid){
	        		tree.selectItem(current_pid);
	        	}
	        }
	        
	        var parents = tree.getAllItemsWithKids();
            var level = 0;
            for(var i = 0 ; i < parents.length; i++)
            {
                level = tree.getLevel(parents[i]);
                switch(level)
                {
                    case 1:
                        break;
                    case 2:
                        tree.openItem(parents[i]);
                        break;
                    default:
                        tree.closeItem(parents[i]);
                        break;
                }
            }
	        
	        // 用户管理的实验室
	        for(var i = 0 ; i < user_labs.length ; i++ )
	        {
	            if(user_labs[i] != 0){
	                parent = tree.getParentId(user_labs[i]['id']);
	                if(parent != 0){
	                    tree.openItem(parent);
	                }
	                tree.setItemColor(user_labs[i]['id'], "blue", "#EC1336");
	                //tree.setItemText(user_labs[i], "【我的】" + tree.getItemText(user_labs[i]), "您的实验室:" + tree.getItemText(user_labs[i]));
	            }
	        }
	        
	    });
	 },500);
    
    var successHandler = function(json){
    	alert(json.message);
      	if(json.message == '操作成功'){
      		location.href= labEditUrl + id + "&" + Math.random();
      	}
    };
    
    
    bindAjaxSubmit('form');
    
	dialog = $( "#labMemberDlg" ).dialog({
      autoOpen: false,
      height: 'auto',
      width: 800,
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
	      ajaxPage(1);
	  });
	  
	  $("#addUserBtn").bind("click",function(){
	  	$.ajax({
	  		type:"GET",
	  		url:"{site_url('lab_user/search?id=')}{$info['id']}&t=" + Math.random(),
	  		success:function(resp){
	  			$("#userlist").html(resp);
	  			dialog.dialog( "open" );
	  		}
	  	});
	  });
	  
	  $.loadingbar({ urls: [ new RegExp("{site_url('lab/manager_lab_user') }"),new RegExp("{site_url('lab_user/search') }") ], templateData:{ message:"努力加载中..." } , container: "#labMemberDlg" });
	  
	  $(".close").bind("click",function(){
	  	var user_id = $(this).attr("data-id");
	      var user_name = $(this).attr("data-title");
	      var alink = $(this);
	      
	      $( "#dialog-confirm .memberName" ).html(user_name);
	      
	  	$( "#dialog-confirm" ).dialog({
		      resizable: false,
		      height: "auto",
		      width: 400,
		      modal: true,
		      position: { my: "left bottom", at: "left bottom", of: alink },
		      buttons: {
		        "确定": function() {
		          $.ajax({
	                    type:"POST",
	                    url:labUserDeleteUrl,
	                    dataType:"json",
	                    data: { id: current_id , user_id: user_id},
	                    success:function(json){
	                    	 alert(json.message);
	                    	 if(json.message == '删除成功'){
	                    	 	$("#row_" + user_id).fadeOut();
	                    	 }
	                    },
	                    error:function(XMLHttpRequest, textStatus, errorThrown){
	                    }
	               });
	               
		          $( this ).dialog( "close" );
		        },
		        "取消": function() {
		          $( this ).dialog( "close" );
		        }
		      }
		    });
	  });
    
});