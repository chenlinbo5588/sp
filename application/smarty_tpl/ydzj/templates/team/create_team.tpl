{include file="common/header.tpl"}
{$warning}
<div id="feedback">{$feedback}</div>
{form_open_multipart(site_url('team/create_team'),'id="createTeamForm"')}
<input type="hidden" name="team_log_id" value="{$team_log_id}"/>
<input type="hidden" name="team_logo" value="{$team_logo}" />
<input type="hidden" name="team_logo_url" value="{$team_logo_url}"/>
<div id="create_team" class="handle_area">
    
    <div class="row">
        <label class="side_lb" for="category_sel"><em>*</em>球队类型：</label>
        <select name="category_id" id="category_sel" class="at_txt">
            {foreach from=$sportsCategoryList item=item}
            <option value="{$item['id']}" {set_select('category_id',$item['id'])}>{$item['name']}</option>
            {/foreach}
        </select>
    </div>
    {form_error('category_id')}
    
    <div class="row">
        <label class="side_lb" for="title_text"><em>*</em>球队名称：</label>
        <input id="title_text" class="at_txt" type="text" name="title" value="{set_value('title')}" placeholder="请输入球队名称,最多8个汉字"/>
    </div>
    <div id="tip_title">{form_error('title')}</div>
    
    <div class="row">
        <label class="side_lb"><em>*</em>队长设置：</label>
        <label style="margin:0 20px 0 0;"><input type="radio" name="leader" value="1" {set_radio('leader',1,true)}/>我是队长</label>
        <label><input type="radio" name="leader" value="2" {set_radio('leader',2)}/>以后设置</label>
    </div>
    {form_error('leader')}
    
    <div class="row">
        <label class="side_lb">入队设置：</label>
        <label><input type="radio" name="joined_type" value="1" {set_radio('joined_type',1,true)}/>通过邀请加入</label>
        {*<label><input type="radio" name="joined_type" value="2" {set_radio('joined_type',2)}/>需要队长验证加入</label>*}
    </div>
    {form_error('joined_type')}
    
    <div class="row">
        <label class="side_lb"  for="logo_url_file">球队合影：</label>
        <input id="logo_url_file" class="at_txt" type="file" name="logo_url"/>
    </div>
    {$logo_error}
    <div class="row"><label class="side_lb"></label>球队合影照片<em class="tip">JPG</em>格式,大小不超过<em class="tip">4MB</em></div>
    <div class="row"><label class="side_lb"></label>分辨率最小<em class="tip">800x800</em> <a id="seeSample" href="javascript:void(0)">查看合影范例</a></div>
    <div class="row" style="display:none;" id="samplWrap">
        <img data-src="{base_url('img/team/team_sample.jpg')}"/>
    </div>
    <div class="row img_preview">
        {if $team_logo_url}
        <img src="{base_url($team_logo_url)}"/>
        {/if}
    </div>
    <div id="submitFixedWrap" >
        <input type="submit" name="submit" class="primaryBtn" value="保存"/>
    </div>
</div>
</form>
<script src="{base_url('js/team.js')}"></script>

{include file="common/footer.tpl"}