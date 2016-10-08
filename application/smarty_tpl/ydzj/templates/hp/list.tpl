        <table class="fulltable">
            <thead>
                <tr>
                    <th>求货序号</th>
                    <th>{#goods_code#}</th>
                    <th>{#goods_name#}</th>
                    <th>{#goods_color#}</th>
                    <th>{#goods_size#}</th>
                    <th>{#quantity#}</th>
                    <th>{#sex#}</th>
                    <th>{#price_max#}</th>
                    <th>发布人</th>
                    <th>发布时间</th>
                    <th>最后更新时间</th>
                    <th>操作</th>
                </tr>
            </thead>
	        <tbody>
		        {foreach from=$list item=item}
		        <tr>
		           <td>{$item['goods_id']}</td>
		           <td>{$item['goods_code']|escape}</td>
		           <td>{$item['goods_name']|escape}</td>
		           <td>{$item['goods_color']|escape}</td>
		           <td>{$item['goods_size']}</td>
		           <td>{$item['quantity']}</td>
		           <td>{if $item['sex'] == 1}男{else}女{/if}</td>
		           <td>{$item['price_max']}</td>
		           <td>
		              {if $userList[$item['uid']]['qq']}
		              <a target="_blank" class="qqchat" href="http://wpa.qq.com/msgrd?v=3&uin={$userList[$item['uid']]['qq']}&site=qq&menu=yes" alt="点击这里给我发消息" title="点击这里给我发消息">{$userList[$item['uid']]['qq']}</a>
		              <span>QQ:{$userList[$item['uid']]['qq']}</span>
		              {elseif $userList[$item['uid']]['mobile']}
		              <span>手机:{$userList[$item['uid']]['mobile']}</span>
		              {else}
		              {$item['uid']}
		              {/if}
		           </td>
		           <td>{time_tran($item['gmt_create'])}</td>
		           <td>{time_tran($item['gmt_modify'])}</td>
		           <td>
		              {if $profile && $profile['basic']['uid'] == $item['uid']}
		              <a href="javascript:void(0)">修改</a>
		              <a href="javascript:void(0)">删除</a>
		              {/if}
		           </td>
		        <tr>
		        {foreachelse}
		        <tr><td colspan="12">找不到相关记录</td></tr>
		        {/foreach}
		    </tbody>
		 </table>