{include file="common/my_header.tpl"}
    {config_load file="lab.conf"}
      
    <form action="{site_url($uri_string)}" method="post" id="formSearch">
        <input type="hidden" name="page" value=""/>
        <table class="fulltable style1">
            <thead>
                <tr>
                    <th>序号</th>
                    <th>名称</th>
                    <th>是否当前</th>
                    <th>加入时间</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$list key=key item=item}
                <tr>
                   <td><input name="id" type="radio" {if $item['is_default'] == 1}checked{/if} value="{$item['oid']}">{$item['oid']|escape}</td>
                   <td>{$item['name']|escape}</td>
                   <td>{if $item['is_default'] == 1}是{else}否{/if}</td>
                   <td>{time_tran($item['gmt_create'])}</td>
                <tr>
                {foreachelse}
                <tr><td colspan="6">找不到相关记录</td></tr>
                {/foreach}
            </tbody>
            <tfoot>
	        <tr>
	            <td colspan="6">
	                <input type="submit" class="master_btn" name="tijiao" value="切换"/>
	            </td>
	        </tr>
	      </tfoot>
        </table>
        <div>{include file="common/pagination.tpl"}</div>
    </form>
{include file="common/my_footer.tpl"}
