			<aside>
				<div class="sideNav">
					<h3 class="itemTitle"><a href="{base_url('product/plist.html')}">{$productSideTitle}</a></h3>
					<ul class="sideItemWrap">
						{foreach from=$sideNavs item=item key=key}
						<li class="oneItem{if $item == $currentSideUrl} current{/if}">
							<a class="level0" href="{base_url('product/plist/'|cat:$item['gc_id']|cat:'.html')}">{$item['name']|escape}</a>
							{if $item['children']}
							{foreach from=$item['children'] item=subItem key=subKey}
							<a class="level1" href="{base_url('product/plist/'|cat:$subItem['gc_id']|cat:'.html')}">{$subItem['name']|escape}</a>
							{/foreach}
							{/if}
						</li>
						{/foreach}
					</ul>
					<div class="search sideItemWrap">
						<h3 class="itemTitle">{$search_product}</h3>
						<div><input type="text" name="keyword" value="{$keyword}" placeholder="{$input_keyword}"/></div>
						<div><input type="submit" class="orangeBtn btn" name="search" value="{$cm_search}"/></div>
					</div>
				</div>
			</aside>