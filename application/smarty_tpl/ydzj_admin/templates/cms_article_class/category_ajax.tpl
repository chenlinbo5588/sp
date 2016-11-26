{foreach from=$list item=item}<tr class="hover edit row{$parentId} row{$item['pid']}" id="row{$item['id']}">
	<td class="w36">
		{if $deep != 2}
		<img fieldid="{$item['id']}" status="open" nc_type="flex" src="{resource_url('img/tv-expandable.gif')}"/>
		{/if}
	</td>
	<td class="w48 sort">
		<span title="可编辑下级分类排序" class="editable tooltip">{$item['ac_sort']}</span>
	</td>
	<td class="w50pre name">
		{if $deep == 2}
		<img class="preimg" src="{resource_url('img/vertline.gif')}"/>
		<img fieldid="{$id}" status="none" nc_type="flex" src="{resource_url('img/tv-expandable1.gif')}"/>
		{else}
		<img fieldid="{$item['id']}" status="open" nc_type="flex" src="{resource_url('img/tv-item1.gif')}">
		{/if}
		<span title="可编辑下级分类名称" class="editable tooltip">{$item['name']|escape}</span>
		{if $deep != 2}<a class="btn-add-nofloat marginleft" href="{admin_site_url('cms_article_class/add')}?pid={$item['id']}"><span>新增下级</span></a>{/if}
	</td>
	<td></td>
	<td class="w84">
		<a href="{admin_site_url('cms_article_class/edit')}?id={$item['id']}">编辑</a> | <a class="delete" href="javascript:void(0);" data-id="{$item['id']}">删除</a>
	</td>
</tr>{/foreach}