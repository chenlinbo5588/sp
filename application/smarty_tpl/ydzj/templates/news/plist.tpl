{include file="common/website_header.tpl"}
	<div class="linePg">
		<div class="commonPg {$pgClass}"></div>
		<div class="boxz clearfix mg10">
			<form id="listForm" name="listForm" action="{base_url('news/plist.html')}" method="get">
			<input type="hidden" name="page" value="{$currentPage}"/>
			{if !$isMobile}{include file="./news_side.tpl"}{/if}
			<div class="contentArea">
				<div class="breadcrumb"><span>您所在的位置:</span>{$breadcrumb}</div>
				<div class="bd bdlist">
					<a name="listmao"></a>
					<ul class="newsList clearfix">
					{foreach from=$list['data'] item=item}
					<li>
						<h4><a href="{$item['jump_url']}">{$item['article_title']|escape}</a></h4>
						<span class="pubtime">【{$item['publish_time']|date_format:"%Y-%m-%d"}】</span>
					</li>
					{foreachelse}
					<li>没有找到符合得记录</li>
					{/foreach}
					</ul>
				</div>
				{include file="common/pagination.tpl"}
			</div>
			{if $isMobile}{include file="./news_side.tpl"}{/if}
			</form>
		</div>
	</div>
{include file="common/website_footer.tpl"}