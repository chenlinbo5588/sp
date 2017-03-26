{include file="common/website_header.tpl"}
	<div class="linePg">
		<div class="commonPg {$pgClass}"></div>
		<div class="boxz clearfix mg10">
			{if !$isMobile}{include file="common/side_nav.tpl"}{/if}
			<div class="contentArea">
				<div class="breadcrumb">{$breadcrumb}</div>
				<div class="bd {if $isMobile}respond_img{/if}" id="articleInfo">
				    <article>
					{$article['article_content']}
					</article>
				</div>
			</div>
			{if $isMobile}{include file="common/side_nav.tpl"}{/if}
		</div>
	</div>
	
{include file="common/website_footer.tpl"}