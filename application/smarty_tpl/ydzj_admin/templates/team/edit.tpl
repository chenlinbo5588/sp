{include file="common/main_header.tpl"}
{config_load file="team.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
        <li><a href="{admin_site_url('team')}" ><span>{#manage#}</span></a></li>
        <li><a href="{admin_site_url('team/add')}" ><span>{#add#}</span></a></li>
        <li><a href="{admin_site_url('team/edit')}?id={$info['basic']['id']}" class="current"><span>{#edit#}:{#team_index#}{$info['basic']['id']}</span></a></li>
      </ul>
      <ul class="tab-sub">
        <li><a href="javascript:void(0);" class="current"><span>{#titel#}基本信息</span></a></li>
        <li><a href="javascript:void(0);"><span>审核记录</span></a></li>
        <li><a href="javascript:void(0);"><span>成员信息</span></a></li>
        <li><a href="javascript:void(0);"><span>比赛记录</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  <div class="tab-content">
	  <div class="tab-item">
	  {form_open_multipart(admin_site_url('team/edit?id='|cat:$info['basic']['id']),'name="form_index"')}
	    <table class="table tb-type2">
	      <tbody>
	      	<tr class="noborder">
	          <td colspan="2" class="required"><label class="validation">{#team_category#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">
	          	<select name="category_id">
	                {foreach from=$sportsCategoryList item=item}
	                <option value="{$item['id']}" {if $info['basic']['category_id'] == $item['id']}selected{/if}>{$item['name']}</option>
	                {/foreach}
	            </select>
	          </td>
	          <td class="vatop tips"><label class="errtip" id="error_category_id"></label></td>
	        </tr>
	      	<tr class="noborder">
	          <td colspan="2" class="required"><label class="validation">{#team_name#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform"><input type="text" class="txt" name="title" value="{$info['basic']['title']|escape}"/></td>
	          <td class="vatop tips"><label class="errtip" id="error_title"></label></td>
	        </tr>
	        <tr class="noborder">
	          <td colspan="2" class="required"><label>{#slogan#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">
	            <input type="text" name="slogan" class="txt" value="{$info['basic']['slogan']|escape}" placeholder="{#please_enter#}{#slogan#}"/>
	          </td>
	          <td class="vatop tips"><label class="errtip" id="error_slogan"></label> 如：不惧任何对手</td>
	        </tr>
	        <tr class="noborder">
	          <td colspan="2" class="required"><label>{#base_area#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">
	            <input type="text" name="base_area" class="txt" value="{$info['basic']['base_area']|escape}" placeholder="{#please_enter#}{#base_area#}"/>
	          </td>
	          <td class="vatop tips"><label class="errtip" id="error_base_area"></label> 如： 傅家路灯光球场</td>
	        </tr>
	        <tr class="noborder">
	          <td colspan="2" class="required"><label>{#notice_board#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">
	            <input type="text" name="notice_board" class="txt" value="{$info['basic']['notice_board']|escape}" placeholder="{#please_enter#}{#notice_board#}"/>
	          </td>
	          <td class="vatop tips"><label class="errtip" id="error_notice_board"></label> 如：球队活动有事者需提前请假</td>
	        </tr>
	        <tr class="noborder">
	          <td colspan="2" class="required"><label>{#team_city#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop ">
	            <div class="cityGroupWrap">
                    <select name="d1" class="citySelect">
                        <option value="">{#choose#}</option>
                        {foreach from=$ds['d1'] item=item}
                        <option value="{$item['id']}" {if $info['basic']['d1'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                        {/foreach}
                    </select>
                    <select name="d2" class="citySelect">
                        <option value="">{#choose#}</option>
                        {foreach from=$ds['d2'] item=item}
                        <option value="{$item['id']}" {if $info['basic']['d2'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                        {/foreach}
                    </select>
                    <select name="d3" class="citySelect">
                        <option value="">{#choose#}</option>
                        {foreach from=$ds['d3'] item=item}
                        <option value="{$item['id']}" {if $info['basic']['d3'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                        {/foreach}
                    </select>
                    <select name="d4" class="citySelect">
                        <option value="">{#choose#}</option>
                        {foreach from=$ds['d4'] item=item}
                        <option value="{$item['id']}" {if $info['basic']['d4'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                        {/foreach}
                    </select>
                </div>
	          </td>
	          <td class="vatop tips">请选择队伍所在的地区</td>
	        </tr>
	        <tr class="noborder">
	          <td colspan="2" class="required"><label>{#team_avatar#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">
	            {if $info['basic']['aid']}<img src="{resource_url($info['basic']['avatar_m'])}"/>{else}还没有{#team_avatar#}{/if}
	          </td>
	          <td class="vatop tips"></td>
	        </tr>
	        <tr>
	          <td colspan="2" class="required"><label>修改球队合照:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">
	            <input type="hidden" name="avatar_id" id="avatar_id" value=""/>
	            <div class="upload"><input type='text' readonly="readonly" class="txt" name='avatar' id='avatar' value="{$info['basic']['avatar']}"/><input type="button" id="uploadButton" value="浏览" /></div>
	            </td>
	          <td class="vatop tips">支持格式jpg或者PNG 最小尺寸 <strong class="warning">{$teamImageConfig['h']['width']}x{$teamImageConfig['h']['height']}</strong></td>
	        </tr>
	        <tr>
	        	<td colspan="2" class="required"><div id="preview"></div></td>
	        </tr>
	        <tr>
	          <td colspan="2" class="required"><label>{#team_leader#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">{if $info['basic']['leader_uid']}<a href="{admin_site_url('member/edit')}?id={$info['basic']['leader_uid']}">{$info['basic']['leader_name']|escape}</a>{else}未设置队长{/if}</td>
	          <td class="vatop tips"></td>
	        </tr>
	        <tr>
	          <td colspan="2" class="required"><label>{#join_type#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">
	          	<label><input type="radio" name="joined_type" value="1" {if $info['basic']['joined_type'] == 1}checked{/if}/>{#join_type1#}</label>
	          	<label><input type="radio" name="joined_type" value="2" {if $info['basic']['joined_type'] == 2}checked{/if}/>{#join_type2#}</label>
	          </td>
	          <td class="vatop tips"></td>
	        </tr>
	        <tr>
	          <td colspan="2" class="required"><label>{#accept_game#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">
	          	<label><input type="radio" name="accept_game" value="1" {if $info['basic']['accept_game'] == 1}checked{/if}/>{#accept_game_enable#}</label>
	          	<label><input type="radio" name="accept_game" value="0" {if $info['basic']['accept_game'] == 0}checked{/if}/>{#accept_game_disable#}</label>
	          </td>
	          <td class="vatop tips"></td>
	        </tr>
	        <tr>
	          <td colspan="2" class="required"><label>战绩:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">
	          	<span>{#games_cnt#}:</span><strong>{$info['basic']['games']}</strong>
	          	<span>{#victory_game#}:</span><strong>{$info['basic']['victory_game']}</strong>
	          	<span>{#fail_game#}:</span><strong>{$info['basic']['fail_game']}</strong>
	          	<span>{#draw_game#}:</span><strong>{$info['basic']['draw_game']}</strong>
	          	<span>{#win_rate#}:</span><strong>{$info['basic']['win_rate']}</strong></td>
	          <td class="vatop tips"></td>
	        </tr>
	        <tr>
	          <td colspan="2" class="required"><label>{#team_credits#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">{#team_credits#}&nbsp;<strong class="red">{$info['basic']['credits']}</strong></td>
	          <td class="vatop tips"></td>
	        </tr>
	        <tr>
	          <td colspan="2" class="required"><label>{#register_channel#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">{$info['basic']['channel']}</td>
	          <td class="vatop tips"></td>
	        </tr>
	        <tr>
	          <td colspan="2" class="required"><label>{#creator#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">{$info['basic']['add_username']|escape}</td>
	          <td class="vatop tips"></td>
	        </tr>
	        <tr>
	          <td colspan="2" class="required"><label>{#create_time#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">{$info['basic']['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
	          <td class="vatop tips"></td>
	        </tr>
	        <tr>
	          <td colspan="2" class="required"><label>{#audit_status#}</label></td>
	        </tr>
	        <tr class="noborder">
	        	<td class="vatop rowform">
	        		<div class="clearfix">
		          		{if $info['basic']['status'] == 1}{#audit_success#}
		          		{elseif $info['basic']['status'] == 0}尚未审核
		          		{elseif $info['basic']['status'] == -1}{#audit_fail#}
		          		{/if}
		        	</div>
		        </td>
	        </tr>
	        <tr>
	          <td colspan="2" class="required"><label>审核备注</label></td>
	        </tr>
	        <tr class="noborder">
	        	<td colspan="2">{$info['audit_log'][0]['remark']|escape}</td>
	        </tr>
	      </tbody>
	      <tfoot>
	        <tr class="tfoot">
	          <td colspan="15"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
	        </tr>
	      </tfoot>
	    </table>
	  </form>
  </div>
  <div class="tab-item">
	  {form_open(admin_site_url('team/audit?id='|cat:$info['basic']['id']),'name="form_audit"')}
	  <table class="tb-type2">
	  	<tbody>
	  		<tr>
	          <td colspan="2" class="required"><label>{#audit_status#}</label></td>
	        </tr>
	        <tr class="noborder">
	        	<td class="vatop rowform">
	        		<div class="clearfix">
		          		<label class="btnlike_segment{if $info['basic']['status'] == 1} selected{/if}"><input type="radio" name="status" value="1" {if $info['basic']['status'] == 1}checked{/if}/><span>{#audit_success#}</span></label>
		          		<label class="btnlike_segment{if $info['basic']['status'] == -1} selected{/if}"><input type="radio" name="status" value="-1" {if $info['basic']['status'] == -1}checked{/if}/><span>{#audit_fail#}</span></label>
		        	</div>
		        	
		        </td>
		        <td class="vatop tips"><label class="errtip" id="error_status"></label></td>
	        </tr>
	        <tr>
	          <td colspan="2" class="required"><label>审核备注</label></td>
	        </tr>
	        <tr class="noborder">
	        	<td><textarea name="remark" style="height:100px;width:100%;"></textarea></td>
	        	<td class="vatop tips"><label class="errtip" id="error_remark"></label></td>
	        </tr>
	  	</tbody>
	  	<tfoot>
	        <tr class="tfoot">
	          <td colspan="15"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
	        </tr>
	      </tfoot>
	  </table>
	  <h3>审核历史记录</h3>
	    <table class="tb-type2">
	      <thead>
	      	<tr>
	      		<th>序号</th>
	      		<th>审核时间</th>
	      		<th>审核人</th>
	      		<th>审核备注</th>
	      	</tr>
	      </thead>
	      <tbody>
	      	{foreach from=$info['audit_log'] item=item key=key}
	        <tr>
	          <td>{$item['id']}</td>
	          <td>{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
	          <td>{$item['add_username']|escape}</td>
	          <td>{$item['remark']|escape}</td>
	        </tr>
	        {foreachelse}
	        <tr>
	        	<td colspan="4">暂无审核记录</td>
	        </tr>
	        {/foreach}
	      </tbody>
	    </table>
	  </form>
  </div>
  <div class="tab-item">
	  {form_open(admin_site_url('team/member'),'name="form_members"')}
	    <table class="tb-type2" id="teamMemberList">
	      <thead>
	      	<tr>
	      		<th></th>
	      		<th>头像</th>
	      		<th>登陆手机号/昵称</th>
	      		<th>真实名称</th>
	      		<th>队内职务</th>
	      		<th>场上位置</th>
	      		<th>球衣号码</th>
	      		<th>排序</th>
	      	</tr>
	      </thead>
	      <tbody>
	      	{foreach from=$info['members'] item=item key=key}
	        <tr id="member_{$item['uid']}">
	          <td>{if $item['uid'] != $info['basic']['leader_uid']}<input type="checkbox" name="sel[]" group="sel" value="{$item['uid']}"/>{/if}</td>
	          <td class="size-64x64">{if $item['aid']}<img src="{resource_url($item['avatar_s'])}"/>{else}暂无头像{/if}</td>
	          <td>
	          	<input type="hidden" name="uid[]" value="{$item['uid']|escape}" />
	          	<input type="text" name="nickname[]" value="{$item['nickname']|escape}"/>
	          </td>
	          <td>
	          	<input type="text" name="username[]" value="{$item['username']|escape}" />
	          	
	          </td>
	          <td>
	          	<select name="rolename[]">
	          	{foreach from=$roleNameList item=subitem}
	      		<option value="{$subitem['name']|escape}" {if $subitem['name'] == $item['rolename']}selected{/if}>{$subitem['name']|escape}</option>
	      		{/foreach}
	      		</select>
	          </td>
	          <td>
	          	<select name="position[]">
	          	{foreach from=$positionList item=subitem}
	      		<option value="{$subitem['name']|escape}" {if $subitem['name'] == $item['position']}selected{/if}>{$subitem['name']|escape}</option>
	      		{/foreach}
	      		</select>
	          </td>
	          <td>
	          	<input type="text" name="num[]" value="{$item['num']|escape}" />
	          </td>
	          <td>
	          	<input type="text" name="displayorder[]" value="{$item['displayorder']|escape}" />
	          </td>
	          <td>
	          	<a class="saveRow" href="javascript:void(0);">保存</a>
	          </td>
	        </tr>
	        {/foreach}
	      </tbody>
	      <tfoot>
	      	<tr><td colspan="9">
	      		<label><input type="checkbox" class="checkall" name="sel" />全选/取消</label>
	      		<input type="button" name="deleteAll" value="删除"/>
	      		<input type="button" name="addTeamMember" value="添加一个成员"/>
	      	</td></tr>
	      </tfoot>
	    </table>
	  </form>
  </div>
  <script type="ydzj-template" id="template_addTeamMember">
  	<tr class="addrow">
      <td><input type="checkbox" name="sel" group="sel" value=""/></td>
      <td></td>
      <td>
      	<input type="hidden" name="uid[]" value="" />
      	<input type="text" name="nickname[]" value="" placeholder="请输入会员手机号码或者昵称" />
      </td>
      <td>
      	<input type="text" name="username[]" value="" />
      </td>
      <td>
      	<select name="rolename[]">
      		<option value="">请选择</option>
      		{foreach from=$roleNameList item=item}
      		<option value="{$item['name']|escape}">{$item['name']|escape}</option>
      		{/foreach}
      	</select>
      </td>
      <td>
      	<select name="position[]">
      		<option value="">请选择</option>
      		{foreach from=$positionList item=item}
      		<option value="{$item['name']|escape}">{$item['name']|escape}</option>
      		{/foreach}
      	</select>
      </td>
      <td>
      	<input type="text" name="num[]" value="" />
      </td>
      <td>
      	<input type="text" name="displayorder[]" value="255" />
      </td>
      <td>
      	<a class="saveRow" href="javascript:void(0);">保存</a>
      	<a class="deleteRow" href="javascript:void(0);">删除</a>
      </td>
    </tr>
  </script>
  {include file="common/ke.tpl"}
  <script type="text/javascript">
	KindEditor.ready(function(K) {
		var uploadbutton = K.uploadbutton({
			button : K('#uploadButton')[0],
			fieldName : 'imgFile',
			extraParams : { formhash : formhash,min_width :{$teamImageConfig['h']['width']},min_height: {$teamImageConfig['h']['height']} },
			url : '{admin_site_url("common/pic_upload")}?mod=team_avatar',
			afterUpload : function(data) {
				refreshFormHash(data);
				if (data.error === 0) {
					K('#avatar').val(data.url);
					K('#avatar_id').val(data.id);
					
					K('#preview').html('<img width="{$teamImageConfig['h']['width']}" height="{$teamImageConfig['h']['height']}" src="' + data.url + '"/>');
					
				} else {
					alert(data.msg);
				}
			},
			afterError : function(str) {
				alert('自定义错误信息: ' + str);
			}
		});
		uploadbutton.fileBox.change(function(e) {
			uploadbutton.submit();
		});
	});
			
			
	$(function(){
		$('.tab-sub').find('a').bind('click',function(){
			$('.tab-sub').find('a').removeClass('current');
			$(this).addClass('current');
			
			$('.tab-content .tab-item').css('display','none');
			$(".tab-content .tab-item:eq(" + $(this).parent('li').index() + ")").show();
			
		});
		
		$('.tab-content .tab-item:gt(0)').css('display','none');
		
		$("input[name=addTeamMember]").bind("click",function(){
			var html = $("#template_addTeamMember").html();
			$("#teamMemberList tbody").prepend(html);
		});
		
		$("#teamMemberList").delegate("a.deleteRow","click",function(){
			$(this).parents("tr").remove();
		});
		
		
		var refreshCurrentPage = function refreshPage(){
			var whitchTab = $(".tab-sub a.current").parent("li").index();
			location.href="{admin_site_url('team/edit?id=')}{$info['basic']['id']}&tabIndx=" + whitchTab;
		};
		
		$("#teamMemberList").delegate("a.saveRow","click",function(){
			var alink = $(this);
			var row = $(this).parents("tr");
			var txt = alink.html();
			
			if(txt == '正在保存'){
				return;
			}		
			
			alink.html('正在保存');
			$.ajax({
				type:'POST',
				url:'{admin_site_url('team/saveTeamMember?id=')}{$info['basic']['id']}',
				dataType:'json',
				data:{
					'formhash' : formhash,
					'uid' : $("input[name='uid[]']",row).val(),
					'username' : $("input[name='username[]']",row).val(),
					'nickname' : $("input[name='nickname[]']",row).val(),
					'position' : $("select[name='position[]']",row).val(),
					'rolename' : $("select[name='rolename[]']",row).val(),
					'num' : $("input[name='num[]']",row).val(),
					'displayorder' : $("input[name='displayorder[]']",row).val()
				},
				success:function(resp){
					refreshFormHash(resp.data);
				
					alink.html('保存');
					$("a.deleteRow",row).remove();
					row.prop("id","member_" + $("input[name='uid[]']",row).val()).removeClass("addrow");
					
					if(resp.message != '保存成功'){
						alert(resp.message);
					}
				},
				error:function(xhr, textStatus, errorThrown){
					alink.html('保存');
				}
			});
		});
		
		$("#teamMemberList").delegate("input[name=deleteAll]",'click',function(){
			var ids = [], that = $(this);
			
			$("input[name='sel[]']").each(function(){
				if($(this).prop("checked")){
					ids.push($(this).val());
				}
			});
			
			if(ids.length == 0){
				alert("请先勾选");
				return;
			}
			
			if(!confirm("真的要删除吗")){
				return;
			}
			
			var txt = that.val();
			if(txt == '正在删除'){
				return;
			}
			
			that.val('正在删除');
			$.ajax({
				type:'POST',
				url:'{admin_site_url('team/removeTeamMember?id=')}{$info['basic']['id']}',
				dataType:'json',
				data:{
					formhash:formhash,
					sel:ids
				},
				success:function(resp){
					that.val('删除');
					refreshFormHash(resp.data);
					
					if(resp.message != '删除成功'){
						alert(resp.message);
					}else{
						for(var i = 0; i < ids.length; i++){
							$("#member_" + ids[i]).remove();
						}
					}
				},
				error:function(xhr, textStatus, errorThrown){
					that.val('删除');
				}
			});
			
		});
		
		$("#teamMemberList").delegate("tr.addrow input[name='nickname[]']","focusout",function(){
			var word = $.trim($(this).val());
			var row = $(this).parents("tr");
			var that = $(this);
			
			if(word.length == 0){
				return;
			}
			
			$.ajax({
				type:'GET',
				url:'{site_url('api/member/getInfoByMobileOrNickname')}',
				dataType:'json',
				data : { word:word },
				success:function(resp){
					if(resp.message == 'success'){
						if($("#member_" + resp.data.uid).size() > 0){
							$("td:eq(1)",row).html('<label class="error">已在列表中,请更换手机号或者昵称</label>');
						}else{
							$("a.saveRow",row).show();
							//row.prop("id","member_" + resp.data.uid);
							
							$("input[name='uid[]']",row).val(resp.data.uid);
							$("input[name='username[]']",row).val(resp.data.username);
							$("input[name='nickname[]']",row).val(resp.data.nickname);
							$("td:eq(1)",row).html('<img src="' + SITEURL  + resp.data.avatar_s + '"/>');
						}
					}
				},
				error:function(xhr, textStatus, errorThrown){
				}
			});
			
		});
		
		
		$(".btnlike_segment").bind("click",function(){
			$(".btnlike_segment").removeClass("selected");
			$(this).addClass("selected");
		});
		
		var formLock = [];
		
		//$.loadingbar({ text: "正在提交，请耐心等待", autoHide: true , urls:[ new RegExp('{admin_site_url('team/edit')}') ] } );
		
		$("form").each(function(){
			var name=$(this).prop("name");
			formLock[name] = false;
		});
		
		$("form").submit(function(){
			var name=$(this).prop("name");
			
			if(formLock[name]){
				return false;
			}
			
			formLock[name] = true;
			
			$.ajax({
				type:'POST',
				url: $(this).prop("action"),
				dataType:'json',
				data:$(this).serialize(),
				success:function(resp){
					formLock[name] = false;
					refreshFormHash(resp.data);
					alert(resp.message);
					if(resp.message != '保存成功'){
						var errors = resp.data.errors;
						var first = null;
						
						for(var f in errors){
							if(first == null){
								first = f;
							}
							$("#error_" + f).html(errors[f]).addClass("error").show();
						}
						$("input[name=" + first + "]").focus();
						
					}else{
						$("label.errtip").hide();
					}
				
				},
				error:function(xhr, textStatus, errorThrown){
					formLock[name] = false;
					alert("服务器发送错误,将重新刷新页面");
					
					refreshCurrentPage();
				}
			});
		
			return false;
		});
		
	});
	</script>
{include file="common/main_footer.tpl"}