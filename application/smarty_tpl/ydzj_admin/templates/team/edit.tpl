{include file="common/main_header.tpl"}
{config_load file="team.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
        <li><a href="{admin_site_url('team')}" ><span>{#manage#}</span></a></li>
        <li><a href="{admin_site_url('team/add')}" ><span>{#add#}</span></a></li>
        <li><a href="{admin_site_url('team/edit')}?id={$info['basic']['id']}" class="current"><span>{#edit#}</span></a></li>
      </ul>
      <ul class="tab-sub">
        <li><a href="javascript:void(0);" class="current"><span>{#titel#}基本信息</span></a></li>
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
		          		<label class="btnlike_segment"><input type="radio" name="status" value="1" {if $info['basic']['status'] == 1}checked{/if}/><span>{#audit_success#}</span></label>
		          		<label class="btnlike_segment"><input type="radio" name="status" value="-1" {if $info['basic']['status'] == -1}checked{/if}/><span>{#audit_fail#}</span></label>
		        	</div>
		        </td>
	        </tr>
	        <tr>
	          <td colspan="2" class="required"><label>审核备注</label></td>
	        </tr>
	        <tr class="noborder">
	        	<td colspan="2"><textarea name="audit_remark" style="height:100px;width:100%;"></textarea></td>
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
	  {form_open(admin_site_url('team/member'),'name="form_members"')}
	    <table class="tb-type2" id="teamMemberList">
	      <thead>
	      	<tr>
	      		<th></th>
	      		<th>头像</th>
	      		<th>手机号/昵称</th>
	      		<th>真实名称</th>
	      		<th>队内职务</th>
	      		<th>场上位置</th>
	      		<th>球衣号码</th>
	      		<th>排序</th>
	      	</tr>
	      </thead>
	      <tbody>
	      	{foreach from=$info['members'] item=item key=key}
	        <tr>
	          <td><input type="checkbox" name="sel[]" group="sel" value="{$item['id']}"/></td>
	          <td>{if $item['aid']}<img src="{resource_url($item['avatar_s'])}"/>{else}暂无头像{/if}</td>
	          <td>
	          	<input type="hidden" name="uid[]" value="{$item['uid']|escape}" />
	          	<input type="text" name="nickname[]" value="{$item['nickname']|escape}"/>
	          </td>
	          <td>
	          	<input type="text" name="username[]" value="{$item['username']|escape}" />
	          	
	          </td>
	          <td>
	          	<input type="text" name="rolename[]" value="{$item['rolename']|escape}" />
	          </td>
	          <td>
	          	<input type="text" name="position[]" value="{$item['position']|escape}" />
	          </td>
	          <td>
	          	<input type="text" name="num[]" value="{$item['num']|escape}" />
	          </td>
	          <td>
	          	<input type="text" name="displayorder[]" value="{$item['displayorder']|escape}" />
	          </td>
	          <td>
	          	{if $item['uid'] != $info['basic']['leader_uid']}
	          	<a class="deleteRow" href="javascript:void(0);">删除</a>
	          	{/if}
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
  	<tr>
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
      	<input type="text" name="rolename[]" value="" />
      </td>
      <td>
      	<input type="text" name="position[]" value="" />
      </td>
      <td>
      	<input type="text" name="num[]" value="" />
      </td>
      <td>
      	<input type="text" name="displayorder[]" value="" />
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
		
		
		var refreshCurrentPage = function refreshWhenError(){
			alert("服务器发送错误,将重新刷新页面");
			
			var whitchTab = $(".tab-sub a.current").parent("li").index();
			//location.href="{admin_site_url('team/edit?id=')}{$info['basic']['id']}&tabIndx=" + whitchTab;
		};
		
		$("#teamMemberList").delegate("input[name='nickname[]']","focusout",function(){
			var word = $.trim($(this).val());
			
			if(word.length == 0){
				return;
			}
			
			$.ajax({
				type:'GET',
				url:'{site_url('api/member/getInfoByMobileOrNickname')}',
				dataType:'json',
				data : { word:word },
				success:function(resp){
					
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
					
					refreshCurrentPage();
				}
			});
		
			return false;
		});
		
	});
	</script>
{include file="common/main_footer.tpl"}