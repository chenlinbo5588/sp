/**
 * 个人中心
 */
$(function(){
	$.loadingbar({ container: "#catelist" ,templateData:{ message:"努力加载中..."} });
	var tree=new dhtmlXTreeObject("treeboxbox_tree1","100%","100%",0);
    tree.setImagePath(treeImgUrl);
    tree.enableHighlighting(true);
    tree.enableDragAndDrop(1);
    tree.enableSmartXMLParsing(true);
    
    function tonclick(id){
        //console.log(id);
    }
    
    function tondblclick(id){
        if(id == 'root'){
            return;
        }
        location.href= labeEditUrl + id + "&t=" + Math.random();
        //console.log(id);
        //console.log(tree.getItemText(id)+" was selected");
    }
    
    function toncheck(id,state){
    	
    }
    
    var successHandler = function(json){
    	alert(json.message);
    	
    	if(json.message.indexOf('成功') != -1){
    		location.reload();
    	}
    	
    };
    
    
    
    function tondrag(id,id2){
        //console.log(id2);
        if(0 == id2){
        	$( "#dialog-confirm .categoryName" ).html(tree.getItemText(id));
        	
        	$( "#dialog-confirm" ).dialog({
		      resizable: false,
		      height: "auto",
		      width: 400,
		      modal: true,
		      buttons: {
		        "确定": function() {
		          	$.ajax({
                      type:"POST",
                      url:labDeleteUrl,
                      dataType:"json",
                      data: { id: id },
                      success:successHandler,
                      error:function(XMLHttpRequest, textStatus, errorThrown){ }
                  	});
                  	
                  	$( this ).dialog( "close" );
		        },
		        "取消": function() {
		          $( this ).dialog( "close" );
		        }
		      }
		    });
        	
        }else{
            if(confirm("确定调整节点 " + tree.getItemText(id) + " 和 " + tree.getItemText(id2) + " 吗？")){
            	$.ajax({
                      type:"POST",
                      dataType:"json",
                      url:labMoveUrl,
                      data: { id: id , pid: id2 },
                      success:successHandler
                 });
            }
            
            return false;
        }
    }
    
    function treeLoading(){
        $("#loading_img").show();
    }
    
    function treeLoaded(){
        $("#loading_img").hide();
    }
    
    
    
    /**
     * 折叠实验室, 只展开到二级
     */
    function treeUnExpand(ptree){
    	
    	var parents = ptree.getAllItemsWithKids();
        var level = 0;
        for(var i = 0 ; i < ptree.length; i++)
        {
            level = ptree.getLevel(parents[i]);
            switch(level)
            {
                case 1:
                    break;
                case 2:
                	ptree.openItem(parents[i]);
                    break;
                default:
                	ptree.closeItem(parents[i]);
                    break;
            }
        }
    }
    
    
    
    tree.setOnLoadingStart(treeLoading);
    tree.setOnLoadingEnd(treeLoaded); 
    
    tree.setOnClickHandler(tonclick);
    tree.setOnDblClickHandler(tondblclick);
    //tree.setOnCheckHandler(toncheck);
    tree.setDragHandler(tondrag);
    
    
    setTimeout(function(){
    	tree.loadXML(treeXMLUrl,function(){
        	tree.closeAllItems();
            
        });
    
    },500);
    
});