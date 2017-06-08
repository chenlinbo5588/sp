{include file="common/my_header.tpl"}
	<table class="fulltable style1">
		<tbody>
			<tr>
				<td {if !$isMobile}class="w120"{/if}>用户UID</td>
				<td>{$profile['basic']['uid']}</td>
			</tr>
			<tr>
                <td>登陆账号</td>
                <td>{$profile['basic']['username']|escape}</td>
            </tr>
            {*
			<tr>
				<td>昵称</td>
				<td>{$profile['basic']['nickname']|escape}</td>
			</tr>
			*}
			<tr>
				<td>手机账号</td>
				<td><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;<a href="javascript:void(0);">{$profile['basic']['mobile']|escape}</a></td>
			</tr>
			<tr>
				<td>邮箱地址</td>
				<td>
				    <div>
				        <span id="emailAddr">{$profile['basic']['email']|escape}</span>&nbsp;<a id="edit_email" class="warning" href="javascript:void(0);">修改</a>
				    </div>
			   </td>
			</tr>
			<tr>
				<td>头像</td>
				<td>
					<img src="{if $profile['basic']['avatar_s']}{resource_url($profile['basic']['avatar_s'])}{else}{resource_url($siteSetting['default_user_portrait'])}{/if}"/>
		            <div class="upload">
		              <input id="file_upload" type="file" name="trade_pic" /><span class="hightlight">请上传尺寸JPG,PNG格式图片,大小1M以内</span>
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
				<td>上次登录IP</td>
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
			{*
			<tr>
				<td>积分</td>
				<td>{$profile['basic']['credits']|escape}</td>
			</tr>
			<tr class="tuiguang">
				<td>推广链接</td>
				<td>
					<input id="tuiguang" type="text" style="width:80%" value="{$siteSetting['site_name']} {$profile['basic']['username']} 邀请您注册 {$inviteUrl}"/>
					<input class="action" type="button" name="copylink" value="复制推广链接"/>
				</td>
			</tr>
			*}
		</tbody>
	</table>
	{include file="common/jquery_validation.tpl"}
	{include file="common/uploadify.tpl"}
	{include file="common/jcrop.tpl"}
	
	<div id="dialog" title="修改邮箱" style="display:none;">
	    <div class="loading_bg" style="display:none;">提交中，请稍候...</div>
	    <div class="tip">修改邮箱后，请对邮箱进行认证</div>
		{form_open(site_url('my/set_email'),"id='editEmailForm'")}
			<table class="fulltable noborder">
                <tr><td><input type="text" class="at_txt" name="newemail" value="" placeholder="请输入新的邮箱地址"/></td></tr>
			    <tr><td><input type="submit" class="master_btn at_txt" name="tijiao" value="保存"/></td></tr>
			</table>
		</form>
	</div>
	<div id="imgCut" title="头像裁切">
		<div id="srcImg"></div>
		<form id="cutForm" action="{site_url('my/set_avatar')}" method="post">
		  <input type='hidden' name='avatar' id='avatar' value="" class='txt' />
		  <input type="hidden" name="avatar_id" value="{$info['aid']}"/>
		  <input type="hidden" name="old_avatar_id" value=""/>
		  <input type="hidden" name="old_avatar" value=""/>
          <input type="hidden" id="x" name="x"/>
          <input type="hidden" id="y" name="y"/>
          <input type="hidden" id="w" name="w"/>
          <input type="hidden" id="h" name="h"/>
       </form>
	</div>
	
	<script>
		var uploadUrl = '{site_url("my/upload_avatar")}?mod=member_avatar';
		var min_width = {$avatarImageSize['m']['width']},min_height = {$avatarImageSize['m']['height']};
		var swfUrl = "{resource_url('js/uploadify/uploadify.swf')}";
	</script>
	<script type="text/javascript" src="{resource_url('js/my/index.js')}"></script>
{include file="common/my_footer.tpl"}

