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
		           <td><a href="{site_url('budongchan/edit?id='|cat:$currentBdcList[$item['bdc_id']]['id'])}">{$currentBdcList[$item['bdc_id']]['lsno']}</a></td>
		           <td><a href="{site_url('budongchan/edit?id='|cat:$currentBdcList[$item['bdc_id']]['id'])}">{$currentBdcList[$item['bdc_id']]['name']|escape}</a></td>
		           <td>{$id_type[$currentBdcList[$item['bdc_id']]['id_type']]}</td>
		           <td>{$currentBdcList[$item['bdc_id']]['id_no']}</td>
		           <td>{$currentBdcList[$item['bdc_id']]['mobile']|escape}</td>
		           <td>{$currentBdcList[$item['bdc_id']]['address']}</td>
		           <td>{$currentBdcList[$item['bdc_id']]['status_name']}【{if $currentBdcList[$item['bdc_id']]['cur_uid']}已{else}未{/if}受理】</td>
		           <td>
		           		<div>{$currentBdcList[$item['bdc_id']]['cur_dept_sname']|escape}</div>
		           		{if $currentBdcList[$item['bdc_id']]['cur_uid']}
		           		<div>{$currentBdcList[$item['bdc_id']]['cur_username']|escape}</div>
		           		{/if}
		           </td>
		           <td>
		           		<div>{$currentBdcList[$item['bdc_id']]['add_username']|escape}</div>
		           		<div>{time_tran($currentBdcList[$item['bdc_id']]['gmt_create'])}</div>
		           </td>
		           <td>
		           		{$currentBdcList[$item['bdc_id']]['edit_username']|escape}</div>
		           		{if $currentBdcList[$item['bdc_id']]['gmt_modify']}
		           		<div>{time_tran($currentBdcList[$item['bdc_id']]['gmt_modify'])}</div>
		           		{/if}
		           </td>
		           <td>
		           		{*<a href="{site_url('budongchan/edit?id='|cat:$currentBdcList[$item['bdc_id']]['id'])}">编辑</a>*}
		           		<a href="javascript:void(0);" class="delete" data-id="{$currentBdcList[$item['bdc_id']]['id']}" data-title="{$currentBdcList[$item['bdc_id']]['name']|escape}以及关联建筑信息" data-url="{site_url('budongchan/delete')}">删除</a>
		           </td>
		        <tr>
		        {foreachelse}
		        <tr><td colspan="11">找不到相关记录</td></tr>
		        {/foreach}
		    </tbody>
		 </table>