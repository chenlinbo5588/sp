{include file="common/header_main_nav.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			<div class="sideNav">
				<ul class="sideItem">
					<li class="itemTitle"><h3><a href="{site_url('about/index')}">{$sideTitle}</a></h3></li>
					{foreach from=$sideNavs item=item key=key}
					<li><a href="{$item}">{$key}</a></li>
					{/foreach}
				</ul>
			</div>
			<div class="contentArea">
				<div class="breadcrumb">{$breadcrumb}</div>
				<div class="bd respond_img" id="articleInfo">
					{$article['article_content']}
				</div>
			</div>
		</div>
	</div>
	
{include file="common/footer.tpl"}