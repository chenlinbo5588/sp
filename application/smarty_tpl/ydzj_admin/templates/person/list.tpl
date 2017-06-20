        <table class="table tb-type2 nobdb">
            <thead>
                <tr>
                    <th>{#person_id#}</th>
                    <th>{#village#}</th>
                    <th>{#qlr_name#}</th>
                    <th>{#id_type#}</th>
                    <th>{#id_no#}</th>
                    <th>{#sex#}</th>
                    <th>{#family_num#}</th>
                    <th>{#mobile#}</th>
                    <th>{#address#}</th>
                    <th>{#yhdz#}</th>
                    <th>{#housecnt#}</th>
                    <th>{#total_wjmj#}</th>
                    <th>{#add_username#}<br/>{#gmt_create#}</th>
                    <th>{#edit_username#}<br/>{#gmt_modify#}</th>
                </tr>
            </thead>
	        <tbody>
		        {foreach from=$list item=item}
		        <tr id="row{$item['id']}">
		           <td>{$item['id']}</td>
		           <td>{$item['town']|escape}{$item['village']|escape}</td>
		           <td>{$item['qlr_name']|escape}</td>
		           <td>{$id_type[$item['id_type']]}</td>
		           <td>{$item['id_no']}</td>
		           <td>{$sex_type[$item['sex']]}</td>
		           <td>{$item['family_num']|escape}</td>
		           <td>{$item['mobile']|escape}</td>
		           <td>{$item['address']}</td>
		           <td>{if $item['yhdz'] == 1}是{else}否{/if}</td>
		           <td>{if $item['housecnt'] == 0}<span>暂无</span>{else}<a href="{admin_site_url('house/index?owner_id='|cat:$item['id'])}">{$item['housecnt']}宅</a>{/if}</td>
		           <td>{$item['total_wjmj']}</td>
		           <td>
		           		{$item['add_username']|escape}<br/>
		           		{time_tran($item['gmt_create'])}
		           </td>
		           <td>
		           		{$item['edit_username']|escape}<br/>
		           		{time_tran($item['gmt_modify'])}
		           </td>
		        <tr>
		        {foreachelse}
		        <tr><td colspan="15">找不到相关记录</td></tr>
		        {/foreach}
		    </tbody>
		 </table>