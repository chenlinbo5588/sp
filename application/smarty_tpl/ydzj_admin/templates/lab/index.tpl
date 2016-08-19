{include file="common/main_header.tpl"}
{config_load file="lab.conf"}
  {include file="./lab_common.tpl"}
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
	<table class="autotable" id="prompt">
	    <tbody>
	      <tr class="space odd">
	        <th colspan="12"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div>
	        </th>
	      </tr>
	      <tr>
	        <td>
	        	<ul>
	              <li class="tip">【修改操作】鼠标双击实验室名称进入修改页面</li>
		          <li class="tip">【删除操作】鼠标左键拖曳实验室名称至右侧空白区域进行删除</li>
		          <li class="tip">【改变父级】鼠标拖曳实验室名称到某一实验室下</li>
		          <li class="tip">【排序调整】鼠标拖曳实验室名称至其父级节点下，则当前实验室则显示在最前面</li>
	            </ul>
	        </td>
	      </tr>
	    </tbody>
	  </table>
      <div id="catelist" style="max-width:750px;">
        <div class="rounded_box">
          <div id="treeboxbox_tree1"></div>
          <div id="loading_img" class="loading_div" style="display:none;"></div>
        </div>
      </div>
      <div id="dialog-confirm" title="删除{#title#}" style="display:none;"><p><span class="ui-icon ui-icon-alert" style="float:left;"></span>确定要移除<span class="categoryName hightlight"></span>吗?</p></div>
	 {include file="common/dhtml_tree.tpl"}
	 {include file="common/jquery_dlg.tpl"}
	
      <script>
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
            location.href= "{admin_site_url('lab/edit?id=')}" + id + "&t=" + Math.random();
            //console.log(id);
            //console.log(tree.getItemText(id)+" was selected");
        }
        
        function toncheck(id,state){
        	
        }
        
        var successHandler = function(json){
        	alert(json.message);
        	
        	if(json.message.indexOf('成功') != -1){
        		location.href = "{admin_site_url('lab/index')}";
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
                          url:"{admin_site_url('lab/delete')}",
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
	                      url:"{admin_site_url('lab/move')}",
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
        tree.loadXML("{admin_site_url('lab/getTreeXML?uid='|cat:$admin_profile['basic']['id'])}",function(){
            tree.closeAllItems();
            {include file="./tree_unexpand.tpl"}
        });
        
        $(function(){
            $.loadingbar({ container: "#catelist" ,templateData:{ message:"努力加载中..."} });
        });
       </script>
{include file="common/main_footer.tpl"}