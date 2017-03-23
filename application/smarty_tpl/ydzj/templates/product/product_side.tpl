			<div class="sideNav">
				<ul class="sideItem">
					<li class="itemTitle"><h3><a href="{site_url('product/plist')}">{$sideTitle}</a></h3></li>
					{foreach from=$sideNavs item=item key=key}
					<li class="oneItem{if $item == $currentSideUrl} current{/if}"><a href="{$item}">{$key}</a></li>
					{/foreach}
				</ul>
				<div class="search sideItem">
					<h3 class="itemTitle">{$search_product}</h3>
					<div><input type="text" name="keyword" value="{$keyword}" placeholder="{$input_keyword}"/></div>
					<div><input type="submit" class="orangeBtn btn" name="search" value="{$cm_search}"/></div>
				</div>
			</div>