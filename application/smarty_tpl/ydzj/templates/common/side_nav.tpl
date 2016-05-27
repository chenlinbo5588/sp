			<div class="sideNav">
				<ul class="sideItem">
					<li class="itemTitle"><h3><a href="{$sideTitleUrl}">{$sideTitle}</a></h3></li>
					{foreach from=$sideNavs item=item key=key}
					<li><a href="{$item}">{$key}</a></li>
					{/foreach}
				</ul>
			</div>