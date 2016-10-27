{include file="common/my_header.tpl"}
{config_load file="member.conf"}
    {if $info['id']}
    <form name="userForm" method="post" action="{site_url('lab_user/edit?id=')}{$info['id']}">
    <input type="hidden" name="id" value="{$info['id']}"/>
	{else}
	<form name="userForm" method="post" action="{site_url('lab_user/add')}">
	{/if}
	<input type="hidden" name="lab_id" value="{$lab_id}"/>
    <table class="fulltable style1">
      <tbody>
      	<tr class="noborder">
          <td class="required w120"><label class="validation">{#account#}:</label></td>
          <td class="vatop rowform">
          	<input type="text" value="{$info['account']|escape}" name="account" id="account" class="txt" placeholder="请输入对方的登陆账号">
            <label class="errtip" id="error_account"></label>
          </td>
        </tr>
        {if 1}
        <tr class="noborder">
          <td class="required"><label>{#set_manager#}:</label></td>
          <td class="vatop rowform">
          	<label><input type="radio" name="is_manager" value="y" {if $info['is_manager'] == 'y'}checked{/if}/>是</label>
          	<label><input type="radio" name="is_manager" value="n" {if $info['is_manager'] == 'n'}checked{/if}/>否</label>
            <label class="errtip" id="error_is_manager"></label>
          </td>
        </tr>
        {/if}
        <tr class="noborder">
          <td class="required"><label class="validation" for="group_id">{#rolename#}:</label><label class="errtip" id="error_group_id"></label></td>
          <td class="vatop rowform">
          	<select name="group_id">
          		<option value="0">请选择</option>
          	{foreach from=$roleList item=item}
          		<option {if $info['group_id'] == $item['id']}selected{/if} value="{$item['id']}">{$item['name']}</option>
          	{/foreach}
          	</select>
             <span>请选择一个权限组，如果还未设置，{form_error('group_id')}</span><a class="hightlight" href="{site_url('authority/role_add')}">点击马上设置</a>
          </td>
        </tr>
    	<tr class="noborder">
          <td class="required"><label>{#lab#}:</label></td>
          <td class="vatop rowform">
          	<span class="hightlight">鼠标双击实验室名称项进入实验室详情页</span><label class="errtip" id="error_lab_id"></label>
          	<div id="treeboxbox_tree1" class="treebox rounded_box" style="height:250px;">
          		<div id="loading_img" class="loading_div" style="display:none;"></div>
	        </div>
          </td>
        </tr>
        <tr>
          <td></td>
          <td><input type="submit" class="master_btn" name="submit" value="保存" class="msbtn"/></td>
        </tr>
        </tbody>
    </table>
    </form>
    {include file="common/dhtml_tree.tpl"}
	<script>
		var tree=new dhtmlXTreeObject("treeboxbox_tree1","100%","100%",0);
		tree.setImagePath("{$smarty.const.TREE_IMG_PATH}");
		
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
		    location.href= "{site_url('lab/edit?id=')}" + id + "&t=" + Math.random();
		}
		
		tree.setOnCheckHandler(toncheck);
		tree.setOnLoadingStart(treeLoading);
		tree.setOnLoadingEnd(treeLoaded); 
		tree.setOnDblClickHandler(tondblclick);
		
		
		setTimeout(function(){
			tree.loadXML("{site_url('lab/getTreeXML')}",function(){
			    var parent = 0;
			    var list = tree.getAllUnchecked(); 
			    var ids = list.split(',');
			    
			    {* 全部不可选 *}
			    {if $admin_profile['basic']['id'] != $smarty.const.LAB_FOUNDER_ID }
			    for(var i = 0; i < ids.length; i++)
			    {
			        if(ids[i] != 0){
			            tree.setItemColor(ids[i], "#BDBDBD", "#FF0000");
			            tree.disableCheckbox(ids[i],true);
			        }
			    }
			    {/if}
			    
			    treeExpand(tree);
			    
			    {* 当前登录为成员的实验室 设置 黑色  *}
			    {if $user_labs}
			        {foreach from=$user_labs item=item}
			            {if $item != 0}
			            parent = tree.getParentId({$item});
			            if(parent != 0){
			                tree.openItem(parent);
			            }
			            tree.setItemColor({$item}, "black", "blue");
			            tree.disableCheckbox({$item},false);
			                {if empty($info['id'])}
			                tree.setCheck({$item}, true);
			                {/if}
			            {/if}
			        {/foreach}
			        
			        {* 当前编辑用户 使得可选 *}
			        {if $edit_user_labs}
			            {foreach from=$edit_user_labs item=item}
			            tree.disableCheckbox({$item},false);
			            tree.setCheck({$item['lab_id']}, true);
			            {/foreach}
			        {/if}
			    {/if}
			    
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
		
		$(function(){
			
		});
	</script>
{include file="common/my_footer.tpl"}