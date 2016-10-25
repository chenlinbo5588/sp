/**
 * 添加实验室
 */
$(function(){
	tree=new dhtmlXTreeObject("treeboxbox_tree1","100%","100%",0);
    tree.setImagePath(treeImgUrl);
    
    function tonclick(id){
        var isFind = false;
        {if $profile['basic']['id'] != $smarty.const.LAB_FOUNDER_ID}
            {* 勾选是否在自己管理的实验室 *}
            for(var i = 0 ; i < user_labs.length ; i++ )
            {
                if(id != 0 && user_labs[i] == id){
                   isFind = true;
                }
            }
        {else}
           isFind = true;
        {/if}
        
        {* 没有修改父级 不报错 *}
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
        	location.href= "{site_url('lab/edit?id=')}" + id + "&" + Math.random();
        }
    }
    
    tree.setOnDblClickHandler(tondblclick);
    tree.setOnLoadingStart(treeLoading);
    tree.setOnLoadingEnd(treeLoaded); 
    
    
    setTimeout(function(){
	    tree.loadXML("{site_url('lab/getTreeXML')}",function(){
	        var parent = 0;
	        {if empty($info['id'])}
	        {if $info['pid']}tree.selectItem({$info['pid']});{/if}
	        {elseif $info['id']}
	        tree.selectItem({$info['id']});
	        {/if}
	        
	        {include file="./tree_unexpand.tpl"}
	        
	        // 用户管理的实验室
	        for(var i = 0 ; i < user_labs.length ; i++ )
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
	        
	    });
	 },500);
    
    
    {* 成员管理 *}
    {if $info['id']}
    function ajaxPage(page){
        //$("#user_loading").show();
        $.ajax({
            type:"GET",
            cache:false,
            url:"{site_url('lab_user/search')}",
              data: { id: {$info['id']} ,page: page , username: $("#search_username").val()},
              success:function(data){
              	$("#userlist").html(data);
              	dialog.dialog( "open" );
              }
        });
    }
    {/if}
    
    
    var successHandler = function(json){
    	alert(json.message);
      	if(json.message == '操作成功'){
      		location.href= "{site_url('lab/edit?id=')}" + id + "&" + Math.random();
      	}
    };
    
    
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
                url:"{site_url('lab/manager_lab_user')}",
                dataType:"json",
                data: "id={$info['id']}&" + $("form[name=memberForm]").serialize(),
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
	                    url:"{site_url('lab/delete_lab_user')}",
	                    dataType:"json",
	                    data: { id: {$info['id']} , user_id: user_id},
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