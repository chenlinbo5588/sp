			<div class="sideNav">
				<ul class="sideItem">
					<li class="itemTitle"><h3><a href="{site_url('news/news_list')}">新闻资讯</h3></li>
					{foreach from=$sideNavs item=item key=key}
					<li><a href="{$item}#listmao">{$key}</a></li>
					{/foreach}
				</ul>
				<div class="search sideItem">
					<h3 class="itemTitle">搜索</h3>
					<div><input type="text" name="keyword" value="{$keyword}" placeholder="请输入关键字"/></div>
					<div><input type="submit" class="orangeBtn btn" name="search" value="查询"/></div>
				</div>
			</div>
