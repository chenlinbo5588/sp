{include file="common/my_header.tpl"}
{config_load file="member.conf"}
    <div id="handleDiv">
	<form name="userForm" method="post" action="{site_url($uri_string)}">
    {if $info['uid']}
    <input type="hidden" name="uid" value="{$info['uid']}"/>
	{/if}
	<input type="hidden" name="lab_id" value="{$lab_id}"/>
    <table class="fulltable style1">
      <tbody>
      	<tr class="noborder">
          <td class="required w120"><label class="validation">{#account#}:</label></td>
          <td class="vatop rowform">
            {if $info['uid']}
            {$info['username']|escape}
            {else}
            <input type="text" value="{$info['username']|escape}" name="username" id="username" class="txt" placeholder="请输入对方的登陆账号">
            {/if}
            <label class="errtip" id="error_username"></label>
          </td>
        </tr>
        {if $lab_param['current']['oid'] == $profile['basic']['uid']}
        <tr class="noborder">
          <td class="required"><label class="validation">设为实验室管理员:</label></td>
          <td class="vatop rowform">
          	<label><input type="radio" name="is_manager" value="y" {if $info['is_manager'] == 'y'}checked{/if}/>是</label>
          	<label><input type="radio" name="is_manager" value="n" {if $info['is_manager'] == 'n'}checked{/if}/>否</label>
            <label class="errtip" id="error_is_manager"></label>
          </td>
        </tr>
        {/if}
        <tr class="noborder">
          <td class="required"><label class="validation" for="role_id">{#rolename#}:</label></td>
          <td class="vatop rowform">
          	<select name="role_id">
          		<option value="0">请选择</option>
          	{foreach from=$roleList item=item}
          		<option value="{$item['id']}" {if $currentRoleId == $item['id']}selected{/if}>{$item['name']}</option>
          	{/foreach}
          	</select>
          	<label class="errtip" id="error_role_id"></label>
             <span>请选择一个角色，如果还未设置，{form_error('role_id')}</span><a class="hightlight" href="{site_url('lab_role/add')}">点击马上设置</a>
          </td>
        </tr>
    	<tr class="noborder">
          <td class="required"><label>{#lab#}:</label></td>
          <td class="vatop rowform">
            <span class="tip">鼠标双击实验室名称项进入实验室详情页 <span class="blue">蓝色文字表示您管辖的实验室</span><label class="errtip" id="error_lab_id"></label>
          	<div id="treeboxbox_tree1" class="treebox rounded_box" style="height:300px;">
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
    </div>
    {include file="common/dhtml_tree.tpl"}
    <script>
	    var user_labs = {$user_labs},manager_labs = {$manager_labs} ,
	    	current_pid = {if $info['pid']}{$info['pid']}{else}""{/if},
	    	current_id = "{$info['id']}";
	    	
            $("form").each(function(){
				var name = $(this).prop("name");
				formLock[name] = false;
			});
	 </script>
    {include file="lab/lab_var.tpl"}
	<script type="text/javascript" src="{resource_url('js/lab/lab_member_add.js')}"></script>
{include file="common/my_footer.tpl"}