        <table class="fulltable bordered">
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
                    <th>{#total_ydmj#}<br/>{#total_jzzdmj#}<br/>{#total_jzmj#}</th>
                    <th>{#add_username#}<br/>{#gmt_create#}</th>
                    <th>{#edit_username#}<br/>{#gmt_modify#}</th>
                    <th>{#op#}</th>
                </tr>
            </thead>
	        <tbody>
		        {foreach from=$list item=item}
		        <tr id="row{$item['id']}">
		           <td>{$item['id']}</td>
		           <td>{$item['town']|escape}{$item['village']|escape}</td>
		           <td><a href="{site_url('person/edit?id='|cat:$item['id'])}">{$item['qlr_name']|escape}</a></td>
		           <td>{$id_type[$item['id_type']]}</td>
		           <td>{$item['id_no']}</td>
		           <td>{$sex_type[$item['sex']]}</td>
		           <td>{$item['family_num']|escape}</td>
		           <td>{$item['mobile']|escape}</td>
		           <td>{$item['address']}</td>
		           <td>{if $item['yhdz'] == 1}是{else}否{/if}</td>
		           <td>{if $item['housecnt'] == 0}<span>暂无</span>{else}<a href="{site_url('house/index?owner_id='|cat:$item['id'])}">{$item['housecnt']}宅</a>{/if} | <a href="{site_url('house/add?owner_id='|cat:$item['id'])}">添加建筑</a></td>
		           <td>
		           		<div>{$item['total_ydmj']}</div>
		           		<div>{$item['total_jzzdmj']}</div>
		           		<div>{$item['total_jzmj']}</div>
		           </td>
		           <td>
		           		{$item['add_username']|escape}<br/>
		           		{time_tran($item['gmt_create'])}
		           </td>
		           <td>
		           		{$item['edit_username']|escape}<br/>
		           		{time_tran($item['gmt_modify'])}
		           </td>
		           <td>
		           		<a href="{site_url('person/edit?id='|cat:$item['id'])}">编辑</a>
		           		<a href="javascript:void(0);" class="delete" data-id="{$item['id']}" data-title="{$item['qlr_name']|escape}以及关联建筑信息" data-url="{site_url('person/delete')}">删除</a>
		           </td>
		        <tr>
		        {foreachelse}
		        <tr><td colspan="15">找不到相关记录</td></tr>
		        {/foreach}
		    </tbody>
		 </table>