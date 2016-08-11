{include file="common/main_header.tpl"}
{config_load file="lab.conf"}
    <style type="text/css">
    
    .lab_users {
        margin: 10px 0 0 0;
    }
    
    .lab_users li {
        display:block;
        float:left;
        width:80px;
        text-align:center;
        margin:2px 3px;
        position:relative;
    }
    
    .lab_users li a {
        display:block;
        padding: 5px 3px;
        background:#f3d987;
    }
    
    .lab_users li a:hover {
        background:#F0C640;
    }
    .lab_users .close {
        position: absolute;
		right: 0px;
		top: 5px;
		width: 20px;
		height: 20px;
		cursor:pointer;
    }
    
    .lab_users li a.manager_color , .manager_color {
        background-color: #4F7ED1;
        color:#fff;
    }
    
    .lab_users li a.manager_color:hover , .manager_color:hover {
        background-color: #276DEB;
    }
    
    
    .member_color {
        background:#f3d987;
    }
    
    .color_sample { 
        padding: 3px;
    }
    
    .form_row .be_manager {
        float:none;
        display:inline;
    }
    </style>
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('lab/index')}"><span>{#manage#}</span></a></li>
      	<li><a href="{admin_site_url('lab/add')}"><span>{#add#}</span></a></li>
      	{if $info['id']}<li><a href="{admin_site_url('lab/edit?id='|cat:$info['id'])}" class="current"><span>{#edit#}</span></a></li>{/if}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
    {include file="common/dhtml_tree.tpl"}
	{if $info['id']}
		{form_open(admin_site_url('lab/edit?id='|cat:$info['id']),'name="categoryForm"')}
	<input type="hidden" name="id" value="{$info['id']}"/>
	{else}
		{form_open(admin_site_url('lab/add'),'name="categoryForm"')}
	{/if}
	<input type="hidden" name="pid" value="{if $info['pid']}{$info['pid']}{else}{/if}"/>
	<table>
      <tbody>
      	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="address">{#lab_address#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['address']|escape}" name="address" id="address" class="txt"></td>
          <td class="vatop tips">{form_error('address')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="displayorder">{#displayorder#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['displayorder']|escape}" name="displayorder" id="displayorder" class="txt"></td>
          <td class="vatop tips">{form_error('displayorder')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="parent">{#parent#}:</label>{form_error('displayorder')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	  <span class="tip">鼠标双击实验室名称项进入实验室详情页 <span class="blue">蓝色文字表示您管辖的实验室</span> <span id="pid_tip"" class="warning">{form_error('pid')}</span></span>
	          <div id="treeboxbox_tree1" class="rounded_box" style="height:400px;">
	               <div id="loading_img" class="loading_div" style="display:none;"></div>
	          </div>
          </td>
          <td class="vatop tips"></td>
        </tr>
        
        {if $info['id']}
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="parent">{#parent#}:</label>{form_error('displayorder')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	  <label>成员管理:</label><input class="form_submit" id="addUserBtn" type="button" name="addUser" value="成员管理"/>
          	  <label>成员清单:</label>
	          <div class="rounded_box" style="padding:0px;height:150px;overflow:auto;">
	            <span class="manager_color color_sample">图例：管理员</span>
	            <span class="member_color color_sample">图例：实验员</span>
	            <ul class="lab_users clearfix">
	                {foreach from=$userList item=item}
	                <li id="row_{$item['user_id']}"><a {if $item['is_manager'] == 'y'}class="manager_color"{/if}" href="javascript:void(0);" data-width="300" data-title="成员详情"  data-href="{base_url('lab/get_join_info/id/')}{$item['user_id']}" >{$item['name']|escape}</a><span class="close" data-id="{$item['user_id']|escape}" data-title="{$item['name']|escape}">x</span></li>
	                {/foreach}
	            </ul>
	          </div>
	          
          </td>
          <td class="vatop tips"></td>
        </tr>
        {/if}
       </tbody>
       <tfoot>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
      </form>
      <script>
	    var user_labs = {$user_labs};
	    var current_pid = {if $info['pid']}{$info['pid']}{else}""{/if};
	    var tree=new dhtmlXTreeObject("treeboxbox_tree1","100%","100%",0);
	    tree.setImagePath("/static/js/dhtmlxTree_v413_std/skins/web/imgs/dhxtree_web/");
	    
	    function tonclick(id){
	        var isFind = false;
	        {if $admin_profile['basic']['id'] != $smarty.const.LAB_FOUNDER_ID}
	            {* 勾选是否在自己管理的实验室 *}
	            for(var i = 0 ; i < user_labs.length ; i++ )
	            {
	                if(id != 0 && user_labs[i] == id){
	                   isFind = true;
	                }
	            }
	        {else}
	           isFind = true;
	        {/if}
	        
	        {* 没有修改父级 不报错 *}
	        if(isFind == false && id == current_pid){
	            isFind = true;
	        }
	        
	        if(isFind == false){
	            $("#pid_tip").html( tree.getItemText(id) + " 不在属于您管辖范围，不能指定");
	            $("input[name=pid]").val("");
	            return ;
	        }else{
	            $("#pid_tip").html("");
	        }
	        
	        if(id == 'root'){
	            $("input[name=pid]").val(0);
	        }else{
	            $("input[name=pid]").val(id);
	        }
	        
	    };
	    tree.enableHighlighting(true);
	    tree.enableSmartXMLParsing(true);
	    
	    tree.setOnClickHandler(tonclick);
	    
	    function treeLoading(){
	        $("#loading_img").show();
	    }
	    
	    function treeLoaded(){
	        $("#loading_img").hide();
	    }
	    
	    function tondblclick(id){
	        if(id == 'root'){
	            return
	        }
	        location.href= "{admin_site_url('lab/edit/id/')}" + id + "?" + Math.random();
	    }
	    
	    tree.setOnDblClickHandler(tondblclick);
	    tree.setOnLoadingStart(treeLoading);
	    tree.setOnLoadingEnd(treeLoaded); 
	    
	    tree.loadXML("{admin_site_url('lab/getTreeXML')}",function(){
	        var parent = 0;
	        {if $act == 'add'}
	        {if $info['pid']}tree.selectItem({$info['pid']});{/if}
	        {elseif $act == 'edit'}
	        {if $info['id']}tree.selectItem({$info['id']});{/if}
	        {/if}
	        
	        {include file="./tree_unexpand.tpl"}
	        
	        for(var i = 0 ; i < user_labs.length ; i++ )
	        {
	            if(user_labs[i] != 0){
	                parent = tree.getParentId(user_labs[i]);
	                if(parent != 0){
	                    tree.openItem(parent);
	                }
	                tree.setItemColor(user_labs[i], "blue", "#EC1336");
	                //tree.setItemText(user_labs[i], "【我的】" + tree.getItemText(user_labs[i]), "您的实验室:" + tree.getItemText(user_labs[i]));
	            }
	        }
	        
	    });
	    
	    
	    function validation(form){
	        if($("input[name=address]").val() == ''){
	            $.jBox.tip('请输入实验室地址:', '提示');
	            $("input[name=address]").focus();
	            return false;
	        }
	        
	        {if $userProfile['id'] != $smarty.const.LAB_FOUNDER_ID}
	        if($("input[name=pid]").val() == '' || $("input[name=pid]").val() == 0){
	            $.jBox.tip('请输入实验室父级:', '提示');
	            return false;
	        }
	        {/if}
	        
	        return true;
	    }
	
	    
	    {if $info['id']}
	    function ajaxPage(page){
	        //$("#user_loading").show();
	        $.ajax({
	            type:"GET",
	            cache:false,
	            url:"{base_url('lab_user/search')}",
	              data: { id: {$info['id']} ,page: page , username: $("#search_username").val()},
	              success:function(data){
	                  $("#usertable").html(data);
	              }
	        });
	    }
	    {/if}
	    
	    
	    $(function(){
	        {if $message}
	            {if $success }
	              $.jBox.success('{$message}',"提示");
	            {else}
	            $.jBox.error('{$message}', '提示');
	            {/if}
	        {/if}
	          
	        {if $info['id']}
	        $("body").delegate("#search_btn","click",function(){
	            ajaxPage(1);
	        });
	        
	        $("#addUserBtn").bind("click",function(){
	            $.jBox("get:{admin_site_url('lab_user/search_popup?id=')}{$info['id']}",{ 
		            title:"查找用户",
		            width:570,
		            height:'auto',
		            buttons:{ "确定" : 1, "取消" : 0 },
		            submit: function (v, o, f) {
		                //console.log(v);
		                if (v == 0) {
				            return true; // close the window
				        }else if(v == 1){
				             $.ajax({
	                              type:"POST",
	                              url:"{admin_site_url('lab/manager_lab_user')}",
	                              dataType:"json",
	                              data: "id={$info['id']}&" + $("form[name=memberForm]").serialize(),
	                              success:ajax_success
	                         });
				        }
				        
		                return true;
		            },
		            loaded:function(h){
		                
		            }
		        });
	        });
	        
	        $.loadingbar({ urls: [ new RegExp("{admin_site_url('lab/manager_lab_user') }") ], templateData:{ message:"努力加载中..." }   });
	        
	        $(".close").bind("click",function(){
	            var user_id = $(this).attr("data-id");
	            var user_name = $(this).attr("data-title");
	            var submit = function (v, h, f) {
	                if (v == 'ok'){
	                     $.ajax({
	                          type:"POST",
	                          url:"{admin_site_url('lab/delete_lab_user')}",
	                          dataType:"json",
	                          data: { id: {$info['id']} , user_id: user_id},
	                          success:ajax_success
	                     });
	                }
	            
	                return true;
	            };
	            
	            $.jBox.confirm("确定把实验员 " + user_name + " 从当前实验室删除吗？", "提示", submit);
	        });
	        {/if}
	    });
	 </script>
{include file="common/main_footer.tpl"}