{include file="my/my_header.tpl"}
    <div>
        <form action="{site_url('goods/index')}" method="get" id="formSearch">
        <input type="hidden" name="page" value=""/>
        <div class="searcharea">
            <label><span>货品名称</span><input type="text" name="name" value="{$smarty.get.name}" placeholder="货品名称"></label>
            <label><span>货号</span><input type="text" name="code" value="{$smarty.get.code}" placeholder="货号"></label>
            <label><span>尺码</span><input type="text" name="size" value="{$smarty.get.size}" placeholder="尺寸(CN)"></label>
            <label><span>剩余数量</span><input type="text" name="cnum" value="{$smarty.get.size}" placeholder="剩余数量"></label>
            <input class="action" type="submit" name="search" value="查询"/>
        </div>
        <div class="warning">用户在线沟通成交后，请<strong>发货方</strong>请点击成交链接完成交易.为了平台的信息的准确性和实时性。请在交易完成后及时点击完成交易。</div>
        <table class="fulltable">
            <thead>
                <tr>
                    <th>货品名称</th>
                    <th>货号</th>
                    <th>尺码(CN)</th>
                    <th>求购数量(剩余)</th>
                    <th>发布人</th>
                    <th>发布时间</th>
                    <th>最后修改时间</th>
                    <th>操作</th>
                </tr>
            </thead>
	        <tbody>
		        {foreach from=$list['data'] item=item}
		        <tr>
		           <td>{$item['goods_name']|escape}</td>
		           <td>{$item['goods_code']|escape}</td>
		           <td>{$item['goods_size']}</td>
		           <td>{$item['quantity']}&nbsp;({$item['cnum']})</td>
		           <td>
		              {if $userList[$item['uid']]['qq']}
		              <a target="_blank" class="qqchat" href="http://wpa.qq.com/msgrd?v=3&uin={$userList[$item['uid']]['qq']}&site=qq&menu=yes" alt="点击这里给我发消息" title="点击这里给我发消息">{$userList[$item['uid']]['nickname']}</a>
		              {else}
		              {$userList[$item['uid']]['mobile']}
		              {/if}
		           </td>
		           <td>{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
		           <td>{$item['gmt_modify']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
		           <td>
		              {if $profile && $profile['basic']['uid'] == $item['uid']}
		              <a href="javascript:void(0)">修改</a>
		              <a href="javascript:void(0)">删除</a>
		              {/if}
		              <a href="javascript:void(0);">点击完成交易</a>
		              <a href="javascript:void(0);" data-url="{site_url('trade/goods_done')}">复制链接</a>
		           </td>
		        <tr>
		        {/foreach}
		    </tbody>
		 </table>
	     <div>{include file="common/pagination.tpl"}</div>
	    
	    </form>
    </div>
    
    <div></div>
{include file="my/my_footer.tpl"}

