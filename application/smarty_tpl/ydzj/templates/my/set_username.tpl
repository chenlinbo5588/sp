{include file="common/header.tpl"}
<div class="handle_area">
    {form_open(site_url('my/set_username'))}
    <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
    <div id="profile_avatar">
        <div class="row">
            <label class="side_lb" for="username_txt"><em>*</em>真实名称：</label>
            <input type="text" name="username" id="username_txt" class="at_txt" {if $default_username}value="{$default_username}"{else}value="{set_value('username')}"{/if} placeholder="请输入真实名称,仅限汉字"/>
        </div>
        {form_error('username')}
        <div class="row">
            <input type="submit" name="submit" class="primaryBtn" value="保存"/>
        </div>
    </div>
    </form>
</div>
{include file="common/footer.tpl"}