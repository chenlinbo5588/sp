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
  {include file="./lab_common.tpl"}
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
    {include file="common/dhtml_tree.tpl"}
	{if $info['id']}
		{form_open(admin_site_url('lab/edit?id='|cat:$info['id']),'name="labForm"')}
	<input type="hidden" name="id" value="{$info['id']}"/>
	{else}
		{form_open(admin_site_url('lab/add'),'name="labForm"')}
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
	                <li id="row_{$item['user_id']}"><a {if $item['is_manager'] == 'y'}class="manager_color"{/if}" href="javascript:void(0);" data-width="300" data-title="成员详情"  data-href="{base_url('lab/get_join_info?id=')}{$item['user_id']}" >{$item['name']|escape}</a><span class="close" data-id="{$item['user_id']|escape}" data-title="{$item['name']|escape}">x</span></li>
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

      <script>
	    var user_labs = {$user_labs};
	    var current_pid = {if $info['pid']}{$info['pid']}{else}""{/if};
	    var dialog = null;
	    
	    var tree=new dhtmlXTreeObject("treeboxbox_tree1","100%","100%",0);
	    tree.setImagePath("{$smarty.const.TREE_IMG_PATH}");
	    
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
	            return;
	        }else{
	        	location.href= "{admin_site_url('lab/edit?id=')}" + id + "&" + Math.random();
	        }
	    }
	    
	    tree.setOnDblClickHandler(tondblclick);
	    tree.setOnLoadingStart(treeLoading);
	    tree.setOnLoadingEnd(treeLoaded); 
	    
	    tree.loadXML("{admin_site_url('lab/getTreeXML')}",function(){
	        var parent = 0;
	        {if empty($info['id'])}
	        {if $info['pid']}tree.selectItem({$info['pid']});{/if}
	        {elseif $info['id']}
	        tree.selectItem({$info['id']});
	        {/if}
	        
	        {include file="./tree_unexpand.tpl"}
	        
	        // 用户管理的实验室
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
	    
	    
	    {* 成员管理 *}
	    {if $info['id']}
	    function ajaxPage(page){
	        //$("#user_loading").show();
	        $.ajax({
	            type:"GET",
	            cache:false,
	            url:"{admin_site_url('lab_user/search')}",
	              data: { id: {$info['id']} ,page: page , username: $("#search_username").val()},
	              success:function(data){
	              	$("#userlist").html(data);
	              	dialog.dialog( "open" );
	              }
	        });
	    }
	    {/if}
	    
	    
	    var successHandler = function(json){
        	alert(json.message);
	      	if(json.message == '操作成功'){
	      		location.href= "{admin_site_url('lab/edit?id=')}" + id + "&" + Math.random();
	      	}
        };
	    
	    
	    $(function(){
	    	{include file="common/form_ajax_submit.tpl"}
	    	
	    	{if $info['id']}
	    	dialog = $( "#labMemberDlg" ).dialog({
			      autoOpen: false,
			      height: 'auto',
			      width: 800,
			      modal: true,
			      buttons: [
			      	{
				      text: "确定",
				      icons: {
				        primary: "ui-icon-heart"
				      },
				      click: function() {
				      
				      	if(!confirm("确定要进行管理操作吗？")){
				      		return;
				      	}
				      	
				      	$.ajax({
                              type:"POST",
                              url:"{admin_site_url('lab/manager_lab_user')}",
                              dataType:"json",
                              data: "id={$info['id']}&" + $("form[name=memberForm]").serialize(),
                              success:successHandler
                         });
				      }
				    },
				    {
				      text: "取消",
				      icons: {
				        primary: "ui-icon-heart"
				      },
				      click: function() {
				         $( this ).dialog( "close" );
				      }
				    }
				  ],
			      close: function() { }
			});
			
			
	        
	        $("body").delegate("#search_btn","click",function(){
	            ajaxPage(1);
	        });
	        
	        $("#addUserBtn").bind("click",function(){
	        	$.ajax({
	        		type:"GET",
	        		url:"{admin_site_url('lab_user/search?id=')}{$info['id']}&t=" + Math.random(),
	        		success:function(resp){
	        			$("#userlist").html(resp);
	        			dialog.dialog( "open" );
	        		}
	        	});
	        });
	        
	        $.loadingbar({ urls: [ new RegExp("{admin_site_url('lab/manager_lab_user') }"),new RegExp("{admin_site_url('lab_user/search') }") ], templateData:{ message:"努力加载中..." } , container: "#labMemberDlg" });
	        
	        $(".close").bind("click",function(){
	        	var user_id = $(this).attr("data-id");
                var user_name = $(this).attr("data-title");
                var alink = $(this);
                
                $( "#dialog-confirm .memberName" ).html(user_name);
                
	        	$( "#dialog-confirm" ).dialog({
			      resizable: false,
			      height: "auto",
			      width: 400,
			      modal: true,
			      position: { my: "left bottom", at: "left bottom", of: alink },
			      buttons: {
			        "确定": function() {
			          $.ajax({
	                          type:"POST",
	                          url:"{admin_site_url('lab/delete_lab_user')}",
	                          dataType:"json",
	                          data: { id: {$info['id']} , user_id: user_id},
	                          success:function(json){
	                          	 alert(json.message);
	                          	 if(json.message == '删除成功'){
	                          	 	$("#row_" + user_id).fadeOut();
	                          	 }
	                          },
	                          error:function(XMLHttpRequest, textStatus, errorThrown){
	                          }
	                     });
	                     
			          $( this ).dialog( "close" );
			        },
			        "取消": function() {
			          $( this ).dialog( "close" );
			        }
			      }
			    });
	        });
	        {/if}
	    });
	 </script>
{include file="common/main_footer.tpl"}