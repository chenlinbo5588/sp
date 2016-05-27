{include file="common/header_main_nav.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			{if !$isMobile}{include file="common/side_nav.tpl"}{/if}
			<div class="contentArea">
				<div class="breadcrumb">{$breadcrumb}</div>
				<div class="bd respond_img" id="articleInfo">
					{$article['article_content']}
				</div>
			</div>
			{if $isMobile}{include file="common/side_nav.tpl"}{/if}
		</div>
	</div>
	
{include file="common/footer.tpl"}