        <table class="fulltable bordered">
            <thead>
                <tr>
                    <th>{#person_id#}</th>
                    <th>{#qlr_name#}</th>
                    <th>{#id_no#}</th>
                    <th>房屋坐落{#village#}</th>
                    <th>房屋坐落{#address#}</th>
                    <th>{#land_no#}</th>
                    <th>{#zddh#}</th>
                    <th>{#land_oa#}</th>
                    <th>{#land_cate#}</th>
                    <th>{#jzw_ydmj#}</th>
                    <th>{#jzw_jzzdmj#}</th>
                    <th>{#jzw_jzmj#}</th>
                    <th>{#jzw_plies#}</th>
                    <th>{#jzw_jg#}</th>
                    <th>{#sp_new#}<br/>{#sp_ycyj#}</th>
                    <th>{#sp_ydmj#}<br/>{#sp_jzmj#}</th>
                    <th>{#illegal#}</th>
                    <th>{#wf_wjsj#}<br/>{#wf_wjmj#}</th>
                    <th>{#deal_way#}</th>
                    <th>{#op#}</th>
                </tr>
            </thead>
	        <tbody>
		        {foreach from=$list item=item}
		        <tr id="row{$item['hid']}">
		           <td>{$item['hid']}</td>
		           <td><a href="{site_url('person/edit?id='|cat:$item['owner_id'])}">{$item['owner_name']|escape}</a></td>
		           <td>{$item['id_no']|escape}</td>
		           <td>{$item['village']|escape}</td>
		           <td>{$item['address']|escape}</td>
		           <td>{$item['land_no']|escape}</td>
		           <td>{$item['zddh']|escape}</td>
		           <td>{if $item['land_oa'] == 1}集体{elseif $item['land_oa'] == 2}国有{/if}</td>
		           <td>{$item['land_cate']|escape}</td>
		           <td>{$item['jzw_ydmj']}</td>
		           <td>{$item['jzw_jzzdmj']}</td>
		           <td>{$item['jzw_jzmj']}</td>
		           <td>{$item['jzw_plies']}</td>
		           <td>{$item['jzw_jg']|escape}</td>
		           <td>{$item['sp_new']}<br/>{$item['sp_ycyj']}</td>
		           <td>{$item['sp_ydmj']}<br/>{$item['sp_jzmj']}</td>
		           <td>{$illegalList[$item['illegal']]}</td>
		           <td>{$item['wf_wjsj']}<br/>{$item['wf_wjmj']}</td>
		           <td>{$dealWayList[$item['deal_way']]}</td>
		           <td>
		           		<a href="{site_url('house/edit?hid='|cat:$item['hid'])}">编辑</a>
		           		<a href="javascript:void(0);" class="delete" data-id="{$item['hid']}" data-title="当前记录" data-url="{site_url('house/delete')}">删除</a>
		           </td>
		        <tr>
		        {foreachelse}
		        <tr><td colspan="20">找不到相关记录</td></tr>
		        {/foreach}
		    </tbody>
		 </table>