{include file="common/header.tpl"}

<section>
    <form name="searchForm" action="{site_url('stadium')}" method="get">
    <div class="searchbar pd5 clearfix">
        <label for="seach_key" class="fl">搜索场馆</label>
        <input type="text" class="fl" style="width:50%;" name="search_key" value="{$smarty.get.search_key|escape}" placeholder="请输入队伍名称"/>
        <input class="master_btn fl"  style="width:20%;" type="submit" value="查询"/>
    </div>
    </form>
</section>

<section>
	<div id="stadiumList" class="list pd5">
	    {foreach from=$list['data'] item=item}
	    <div class="team clearfix">
	        <div class="team_avatar fl"><a href="{site_url('stadium/detail/'|cat:$item['stadium_id'])}"><img src="{resource_url($item['avatar_middle'])}"/></a></div>
	        <div class="team_basic fl">
	            <ul>
	                <li><a class="team_title" href="{site_url('stadium/detail/'|cat:$item['stadium_id'])}">{$item['title']|escape}</a></li>
	                <li><label>权属：</label><span>{$item['open_type']}</li>
	                <li><label>地址：</label>
	                {if $cityLevel == 0}
	                <span>{$item['dname1']}{$item['dname2']}{$item['dname3']}{$item['dname4']}</span>
	                {elseif $cityLevel == 1}
	                <span>{$item['dname2']}{$item['dname3']}{$item['dname4']}</span>
	                {elseif $cityLevel == 2}
	                <span>{$item['dname3']}{$item['dname4']}</span>
	                {elseif $cityLevel == 3}
	                <span>{$item['dname4']}</span>
	                {elseif $cityLevel == 4}
	                <span>{$item['dname4']}{$item['street_number']}</span>
	                {/if}
	                </li>
	                <li><label>联系人：</label>{$item['contact']|escape}</li>
	                <li><label>手机号：</label>{if $item['mobile']}{$item['mobile']}{else}{$item['tel']}{/if}</li>
	                <li>{$stadium['basic']['stadium_type']} {$stadium['basic']['ground_type']} {$stadium['basic']['charge_type']}</li>
	            </ul>
	        </div>
	        {*
	        <div class="order_game">
	            {if $item['can_booking'] == 'y'}
	            <a class="link_btn greenBtn" href="{site_url('stadium/booking')}">预定</a>
	            {else}
	            <a class="link_btn grayed" href="javascript:void(0);">未开放预定</a>
	            {/if}
	        </div>
	        *}
	    </div>
	    {/foreach}
	</div>
</section>
{include file="common/footer.tpl"}