{include file="common/header.tpl"}
{$feedback}
<div class="row warning">照片格式JPG,不大于4M,最小尺寸800x800</div>
<div class="handle_area">
    {form_open_multipart(site_url('team/set_teamavatar/'|cat:$teamid),"id='setAvatarForm'")}
    <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
    <input type="hidden" name="default_avatar" value="{$default_avatar}"/>
    <div id="profile_avatar">
        <div class="row" style="position:relative;">
            <label class="side_lb" for="avatar_txt"><em>*</em>球队合影：</label>
            <input type="file" class="at_txt" id="avatar_txt" name="avatar" value=""/>
        </div>
        {$avatar_error}
        <div class="row">
            <input type="submit" name="submit" class="master_btn" value="保存"/>
        </div>
        <div class="row" id="preview">
        	{if $default_avatar}
        	<img src="{base_url($default_avatar)}"/>
        	{/if}
        </div>
        
    </div>
    </form>
</div>

<script>


</script>
{include file="common/footer.tpl"}