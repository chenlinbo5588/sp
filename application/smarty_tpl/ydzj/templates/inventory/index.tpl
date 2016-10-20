{include file="common/my_header.tpl"}
    {config_load file="hp.conf"}
    {if $currentGroupId == 2}
    <div class="panel pd20 warnbg">
        <span>当前库存不可用,您的账户尚未进行卖家审核,<a class="warning" href="{site_url('my/seller_verify')}">马上去认证</a></span>
    </div>
    {else}
        {if $currentHpCnt == 0}
        {include file="./import.tpl"}
        {else}
        <div class="w-tixing clearfix"><b>温馨提醒：</b>
            <p>库存更新通过导入文件方式更新. <a class="hightlight" href="{site_url('inventory/import')}">马上去更新库存</a></p>
          </div>
        <form action="{site_url($uri_string)}" method="get" id="formSearch">
	        <input type="hidden" name="page" value=""/>
	        <table class="fulltable">
	            <thead>
	                <tr>
	                	<th>{#goods_code#}</th>
	                    <th>{#goods_name#}</th>
	                    <th>{#goods_color#}</th>
	                    <th>{#goods_size#}</th>
	                    <th>{#sex#}</th>
	                    <th>{#inventorynum#}</th>
	                    <th>{#price_min#}</th>
	                </tr>
	            </thead>
	            <tbody>
	                {foreach from=$list key=key item=item}
	                <tr>
	                   <td>{$item['goods_code']|escape}</td>
	                   <td>{$item['goods_name']|escape}</td>
	                   <td>{$item['goods_color']|escape}</td>
	                   <td>{$item['goods_size']}</td>
	                   <td>{$item['sex']|escape}</td>
	                   <td>{$item['quantity']}</td>
	                   <td>{$item['price_min']}</td>
	                <tr>
	                {foreachelse}
	                <tr><td colspan="14">找不到相关记录</td></tr>
	                {/foreach}
	            </tbody>
	        </table>
		    <div>{include file="common/pagination.tpl"}</div>
	    </form>
        {/if}
    {/if}
{include file="common/my_footer.tpl"}
