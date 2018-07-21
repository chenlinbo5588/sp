			<aside>
			<div class="sideNav">
				<ul class="sideItemWrap">
					{foreach from=$sideNavs item=item key=key}
					<li class="oneItem{if $item == $currentSideUrl} current{/if}">
						<a class="itemTitle" href="{base_url('cms/plist/'|cat:$item['id']|cat:'.html')}">{$item['name']|escape}</a>
						{if !empty($item['children'])}
						{foreach from=$item['children'] item=subItem key=subKey}
						<a class="level1" href="{base_url('cms/plist/'|cat:$subItem['id']|cat:'.html')}">{$subItem['name_en']|escape}</a>
						{/foreach}
						{/if}
					</li>
					{/foreach}
				</ul>
				<div class="search sideItemWrap">
					<h3 class="itemTitle">{$cm_search}</h3>
					<div><input type="text" name="keyword" value="{$keyword}" placeholder="{$input_keyword}"/></div>
					<div><input type="submit" class="orangeBtn btn" name="search" value="{$cm_search}"/></div>
				</div>
			</div>
			</aside>
