{foreach from=$list item=item}<tr class="hover edit row{$parentId} row{$item['pid']}" id="row{$item['id']}">
	<td class="w36">
		{if $deep != 3}
		<img fieldid="{$item['id']}" status="open" nc_type="flex" src="{resource_url('img/tv-expandable.gif')}"/>
		{/if}
	</td>
	<td class="w120 sort">
		<span title="可编辑下级分类排序" class="editable deep{$deep}" data-id="{$item['id']}" data-fieldname="displayorder">{$item['displayorder']}</span>
	</td>
	<td class="w50pre name">
		{if $deep == 3}
		<img class="preimg" src="{resource_url('img/vertline.gif')}"/>
		<img fieldid="{$id}" status="none" nc_type="flex" src="{resource_url('img/tv-expandable1.gif')}"/>
		{else}
		{str_repeat('&nbsp;&nbsp;',$deep)}
		<img fieldid="{$item['id']}" status="open" nc_type="flex" src="{resource_url('img/tv-item1.gif')}">
		{/if}
		<span title="可编辑下级分类名称" class="editable" data-id="{$item['id']}" data-fieldname="show_name">{$item['show_name']|escape}</span>
		{if $deep != 3}<a class="btn-add-nofloat marginleft" href="{admin_site_url($moduleClassName|cat:'/add')}?pid={$item['id']}"><span>新增下级</span></a>{/if}
	</td>
	<td></td>
	<td class="w84">
		<a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a> | <a class="delete" data-url="{admin_site_url($moduleClassName|cat:'/delete')}" href="javascript:void(0);" data-id="{$item['id']}">删除</a>
	</td>
</tr>{/foreach}