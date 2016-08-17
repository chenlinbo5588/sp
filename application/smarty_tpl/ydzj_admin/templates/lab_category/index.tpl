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
	{include file="common/dhtml_tree.tpl"}
    <script>
	    var tree=new dhtmlXTreeObject("treeboxbox_tree1","100%","100%",0);
	    tree.setImagePath("/static/js/dhtmlxTree_v413_std/skins/web/imgs/dhxtree_web/");
	    tree.enableHighlighting(true);
	    tree.enableDragAndDrop(1);
	    tree.enableSmartXMLParsing(true);
	    
	    function tonclick(id){
	    }
	    
	    function tondblclick(id){
	        if(id == 'root'){
	            return;
	        }
	        location.href= "{admin_site_url('lab_category/edit?id=')}" + id + "&t" + Math.random();
	    }
	    
	    function toncheck(id,state){
	    
	    }
	    
	    function tondrag(id,id2){
	        //console.log(id2);
	        if(0 == id2){
	            var submit = function (v, h, f) {
				    if (v == 'ok'){
				         $.ajax({
				              type:"POST",
				              url:"{base_url('lab_category/delete')}",
				              data: { id: id },
				              success:function(data){
				                  //console.log(data);
				                  location.reload();
				              }
				         });
				    }
				
				    return true;
				};
				
				$.jBox.confirm("确定删除 " + tree.getItemText(id) + " 吗？", "提示", submit);
				
	            //var flag = confirm("确定要删除 "+tree.getItemText(id)+" to item "+tree.getItemText(id2)+"?");
	            
	        }else{
	        
	            var submit2 = function (v, h, f) {
	                if (v == 'ok'){
	                     $.ajax({
	                          type:"POST",
	                          url:"{base_url('lab_category/edit')}",
	                          data: { id: id , name : tree.getItemText(id) , pid: id2 },
	                          success:function(data){
	                              location.reload();
	                          }
	                     });
	                }
	            
	                return true;
	            };
	            
	            $.jBox.confirm("确定移动 " + tree.getItemText(id) + " 到 " + tree.getItemText(id2) + " 吗？", "提示", submit2);
	        
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
	    tree.setOnDblClickHandler(tondblclick);
	    tree.setDragHandler(tondrag);
	    tree.loadXML("{admin_site_url('lab_category/getTreeXML/')}",function(){
	    	
	    });
	    
	    $(function(){
	        $.loadingbar({ templateData:{ message:"努力加载中..."} });
	    });
    </script>
{include file="common/main_footer.tpl"}