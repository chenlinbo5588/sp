/**
 * 添加成员
 */
;$(function(){
	tree=new dhtmlXTreeObject("treeboxbox_tree1","100%","100%",0);
    tree.setImagePath(treeImgUrl);
    
	tree.enableHighlighting(true);
	tree.enableSmartXMLParsing(true);
	tree.enableCheckBoxes(true, true);
	//tree.enableThreeStateCheckboxes(true);
	tree.enableSmartCheckboxes(true);
	
	function treeLoading(){
	    $("#loading_img").show();
	}
	
	function treeLoaded(){
	    $("#loading_img").hide();
	}
	
	function toncheck(id,state){
	    tree.setSubChecked(id, state);
	    setLabIds();
	};
	
	function tondblclick(id){
	    if(id == 'root'){
	        return;
	    }
	    
	    location.href= labEditUrl + "?id=" + id + "&t=" + Math.random();
	}
	
	tree.setOnCheckHandler(toncheck);
	tree.setOnLoadingStart(treeLoading);
	tree.setOnLoadingEnd(treeLoaded); 
	tree.setOnDblClickHandler(tondblclick);
	
	
	setTimeout(function(){
		tree.loadXML(treeXMLUrl,function(){
		    var parent = 0,i = 0;
		    var list = tree.getAllUnchecked(); 
		    var ids = list.split(',');
		    
		    if(!isFounder){
			    for(i = 0; i < ids.length; i++)
			    {
			        if(ids[i] != 0){
			            tree.setItemColor(ids[i], "#BDBDBD", "#FF0000");
			            tree.disableCheckbox(ids[i],true);
			        }
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
	                if(current_id){
	                	tree.setCheck(user_labs[i], true);
	                }
	            }
	            
	            //用户所在的实验室勾选
	            for(i = 0; i <  edit_user_labs.length; i++){
	            	tree.disableCheckbox(edit_user_labs[i],false);
		            tree.setCheck(edit_user_labs[i], true);
	            }
	        }
	        
		});
	},500);
	
	function setLabIds(){
	    var allchecked = tree.getAllChecked();
	    var allPartiallyChecked = tree.getAllPartiallyChecked();
	    
	    if(allchecked && allPartiallyChecked){
	        $("input[name=lab_id]").val(allchecked + "," + allPartiallyChecked);
	    }else if(allchecked){
	        $("input[name=lab_id]").val(allchecked);
	    }else if(allPartiallyChecked){
	        $("input[name=lab_id]").val(allPartiallyChecked);
	    }
	}
});