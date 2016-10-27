        <table class="fulltable">
            <thead>
                <tr>
                    <th>{#goods_id#}</th>
                    <th>{#goods_code#}</th>
                    <th>{#goods_name#}</th>
                    <th>{#goods_color#}</th>
                    <th>{#goods_size#}</th>
                    <th>{#quantity#}</th>
                    <th>{#sex#}</th>
                    <th>求货价格</th>
                    <th>{#owner#}</th>
                    <th>{#need#}{#send_zone#}</th>
                    <th>{#send_day#}</th>
                    <th>{#mtime#}</th>
                </tr>
            </thead>
	        <tbody>
		        {foreach from=$list item=item}
		        <tr>
		           <td>{$item['goods_id']}</td>
		           <td>{$item['goods_code']|escape}</td>
		           <td>{$item['goods_name']|escape}</td>
		           <td>{$item['goods_color']|escape}</td>
		           <td>{$item['goods_csize']}</td>
		           <td>{$item['quantity']}</td>
		           <td>{if $item['sex'] == 1}男{else}女{/if}</td>
		           <td>{if $item['price_status']}{$item['price_max']}{else}***{/if}</td>
		           <td>
		              {if $item['qq']}
		              <a target="_blank" class="qqchat" href="http://wpa.qq.com/msgrd?v=3&uin={$item['qq']}&site=qq&menu=yes" alt="点击这里给我发消息" title="点击这里给我发消息">{$item['qq']}</a>
		              <span>QQ:{$item['qq']}</span>
		              {elseif $item['mobile']}
		              <span>手机:{$item['mobile']}</span>
		              {else}
		              {$item['uid']}
		              {/if}
		           </td>
		           <td>{$item['send_zone']|escape}</td>
		           <td>{if $item['send_day'] != 0}{$item['send_day']|date_format:"%Y-%m-%d"}{/if}</td>
		           <td>{time_tran($item['gmt_modify'])}</td>
		        <tr>
		        {foreachelse}
		        <tr><td colspan="12">找不到相关记录</td></tr>
		        {/foreach}
		    </tbody>
		 </table>