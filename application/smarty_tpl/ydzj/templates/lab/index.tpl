{include file="common/my_header.tpl"}
  {config_load file="lab.conf"}
  	
  	<div class="w-tixing clearfix"><b>温馨提醒：</b>
        <p>1、【修改操作】鼠标双击实验室名称进入修改页面。</p>
        <p>2、【删除操作】鼠标左键拖曳实验室名称至右侧空白区域进行删除.</p>
        <p>3、【改变父级】鼠标拖曳实验室名称到某一实验室下。</p>
        <p>4、【排序调整】鼠标拖曳实验室名称至其父级节点下，则当前实验室则显示在最前面， </p>
      </div>
  	
      <div id="catelist" style="max-width:750px;">
        <div class="rounded_box">
          <div id="treeboxbox_tree1"></div>
          <div id="loading_img" class="loading_div" style="display:none;"></div>
        </div>
      </div>
      <div id="dialog-confirm" title="删除{#title#}" style="display:none;"><p><span class="ui-icon ui-icon-alert" style="float:left;"></span>确定要移除<span class="categoryName hightlight"></span>吗?</p></div>
	 {include file="common/dhtml_tree.tpl"}
	 {include file="common/jquery_ui.tpl"}
	
      <script>
      	var labeEditUrl = "{site_url('lab/edit?id=')}";
      
        var tree=new dhtmlXTreeObject("treeboxbox_tree1","100%","100%",0);
        tree.setImagePath("{$smarty.const.TREE_IMG_PATH}");
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
            location.href= "{site_url('lab/edit?id=')}" + id + "&t=" + Math.random();
            //console.log(id);
            //console.log(tree.getItemText(id)+" was selected");
        }
        
        function toncheck(id,state){
        	
        }
        
        var successHandler = function(json){
        	alert(json.message);
        	
        	if(json.message.indexOf('成功') != -1){
        		location.href = "{site_url('lab/index')}";
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
                          url:"{site_url('lab/delete')}",
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
	                      url:"{site_url('lab/move')}",
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
        
        tree.setOnLoadingStart(treeLoading);
        tree.setOnLoadingEnd(treeLoaded); 
        
        tree.setOnClickHandler(tonclick);
        tree.setOnDblClickHandler(tondblclick);
        //tree.setOnCheckHandler(toncheck);
        tree.setDragHandler(tondrag);
        
        
        setTimeout(function(){
        	tree.loadXML("{site_url('lab/getTreeXML?uid='|cat:$profile['basic']['id'])}",function(){
            tree.closeAllItems();
	            {include file="./tree_unexpand.tpl"}
	        });
        
        },500);
        
        
        $(function(){
            $.loadingbar({ container: "#catelist" ,templateData:{ message:"努力加载中..."} });
        });
       </script>
{include file="common/my_footer.tpl"}