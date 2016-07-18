{include file="common/header.tpl"}
<section>
    <form name="searchForm" action="{site_url($smarty.const.BASKET_BALL)}" method="get">
    <div class="searchbar pd5 clearfix">
        <label for="seach_key" class="fl">搜索队伍</label>
        <input type="text" class="fl" style="width:50%;" name="search_key" value="{$smarty.get.search_key|escape}" placeholder="请输入队伍名称"/>
        <input type="submit" class="master_btn" style="width:20%;" value="查询"/>
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
            <div class="team_avatar fl"><a href="{site_url('team/detail/'|cat:$item['id'])}"><img src="{base_url($item['avatar_m'])}"/></a></div>
            <div class="team_basic fl">
                <ul>
                    <li>
                        <a class="team_title" href="{site_url('team/detail/'|cat:$item['id'])}">{$item['title']|escape}</a></li>
                    <li>
                        <label>队长:</label><a class="team_leader" href="{site_url('user/info/'|cat:$item['leader_uid'])}">{mask_mobile($item['leader_name'])|escape}</a>
                    </li>
                    <li><label>地区:</label><span>{$item[$cityLevel]}</span></li>
                    <li><label>成员数:</label><strong class="team_pnum">{$item['current_num']}</strong><span>人</span></li>
                    <li><label>比赛场次:</label><strong>{$item['games']}场</strong><label class="win_rate">胜率:</label><strong>{$item['win_rate']}</strong></li>
                </ul>
            </div>
        </div>
        {/foreach}
    </div>
</section>

{include file="common/footer.tpl"}