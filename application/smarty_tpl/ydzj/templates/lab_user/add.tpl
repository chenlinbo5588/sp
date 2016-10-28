{include file="common/my_header.tpl"}
{config_load file="member.conf"}
	<form name="userForm" method="post" action="{site_url($uri_string)}">
    {if $info['id']}
    <input type="hidden" name="uid" value="{$info['uid']}"/>
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
          <td class="required"><label class="validation">{#set_manager#}:</label></td>
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
	    var user_labs = {$user_labs},edit_user_labs = {$edit_user_labs} ,formLock = [],
	    	current_pid = {if $info['pid']}{$info['pid']}{else}""{/if},
	    	current_id = "{$info['id']}",
	    	dialog = null;
	    	
            $("form").each(function(){
				var name = $(this).prop("name");
				formLock[name] = false;
			});
	 </script>
	 
    {include file="lab/lab_var.tpl"}
	<script type="text/javascript" src="{resource_url('js/lab/lab_member_add.js')}"></script>
{include file="common/my_footer.tpl"}