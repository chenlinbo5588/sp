{include file="common/header.tpl"}

{$warning}
{if !$isCreateOk}
{$feedback}
{form_open_multipart(site_url('team/create_team'))}
<div id="create_team" class="handle_area">
    {form_error('category_id')}
    <div class="row">
        <label class="side_lb" for="category_sel"><em>*</em>队伍类型：</label>
        <select name="category_id" id="category_sel" class="at_txt">
            {foreach from=$sportsCategoryList item=item}
            <option value="{$item['id']}" {set_select('category_id',$item['id'])}>{$item['name']}</option>
            {/foreach}
        </select>
    </div>
    {form_error('title')}
    <div class="row">
        <label class="side_lb" for="title_text"><em>*</em>队伍名称：</label>
        <input id="title_text" class="at_txt" type="text" name="title" value="{set_value('title')}" placeholder="请输入队伍名称,最多20个汉字"/>
    </div>
    {form_error('leader')}
    <div class="row">
        <label class="side_lb"><em>*</em>队长设置：</label>
        <label style="margin:0 20px 0 0;"><input type="radio" name="leader" value="1" {set_radio('leader',1,true)}/>我是队长</label>
        <label><input type="radio" name="leader" value="2" {set_radio('leader',2)}/>以后设置</label>
    </div>
    {$logo_url}
    <div class="row">
        <label class="side_lb"  for="logo_url_file">队伍合影：</label>
        <input id="logo_url_file" class="at_txt" type="file" name="logo_url"/>
    </div>
    <div class="row"><label class="side_lb"></label>队伍合影照片<em class="tip">JPG</em>格式,大小不超过<em class="tip">4MB</em></div>
    <div class="row"><label class="side_lb"></label>分辨率最小<em class="tip">200X200</em></div>
    {form_error('joined_type')}
    <div class="row">
        <label class="side_lb">入队设置：</label>
        <label><input type="radio" name="joined_type" value="1" {set_radio('joined_type',1,true)}/>通过邀请加入</label>
        {*<label><input type="radio" name="joined_type" value="2" {set_radio('joined_type',2)}/>需要队长验证加入</label>*}
    </div>
    <div class="row">
        <input type="submit" name="submit" class="primaryBtn" value="保存"/>
    </div>
</div>
</form>
{else}
<div class="row">{$feedback}</div>
<div class="row"><a href="{$teamUrl}">查看队伍信息</a></div>
{/if}

{include file="common/footer.tpl"}