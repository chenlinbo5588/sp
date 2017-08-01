        <table class="fulltable bordered">
            <thead>
                <tr>
                    <th>{#cm_lsno#}</th>
                    <th>{#bdc_name#}</th>
                    <th>{#cm_id_type#}</th>
                    <th>{#cm_id_no#}</th>
                    <th>{#cm_mobile#}</th>
                    <th>{#cm_address#}</th>
                    <th>{#cm_status#}</th>
                    <th>当前经办</th>
                    <th>{#cm_add_username#}<br/>{#cm_gmt_create#}</th>
                    <th>{#cm_edit_username#}<br/>{#cm_gmt_modify#}</th>
                    <th>{#cm_op#}</th>
                </tr>
            </thead>
	        <tbody>
		        {foreach from=$list item=item}
		        <tr id="row{$item['id']}">
		           <td><a href="{site_url('budongchan/edit?id='|cat:$item['id'])}">{$item['lsno']}</a></td>
		           <td><a href="{site_url('budongchan/edit?id='|cat:$item['id'])}">{$item['name']|escape}</a></td>
		           <td>{$id_type[$item['id_type']]}</td>
		           <td>{$item['id_no']}</td>
		           <td>{$item['mobile']|escape}</td>
		           <td>{$item['address']}</td>
		           <td>{$item['status']}</td>
		           <td>
		           		{$item['cur_dept_sname']|escape}<br/>
		           		{$item['cur_username']|escape}
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
		           		<a href="{site_url('budongchan/edit?id='|cat:$item['id'])}">编辑</a>
		           		<a href="javascript:void(0);" class="delete" data-id="{$item['id']}" data-title="{$item['name']|escape}以及关联建筑信息" data-url="{site_url('budongchan/delete')}">删除</a>
		           </td>
		        <tr>
		        {foreachelse}
		        <tr><td colspan="11">找不到相关记录</td></tr>
		        {/foreach}
		    </tbody>
		 </table>