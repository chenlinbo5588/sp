{include file="common/main_header.tpl"}
{config_load file="goods.conf"}
    {include file="./sub_nav.tpl"}
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
	              <li class="tip">【修改操作】鼠标双击{#category_title#}名称进入修改页面</li>
		          <li class="tip">【删除操作】鼠标左键拖曳{#category_title#}名称至右侧空白区域进行删除</li>
		          <li class="tip">【改变父级】鼠标拖曳{#category_title#}名称到某一{#category_title#}下</li>
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
	  
	<div id="dialog-confirm" title="删除{#category_title#}" style="display:none;"><p><span class="ui-icon ui-icon-alert" style="float:left;"></span>确定删除<span class="categoryName hightlight"></span>吗?</p></div>
	<div id="dialog-confirm2" title="删除{#category_title#}" style="display:none;"><p><span class="ui-icon ui-icon-alert" style="float:left;"></span>确定移动<span class="categoryName hightlight"></span>吗?</p></div>
	
	{include file="common/dhtml_tree.tpl"}
	{include file="common/jquery_ui.tpl"}
    <script>
	    var tree=new dhtmlXTreeObject("treeboxbox_tree1","100%","100%",0);
	    tree.setImagePath("/static/js/dhtmlxTree_v413_std/skins/web/imgs/dhxtree_web/");
	    tree.enableHighlighting(true);
	    tree.enableDragAndDrop(1);
	    tree.enableSmartXMLParsing(true);
	    
	    var dialog = null;
	    var successHandler = function(json){
        	alert(json.message);
        	
        	if(json.message.indexOf('成功') != -1){
        		location.href = "{admin_site_url('goods_category/index')}";
        	}
        	
        };
        
	    function tonclick(id){
	    }
	    
	    function tondblclick(id){
	        if(id == 'root'){
	            return;
	        }
	        location.href= "{admin_site_url('goods_category/edit?id=')}" + id + "&t" + Math.random();
	    }
	    
	    function tondrag(id,id2){
	    	
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
	                          url:"{admin_site_url('goods_category/delete')}",
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
	        	
	        	$( "#dialog-confirm2 .categoryName" ).html(tree.getItemText(id) + "到" + tree.getItemText(id2) + "下");
	        	
	        	$( "#dialog-confirm2" ).dialog({
	        	  title:'移动{#category_title#}',
			      resizable: false,
			      height: "auto",
			      width: 400,
			      modal: true,
			      buttons: {
			        "确定": function() {
			          	$.ajax({
	                          type:"POST",
	                          url:"{admin_site_url('goods_category/edit')}",
	                          dataType:"json",
	                          data: { id: id , name : tree.getItemText(id) , pid: id2 },
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
	    tree.setOnDblClickHandler(tondblclick);
	    tree.setDragHandler(tondrag);
	    tree.loadXML("{admin_site_url('goods_category/getTreeXML/')}",function(){
	    	
	    });
	    
	    $(function(){
	        $.loadingbar({ templateData:{ message:"努力加载中..."} , container: "#catelist"});
	    });
    </script>
{include file="common/main_footer.tpl"}