        <table class="table tb-type2 nobdb">
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
                    <th>{#wf_wjsj#}<br/>{#wf_ydmj#}</th>
                    <th>{#deal_way#}</th>
                    <th>{#op#}</th>
                </tr>
            </thead>
	        <tbody>
		        {foreach from=$list item=item}
		        <tr id="row{$item['hid']}">
		           <td>{$item['hid']}</td>
		           <td>{$item['owner_name']|escape}</td>
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
		           <td>{$item['wf_wjsj']}<br/>{$item['wf_ydmj']}<br/>{$item['wf_jzzdmj']}<br/>{$item['wf_jzmj']}</td>
		           <td>{$dealWayList[$item['deal_way']]}</td>
		           <td>
		           		<a href="{admin_site_url('house/detail?hid='|cat:$item['hid'])}">浏览</a>
		           </td>
		        <tr>
		        {foreachelse}
		        <tr><td colspan="20">找不到相关记录</td></tr>
		        {/foreach}
		    </tbody>
		 </table>