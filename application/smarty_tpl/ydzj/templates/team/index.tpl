{include file="common/header.tpl"}
<section>
    {form_open(site_url('team/index'))}
    <div class="searchbar pd5">
        <div id="switchCity" class="row">
	        <label>切换城市</label>
	        <select name="city">
	            <option value="">全部</option>
	            {foreach from=$cities item=item}
	            <option value="{$item['name']}" {if $smarty.post.city == $item['name']}selected="selected"{/if}>{$item['name']}</option>
	            {/foreach}
	        </select>
	        <a class="baritem_addteam fr" href="{site_url('team/create_team')}">+创建球队</a>
	    </div>
        <div class="row">
	        <label for="seach_key">搜素队伍</label>
	        <input type="text" name="search_key" value="{$smarty.post.search_key}" placeholder="请输入队伍名称"/>
	        <input class="" type="submit" value="查询"/>
        </div>
    </div>
    </form>
</section>

<section>
    {*
    <div class="row">
        <ul id="categoryList" class="clearfix">
            {foreach from=$sportsCategoryList item=item}
            <li class="fl">
                <a href="{site_url('team/category')}/{$item['id']}">{$item['name']}</a>
            </li>
            {/foreach}
        </ul>
    </div>
    *}
    <div id="teamList" class="list pd5">
        {foreach from=$teamList['data'] item=item}
        <div class="team clearfix">
            <div class="fl"><a href="{site_url('team/detail/'|cat:$item['id'])}"><img src="{base_url($item['avatar_small'])}"/></a></div>
            <div class="team_basic">
                <ul>
                    <li><a class="team_title" href="{site_url('team/detail/'|cat:$item['id'])}">{$item['title']|escape}</a></li>
                    <li><label>队长:</label><a class="team_leader" href="{site_url('user/info/'|cat:$item['leader_uid'])}">{$item['leader_name']|escape}</a></li>
                    <li><label>根据地:</label><span>{if $item['base_area']}{$item['base_area']|escape}{else}未设置{/if}</span></li>
                    <li><label>成员数:</label><strong class="team_pnum">{$item['current_num']}</strong><span>人</span></li>
                    <li><label>比赛场次:</label><strong>{$item['games']}场</strong><label class="win_rate">胜率:</label><strong>{$item['win_rate']}</strong></li>
                </ul>
            </div>
            <div class="order_game">
                {if $item['accept_game']}
                <a class="link_btn greenBtn" href="{site_url('team/order_game')}">预约比赛</a>
                {else}
                <a class="link_btn grayed" href="javascript:void(0);">暂时不能预约</a>
                {/if}
            </div>
        </div>
        {/foreach}
    </div>
</section>

{include file="common/footer.tpl"}