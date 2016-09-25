{include file="./my_header.tpl"}
	{include file="common/jquery_ui.tpl"}
	<table class="fulltable style1">
		<tbody>
			<tr>
				<td class="w120">用户UID</td>
				<td>{$profile['basic']['uid']}</td>
			</tr>
			<tr>
                <td>登陆账号</td>
                <td>{$profile['basic']['username']|escape}</td>
            </tr>
			<tr>
				<td>昵称</td>
				<td>{$profile['basic']['nickname']|escape}</td>
			</tr>
			<tr>
				<td>手机账号</td>
				<td><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;<a href="javascript:void(0);">{$profile['basic']['mobile']|escape}</a><a class="warning"  href="{site_url('my/change_mobile')}">更换手机号码</a></td>
			</tr>
			<tr>
				<td>邮箱地址</td>
				<td><span>{$profile['basic']['email']|escape}</span>&nbsp;<a id="edit_email" class="warning" href="javascript:void(0);">修改</a> {if $profile['basic']['email_status'] == 0}<span class="hightlight">邮箱尚未认证,可能无法收到邮件提醒</span> <a class="warning" href="{site_url('my/verify_email')}" title="马上认证邮箱">马上认证邮箱</a>{else}<span>已认证</span>{/if}</td>
			</tr>
			<tr>
				<td>头像</td>
				<td>
					<img src="{if $profile['basic']['avatar_s']}{resource_url($profile['basic']['avatar_s'])}{else}{resource_url($siteSetting['default_user_portrait'])}{/if}"/>
					<input type="hidden" name="avatar_id" value="{$info['aid']}"/>
		            <input type="hidden" name="old_avatar_id" value=""/>
		            <input type="hidden" name="old_avatar" value=""/>
		            <div class="upload">
		              <input type='hidden' name='avatar' id='avatar' value="{$info['avatar']}" class='txt' />
		              <input type="button" id="uploadButton" value="选择图片上传" />
		            </div>
				</td>
			</tr>
			<tr>
				<td>QQ</td>
				<td>{$profile['basic']['qq']|escape}</td>
			</tr>
			<tr>
				<td>上次登陆时间</td>
				<td>{$profile['basic']['last_login']|date_format:"%Y-%m-%d %H:%M"}</td>
			</tr>
			<tr>
				<td>注册IP</td>
				<td>{$profile['basic']['last_loginip']|escape}</td>
			</tr>
			<tr>
				<td>注册日期</td>
				<td>{$profile['basic']['reg_date']|date_format:"%Y-%m-%d %H:%M"}</td>
			</tr>
			<tr>
				<td>注册IP</td>
				<td>{$profile['basic']['reg_ip']|escape}</td>
			</tr>
			<tr>
				<td>积分</td>
				<td>{$profile['basic']['credits']|escape}</td>
			</tr>
			<tr>
				<td>推广链接</td>
				<td>
					<input type="text" style="width:80%" value="{$inviteUrl}"/>
					<input class="action" type="button" name="copylink" value="复制推广链接"/>
				</td>
			</tr>
		</tbody>
	</table>
	{include file="common/jquery_ui.tpl"}
	{include file="common/jquery_validation.tpl"}
	{include file="common/ke.tpl"}
	{include file="common/jcrop.tpl"}
	
	<div id="dialog" title="修改邮箱">
		{form_open(site_url('my/set_email'),"id='editEmailForm'")}
			<input type="text" class="at_txt" name="newemail" value="" placeholder="请输入新的邮箱地址"/>
		</form>
	</div>
	<script>
	
		//裁剪图片后返回接收函数
		function call_back(resp){
		    $('#previewWrap').html('<img src="' + resp.url + '"/>');
		}
		
		KindEditor.ready(function(K) {
			var uploadbutton = K.uploadbutton({
				button : K('#uploadButton')[0],
				fieldName : 'imgFile',
				extraParams : { min_width :{$avatarImageSize['b']['width']},min_height: {$avatarImageSize['b']['height']} },
				url : '{site_url("common/pic_upload")}?mod=member_avatar',
				afterUpload : function(data) {
					refreshFormHash(data);
					if (data.error === 0) {
						$("input[name=old_avatar]").val($("input[name=avatar]").val());
		            	$("input[name=old_avatar_id]").val($("input[name=avatar_id]").val());
		            	
		                $("input[name=avatar_id]").val(data.id);
		                $("input[name=avatar]").val(data.url);
						
						ajax_form('cutpic','裁剪','{admin_site_url("member/pic_cut")}?type=member&x={$avatarImageSize['m']['width']}&y={$avatarImageSize['m']['height']}&resize=0&ratio=1&url='+data.url,800);
						
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
	
	
	</script>
	<script type="text/javascript" src="{resource_url('js/my/index.js')}"></script>
{include file="./my_footer.tpl"}

