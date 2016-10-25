{include file="common/main_header.tpl"}
{config_load file="lab.conf"}
    <link href="{resource_url('css/lab.css')}" rel="stylesheet" type="text/css"/>
    
	{if $info['id']}
		{form_open(site_url($uri_string|cat:$info['id']),'name="labForm"')}
	<input type="hidden" name="id" value="{$info['id']}"/>
	{else}
		{form_open(site_url($uri_string),'name="labForm"')}
	{/if}
	<input type="hidden" name="pid" value="{if $info['pid']}{$info['pid']}{else}0{/if}"/>
	<table class="autotable">
      <tbody>
      	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="address">{#lab_address#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['address']|escape}" name="address" id="address" class="txt"></td>
          <td class="vatop tips"><label class="errtip" id="error_address"></label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="displayorder">{#displayorder#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['displayorder']|escape}" name="displayorder" id="displayorder" class="txt"></td>
          <td class="vatop tips"><label class="errtip" id="error_displayorder"></label> 大于等于0的自然数,最大9999，数字越大越靠前</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="parent">{#parent#}:</label><label class="errtip" id="error_pid"></label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="vatop rowform">
          	  <span class="tip">鼠标双击实验室名称项进入实验室详情页 <span class="blue">蓝色文字表示您管辖的实验室</span><span id="pid_tip" class="hightlight">{form_error('pid')}</span></span>
	          <div id="treeboxbox_tree1" class="rounded_box" style="height:400px;">
	               <div id="loading_img" class="loading_div" style="display:none;"></div>
	          </div>
          </td>
        </tr>
        
        {if $info['id']}
        <tr class="noborder">
          <td colspan="2" class="vatop rowform">
          	  <input class="form_submit" id="addUserBtn" type="button" name="addUser" value="成员管理"/>
	          <div class="rounded_box" style="padding:10px;overflow:auto;">
	          	<label>成员清单:</label>
	            <span class="manager_color color_sample">图例：管理员</span>
	            <span class="member_color color_sample">图例：实验员</span>
	            <ul class="lab_users clearfix">
	                {foreach from=$userList item=item}
	                <li id="row_{$item['user_id']}"><a {if $item['is_manager'] == 'y'}class="manager_color"{/if}" href="javascript:void(0);">{$item['name']|escape}</a><span class="close" data-id="{$item['user_id']|escape}" data-title="{$item['name']|escape}">x</span></li>
	                {/foreach}
	            </ul>
	          </div>
          </td>
        </tr>
        {/if}
       </tbody>
       <tfoot>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
      </form>
	  <div id="labMemberDlg" title="成员管理"><div id="userlist"></div></div>
	  <div id="dialog-confirm" title="移除成员" style="display:none;"><p><span class="ui-icon ui-icon-alert" style="float:left;"></span>确定要移除成员<span class="memberName hightlight"></span>吗?</p></div>

	 {include file="common/jquery_ui.tpl"}
	 {include file="common/dhtml_tree.tpl"}
      <script>
	    var user_labs = {$user_labs};
	    var current_pid = {if $info['pid']}{$info['pid']}{else}""{/if};
	    var dialog = null,tree = null,
	        labEditUrl = "{site_url('lab/edit?id=')}",
            treeXMLUrl = "{site_url('lab/getTreeXML')}",
            treeImgUrl = "{$smarty.const.TREE_IMG_PATH}";
	 </script>
	 <script type="text/javascript" src="{resource_url('js/lab/lab_add.js')}"></script>
{include file="common/main_footer.tpl"}