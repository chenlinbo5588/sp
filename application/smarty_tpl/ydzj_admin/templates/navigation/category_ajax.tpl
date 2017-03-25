{foreach from=$list item=item}<tr class="hover edit row{$parentId} row{$item['pid']}" id="row{$item['id']}">
	<td class="w36">
		{if $deep != 2}
		<img fieldid="{$item['id']}" status="open" nc_type="flex" src="{resource_url('img/tv-expandable.gif')}"/>
		{/if}
	</td>
	<td class="w48 sort">
		<span title="可编辑下级分类排序" class="editable tooltip">{$item['displayorder']}</span>
	</td>
	<td class="w50pre name">
		{if $deep == 2}
		<img class="preimg" src="{resource_url('img/vertline.gif')}"/>
		<img fieldid="{$id}" status="none" nc_type="flex" src="{resource_url('img/tv-expandable1.gif')}"/>
		{else}
		<img fieldid="{$item['id']}" status="open" nc_type="flex" src="{resource_url('img/tv-item1.gif')}">
		{/if}
		<span title="可编辑下级分类名称" class="editable tooltip">{$item['name_cn']|escape}</span>
		{if $deep == 1}<a class="btn-add-nofloat marginleft" href="{admin_site_url('navigation/add')}?pid={$item['id']}"><span>新增下级</span></a>{/if}
	</td>
	<td>{str_replace($idReplacement,$item['id'],$item['url_cn'])}</td>
	<td>{if $item['nav_location'] == 1}主导航{else if $item['nav_location'] == 2}底部{/if}</td>
          <td>{if $item['jump_type'] == 0}否{else if $item['jump_type'] == 1}是{/if}</td>
	<td class="w84">
		<a href="{admin_site_url('navigation/edit')}?id={$item['id']}">编辑</a> | <a class="delete" data-url="{admin_site_url('navigation/delete')}" href="javascript:void(0);" data-id="{$item['id']}">删除</a>
	</td>
</tr>{/foreach}