{include file="common/header.tpl"}
<div class="handle_area">
	{if $mailed}
	<div class="row">
		<p>我们已经发送验证邮件到{$email}, 请登录邮箱进行激活</p>
	</di>
	{/if}

    {form_open_multipart(site_url('my/set_avatar'),"id='setAvatarForm'")}
    <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
    <input type="hidden" name="inviteFrom" value="{$inviteFrom}"/>
    <input type="hidden" name="new_avatar" value="{if $new_avatar}{$new_avatar}{/if}" />
    <input type="hidden" name="default_avatar" value="{$default_avatar}"/>
    <div id="profile_avatar">
        <div class="row">
            <label class="side_lb" for="username_txt"><em>*</em>真实名称：</label>
            <input type="text" name="username" id="username_txt" class="at_txt" {if $default_username}value="{$default_username}"{else}value="{set_value('username')}"{/if} placeholder="请输入真实名称,仅限汉字"/>
        </div>
        {form_error('username')}
        <div class="row" style="position:relative;">
            <label class="side_lb" for="avatar_txt"><em>*</em>用户头像：</label>
            <input type="file" class="at_txt" id="avatar_txt" name="avatar" value=""/>
            
        </div>
        <div class="warning">头像格式JPG 尺寸图片最小尺寸 200x200 正方形照片</div>
        {$avatar_error}
        <div class="row">
            <input type="submit" name="submit" class="primaryBtn" value="保存"/>
        </div>
        <div class="row" id="preview">
        	{if $new_avatar}
        	<img src="{base_url($new_avatar)}"/>
        	{else}
        	<img src="{base_url($default_avatar)}"/>
        	{/if}
        </div>
        
    </div>
    </form>
</div>

<script>

$(function(){
    
    
});
</script>
<script src="{base_url('js/my.js')}" type="text/javascript"></script>

{include file="common/footer.tpl"}