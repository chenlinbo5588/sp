{include file="common/my_header.tpl"}
    {config_load file="hp.conf"}
    <div class="tabin_screen">
        <ul class="clearfix">
            <li {if $uri_string == 'my_req/recent'}class="scr_cur"{/if}><a href="{site_url('my_req/recent')}">最近3日内求货</a></li>
            <li {if $uri_string == 'my_req/history'}class="scr_cur"{/if}><a href="{site_url('my_req/history')}">历史求货</a></li>
        </ul>
        
    </div>
    <form action="{site_url($uri_string)}" method="get" id="formSearch">
        <input type="hidden" name="page" value=""/>
        <div class="goods_search">
             <ul class="search_con clearfix">
                <li>
                    <label class="ftitle">{#pub_date#}</label>
                    <input type="text" name="sdate" class="w72 datepicker" value="{if $smarty.get.sdate}{$smarty.get.sdate}{/if}" placeholder="日期开始"/>
                    <input type="text" name="edate" class="w72 datepicker" value="{if $smarty.get.edate}{$smarty.get.edate}{/if}" placeholder="日期结束"/>
                </li>
                <li>
                    <input class="master_btn" type="submit" name="search" value="查询"/>
                </li>
             </ul>
	        <table class="fulltable">
	            <thead>
	                <tr>
	                	<th class="w60"><label><input type="checkbox" class="checkall" name="hpid" />{#goods_id#}</label></th>
	                	<th>{#goods_code#}</th>
	                    <th>{#goods_name#}</th>
	                    <th>{#goods_color#}</th>
	                    <th>{#goods_size#}</th>
	                    <th>{#quantity#}</th>
	                    <th>{#sex#}</th>
	                    <th>{#accept#}{#price_max#}</th>
	                    <th>{#status#}</th>
	                    <th>{#pub_date#}</th>
	                    <th>{#need#}{#send_zone#}</th>
                        <th>{#need#}{#send_day#}</th>
	                    <th>{#mtime#}</th>
	                </tr>
	            </thead>
	            <tbody>
	                {foreach from=$list item=item}
	                <tr id="row{$item['goods_id']}">
	                   <td><label><input type="checkbox" name="id[]" group="hpid" value="{$item['goods_id']}"/>{$item['goods_id']}</label></td>
	                   <td>{$item['goods_code']|escape}</td>
	                   <td>{$item['goods_name']|escape}</td>
	                   <td>{$item['goods_color']|escape}</td>
	                   <td>{$item['goods_size']}</td>
	                   <td>{$item['quantity']}</td>
	                   <td>{if $item['sex'] == 1}男{else}女{/if}</td>
	                   <td>{$item['price_max']}</td>
	                   <td>{if ($reqtime - $item['gmt_modify']) >= 5400}<span class="warning">已过期</span>{else}正常{/if}</td>
	                   <td>{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M"}</td>
	                   <td>{$item['send_zone']|escape}</td>
	                   <td>{$item['send_day']|date_format:"%Y-%m-%d"}</td>
	                   <td>{time_tran($item['gmt_modify'])}</td>
	                <tr>
	                {foreachelse}
	                <tr><td colspan="14">找不到相关记录</td></tr>
	                {/foreach}
	            </tbody>
	        </table>
	        <tfoot>
		      <tr>
		          <td colspan="12">
		              <div class="pd5">
		              <input type="button" class="action repub" name="repub" value="加入重新发布" />
		              <input type="button" class="action deleteBtn" data-checkbox="id[]" data-title="删除" data-url="{site_url('my_req/delete?source=history')}" name="delete" value="删除" />
		              </div>
		          </td>
		      </tr>
		    </tfoot>
		    <div>{include file="common/pagination.tpl"}</div>
	    </div>
    </form>
    <script type="text/javascript" src="{resource_url('js/jquery-ui/i18n/zh-CN.js')}"></script>
    <script type="text/javascript" src="{resource_url('js/my/history.js')}"></script>
{include file="common/my_footer.tpl"}
