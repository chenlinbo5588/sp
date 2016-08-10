{include file="common/lab_admin_header.tpl"}
    <div class="submenu">
        <ul>
            <li><a href="javascript:void(0)" class="selected">无</a></li>
        </ul>
        <div class="clear"></div>
    </div>
    {include file="common/dhtml_tree.tpl"}
    
    <div class="center_content"> 
	    <div id="right_wrap">
		    <div id="right_content">
		      <div id="catelist" style="max-width:800px;">
		      {*
		          <form name="categoryForm" method="get" action="{base_url('lab_category/index')}">
	               <div class="form">
	                    <div class="clearfix">
	                      <label>类别名称:</label>
	                      <input type="text" class="form_input" name="name" value="{$smarty.get.name}" placeholder="请输入类别名称" />
	                      <input type="submit" class="form_submit" value="查询" />
	                    </div>
	                </div>
                </form>
		          *}
		        <h2>类别树</h2>
		        <div class="rounded_box">
		          <div class="tip">【修改操作】鼠标双击分类项进入修改页面</div>
		          <div class="tip">【删除操作】鼠标左键拖曳分类项至右侧空白区域进行删除</div>
		          <div class="tip">【更改父级】鼠标拖曳分类到某一分类下</div>
		          <br/>
		          <div id="treeboxbox_tree1"></div>
		          <div id="loading_img" class="loading_div" style="display:none;"></div>
		        </div>
		        
		        
		        <script>
		        var tree=new dhtmlXTreeObject("treeboxbox_tree1","100%","100%",0);
                tree.setImagePath("js/dhtmlxTree_v413_std/skins/web/imgs/dhxtree_web/");
                tree.enableHighlighting(true);
                tree.enableDragAndDrop(1);
                tree.enableSmartXMLParsing(true);
                

                //tree.loadXML("{base_url('js/dhtmlxTree_v413_std/samples/dhtmlxTree/common/tree.xml')}");
                
                function tonclick(id){
                    
                }
                
                function tondblclick(id){
                    if(id == 'root'){
                        return
                    }
                    location.href= "{base_url('lab_category/edit/id/')}" + id + "?" + Math.random();
                    //console.log(id);
                    //console.log(tree.getItemText(id)+" was selected");
	            
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
                
                
                //tree.setOnClickHandler(tonclick);
                tree.setOnDblClickHandler(tondblclick);
                //tree.setOnCheckHandler(toncheck);
                tree.setDragHandler(tondrag);
                tree.loadXML("{base_url('lab_category/getTreeXML/')}",function(){
                });
                
                $(function(){
                    $.loadingbar({ templateData:{ message:"努力加载中..."} });
                });
		        </script>
		        
		        {*
			    <h2>类别列表</h2> 
				<table class="rounded-corner">
				    <colgroup>
				        <col style="width:10%"/>
				        <col style="width:30%"/>
				        <col style="width:10%"/>
				        <col style="width:10%"/>
				        <col style="width:10%"/>
				    </colgroup>
				    <thead>
				        <tr>
				            <th>序号</th>
				            <th>类别名称</th>
				            <th>录入时间</th>
				            <th>录入</th>
				            <th>编辑</th>
				            <th>删除</th>
				        </tr>
				    </thead>
				    <tbody>
				        {foreach from=$data['data'] key=key item=item}
                        <tr id="row_{$item['id']}" {if $key % 2 == 0}class="odd"{else}class="even"{/if}>
                            <td>{$item['id']}</td>
                            <td>{$item['name']|escape}</td>
                            <td>{$item['gmt_create']|date_format:"Y-m-d"}</td>
                            <td>{$item['creator']|escape}</td>
                            <td><a href="{base_url('lab_category/edit/id/')}{$item['id']}">编辑</a></td>
                            <td><a class="delete" href="javascript:void(0);" data-href="{base_url('lab_category/delete/id/')}{$item['id']}" data-title="当前类别的子类将同时被删除 ,确定删除{$item['name']|escape}吗?">删除</a></td>
                        </tr>
                        {/foreach}  
				    </tbody>
				    <tfoot>
				        <tr>
				            <td colspan="6">{include file="pagination.tpl"}</td>
				        </tr>
				    </tfoot>
				</table>
				*}
			  </div>
			 
		    </div><!-- end of right content-->
		</div><!-- end of right wrap -->
		{include file="./side.tpl"}           
{include file="common/lab_admin_footer.tpl"}