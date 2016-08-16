{include file="common/lab_admin_header.tpl"}
    <div class="submenu">
        <div class="clear"></div>
    </div>
    {include file="common/dhtml_tree.tpl"}
    <div class="center_content"> 
	    <div id="right_wrap">
		    <div id="right_content">
		      {if $action == 'add'}
		      <form name="labForm" method="post" action="{base_url('lab_user/add')}" onsubmit="return validation(this);">
		      {elseif $action = 'edit'}
		      <form name="labForm" method="post" action="{base_url('lab_user/edit')}" onsubmit="return validation(this);">
		      <input type="hidden" name="id" value="{$info['id']}"/>
		      {/if}
		       <div class="form">
		          <div class="form_row clearfix">
                      <label class="require"><em>*</em>实验员账号:</label>
                      <input type="text" class="form_input" name="account" value="{$info['account']|escape}" {if $action == 'edit'}readonly{/if} placeholder="请输入实验员账号" />
                      {form_error('account')}
                    </div>
		            <div class="form_row clearfix">
		              <label class="require"><em>*</em>实验员名称:</label>
		              <input type="text" class="form_input" name="name" value="{$info['name']|escape}" placeholder="请输入实验员名称" />
		              {form_error('name')}
		            </div>
		            <div class="form_row clearfix">
                      <label>登录密码:</label>
                      <input type="password" class="form_input" name="psw" value="" placeholder="请输入登录密码" />
                      {form_error('psw')}
                      <span class="warning">留空默认密码 123456</span>
                    </div>
                    <div class="form_row clearfix">
                      <label>登录密码确认:</label>
                      <input type="password" class="form_input" name="psw2" value="" placeholder="请输入登录密码确认" />
                      {form_error('psw2')}
                    </div>
                    {if $info['status'] == '已删除'}
                    <div class="form_row clearfix">
                      <label>重新激活:</label>
                      <input type="checkbox" name="status" value="正常" />
                      {form_error('status')}
                    </div>
                    {/if}
                    <div class="form_row clearfix">
                      <label class="require"><em>*</em>归属实验室:</label>
                      <input type="hidden" name="lab_id" value=""/>
                      <span class="tip">鼠标双击实验室名称项进入实验室详情页</span>
                      {form_error('lab_id')}
                      <div id="treeboxbox_tree1" class="rounded_box" style="height:250px;">
                        <div id="loading_img" class="loading_div" style="display:none;"></div>
                      </div>
                    </div>
                    {if $userProfile['id'] == $smarty.const.LAB_FOUNDER_ID}
                    <div class="form_row clearfix">
                        <label>设为系统管理员:</label>
                        <input type="checkbox" name="is_manager" value="y" {if $info['is_manager'] == 'y'}checked{/if}/>
                        {form_error('is_manager')}
                    </div>
                    {/if}
		            <div class="form_row paddingleft100">
		               <input type="submit" class="form_submit" value="保存" />
		               {if $gobackUrl }<input type="hidden" name="gobackUrl" value="{$gobackUrl}"/><input type="button" class="form_submit" value="返回" onclick="location.href='{$gobackUrl}'" />{/if}
		            </div> 
		        </div>
		        </form>
		    </div><!-- end of right content-->
		</div><!-- end of right wrap -->
		<script>
            var tree=new dhtmlXTreeObject("treeboxbox_tree1","100%","100%",0);
            tree.setImagePath("/js/dhtmlxTree_v413_std/skins/web/imgs/dhxtree_web/");
            
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
            };
            
            function tondblclick(id){
                if(id == 'root'){
                    return
                }
                location.href= "{base_url('lab_admin/edit/id/')}" + id + "?" + Math.random();
            }
            
            tree.setOnCheckHandler(toncheck);
            tree.setOnLoadingStart(treeLoading);
            tree.setOnLoadingEnd(treeLoaded); 
            tree.setOnDblClickHandler(tondblclick);
            
            tree.loadXML("{base_url('lab_admin/getTreeXML')}",function(){
                var parent = 0;
                var list = tree.getAllUnchecked(); 
                var ids = list.split(',');
                
                {if $userProfile['id'] != $smarty.const.LAB_FOUNDER_ID }
                for(var i = 0; i < ids.length; i++)
                {
                    if(ids[i] != 0){
                        tree.setItemColor(ids[i], "#BDBDBD", "#FF0000");
                        tree.disableCheckbox(ids[i],true);
                    }
                }
                {/if}
                
                {include file="lab_admin/tree_unexpand.tpl"}
                {if $user_labs}
	                {foreach from=$user_labs item=item}
	                    {if $item != 0}
	                    parent = tree.getParentId({$item});
	                    if(parent != 0){
	                        tree.openItem(parent);
	                    }
	                    tree.setItemColor({$item}, "black", "blue");
	                    tree.disableCheckbox({$item},false);
	                    
	                        {if $action == 'add'}
	                        tree.setCheck({$item}, true);
	                        {/if}
	                    {/if}
	                {/foreach}
                
	                {if $edit_user_labs}
		                {foreach from=$edit_user_labs item=item}
		                tree.disableCheckbox({$item},false);
		                tree.setCheck({$item['lab_id']}, true);
		                {/foreach}
	                {/if}
                {/if}
                
            });
            
            
            function validation(form){
                if($("input[name=account]").val() == ''){
                    $.jBox.tip('请输入实验员账号', '提示');
                    $("input[name=account]").focus();
                    return false;
                }
                
                if($("input[name=name]").val() == ''){
                    $.jBox.tip('请输入实验员名称', '提示');
                    $("input[name=name]").focus();
                    return false;
                }
            
            
                var allchecked = tree.getAllChecked();
                var allPartiallyChecked = tree.getAllPartiallyChecked();
                
                if(allchecked && allPartiallyChecked){
                    $("input[name=lab_id]").val(allchecked + "," + allPartiallyChecked);
                }else if(allchecked){
                    $("input[name=lab_id]").val(allchecked);
                }else if(allPartiallyChecked){
                    $("input[name=lab_id]").val(allPartiallyChecked);
                }
                
                if($("input[name=lab_id]").val() == ''){
                    $.jBox.tip('请勾选归属实验室', '提示');
                    return false;
                }
              
                return true;
            }
            
		  {if $message }
		  $(function(){
		      {if $success }
			      {if $action == 'add'}
			      var submit = function (v, h, f) {
				     if (v == 'ok'){
				     
				     }else if (v == 'cancel'){
				         location.href = "{base_url('lab_user')}";
				     }
				     return true; //close
				  };
				  
				  $.jBox.confirm("{$message},要继续添加吗？", "提示", submit);
				  {else}
				  $.jBox.success('{$message}', '提示');
				  {/if}
		      {else}
		      $.jBox.error('{$message}', '提示');
		      {/if}
		  });
		  {/if}
		</script>
		{include file="./side.tpl"} 
{include file="common/lab_admin_footer.tpl"}