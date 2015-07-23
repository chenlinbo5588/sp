{include file="common/header.tpl"}
<section>
    {form_open(site_url('team/index'))}
    <div id="switchCity" class="row">
        <label>切换城市</label>
        <select name="city">
            <option value="">全部</option>
            {foreach from=$cities item=item}
            <option value="{$item['name']}" {if $smarty.post.city == $item['name']}selected="selected"{/if}>{$item['name']}</option>
            {/foreach}
        </select>
        <a class="fr" href="{site_url('team/create_team')}">+创建我的队伍</a>
    </div>
    
    <div class="row">
        <label for="seach_key">搜素队伍</label>
        <input type="text" name="search_key" value="{$smarty.post.search_key}" placeholder="请输入队伍名称"/>
        <input type="submit" value="查询"/>
    </div>
    </form>
</section>

<section>
    <div class="row">
        <ul id="categoryList" class="clearfix">
            {foreach from=$sportsCategoryList item=item}
            <li class="fl">
                <a href="{site_url('team/category')}/{$item['id']}">{$item['name']}</a>
            </li>
            {/foreach}
        </ul>
    </div>
    <div id="teamList" class="list">
        {foreach from=$teamList['data'] item=item}
        <div class="row">
            <img src="{base_url('img/nophoto.gif')}"/>
            <a href="{site_url('team/detail')}/{$item['id']}">{$item['title']}</a>
        </div>
        {/foreach}
    </div>
    
    <a href="{site_url('team/search')}">更多</a>
</section>

{include file="common/footer.tpl"}