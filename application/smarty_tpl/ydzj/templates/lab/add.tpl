{include file="common/my_header.tpl"}
{config_load file="lab.conf"}
    <div id="handleDiv">
    {form_open(site_url($uri_string),'name="labForm"')}
	{if $info['id']}
		<input type="hidden" name="id" value="{$info['id']}"/>
	{/if}
		<input type="hidden" name="pid" value="{if $info['pid']}{$info['pid']}{else}0{/if}"/>
	<table class="fulltable style1">
      <tbody>
        <tr class="noborder">
          <td class="required w120"><label class="validation"><em></em>{#lab_name#}:</label></td>
          <td class="vatop rowform">
                <input type="text" value="{$info['name']|escape}" name="name" class="w40pre txt" placeholder="请输入实验室名称，中文或者英文,中文最多12个汉字">
                <label class="errtip" id="error_name"></label>
          </td>
        </tr>
      	<tr class="noborder">
          <td class="required"><label class="validation" for="address">{#lab_address#}:</label></td>
          <td class="vatop rowform">
          		<input type="text" value="{$info['address']|escape}" name="address" id="address" class="w40pre txt" placeholder="请输入实验室地址，中文或者英文,中文最多12个汉字">
          		<label class="errtip" id="error_address"></label>
          </td>
        </tr>
        <tr class="noborder">
          <td><label for="displayorder">{#displayorder#}:</label></td>
          <td class="vatop rowform">
          	<input type="text" value="{$info['displayorder']|escape}" name="displayorder" id="displayorder" class="txt">
          	<label class="errtip" id="error_displayorder"></label><span>大于等于0的自然数,最大9999，数字越小越靠前</span>
          </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label class="validation" for="parent">{#parent#}:</label></td>
          <td class="vatop rowform">
          	  <span class="tip">鼠标双击实验室名称项进入实验室详情页 <span class="blue">蓝色文字表示您管辖的实验室</span><span id="pid_tip" class="hightlight">{form_error('pid')}</span></span>
	          <div><label class="errtip" id="error_pid"></label></div>
	          <div id="treeboxbox_tree1" class="treebox rounded_box" style="height:300px;">
	               <div id="loading_img" class="loading_div" style="display:none;">加载中...</div>
	          </div>
          </td>
        </tr>
        {if $info['id']}
        <tr class="noborder">
          <td colspan="2" class="vatop rowform">
          	  {*<input class="action" id="addUserBtn" type="button" name="addUser" value="成员管理"/>*}
          	  <div class="rel clearfix">
	          	  <div class="member_sample fr">
	                 <span class="manager_color color_sample">图例：管理员</span>
	                 <span class="member_color color_sample">图例：实验员</span>
	          	  </div>
          	  </div>
	          <div class="rounded_box" style="padding:10px;overflow:auto;">
	          	<h1>成员清单:</h1>
	            <ul class="lab_users clearfix">
	                {foreach from=$memberList item=item}
	                <li id="row{$item['uid']}_{$item['lab_id']}"><a class="username" href="{site_url('lab_user/edit?uid='|cat:$item['uid'])}" {if $item['is_manager'] == 'y' || $item['oid'] == $item['uid']}class="manager_color"{/if}>{$item['username']|escape}</a>{if $profile['basic']['uid'] != $item['uid']}<a class="delete" data-url="{site_url('lab_user/delete')}" data-id="{$item['uid']}_{$item['lab_id']}" data-title="{$item['username']|escape}">x</a>{/if}</li>
	                {/foreach}
	            </ul>
	          </div>
          </td>
        </tr>
        {/if}
        <tr>
          <td></td>
          <td><input type="submit" name="submit" value="保存" class="master_btn"/></td>
        </tr>
       </tbody>
      </table>
      </form>
      </div>
	  <div id="labMemberDlg" title="成员管理"><div id="userlist"></div></div>
	  <div id="dialog-confirm" title="移除成员" style="display:none;"><p><span class="ui-icon ui-icon-alert" style="float:left;"></span>确定要移除成员<span class="memberName hightlight"></span>吗?</p></div>
	 {include file="common/jquery_ui.tpl"}
	 {include file="common/dhtml_tree.tpl"}
      <script>
	    var user_labs = {$user_labs},manager_labs = {$manager_labs},
	    	current_pid = {if $info['pid']}{$info['pid']}{else}""{/if},
	    	current_id = "{$info['id']}",
	    	dialog = null;
	    	
            $("form").each(function(){
				var name = $(this).prop("name");
				formLock[name] = false;
			});
	 </script>
	 {include file="./lab_var.tpl"}
	 <script type="text/javascript" src="{resource_url('js/lab/lab_add.js')}"></script>
{include file="common/my_footer.tpl"}