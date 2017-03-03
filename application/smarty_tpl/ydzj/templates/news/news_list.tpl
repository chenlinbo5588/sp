{include file="common/website_header.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			<form id="listForm" name="listForm" action="{site_url('news/news_list')}" method="get">
			<input type="hidden" name="page" value="{$currentPage}"/>
			<input type="hidden" name="ac_id" value="{$currentAcId}"/>
			{if !$isMobile}{include file="./news_side.tpl"}{/if}
			<div class="contentArea">
				<div class="breadcrumb"><span>您所在的位置:</span>{$breadcrumb}</div>
				<div class="bd bdlist">
					<a name="listmao"></a>
					<table class="liststyle1 newsList">
					{foreach from=$list['data'] item=item}
					<tr class="liststyle1Item"><td>
						<a href="{$item['article_url']}"><img class="previewPic" src="{$item['article_pic']}" alt="{$item['article_title']|escape}"/></a>
						<div class="previewCont">
							<div><a href="{$item['article_url']}">{$item['article_title']|escape}</a></div>
							<div>发布时间：{$item['gmt_create']|date_format:"%Y-%m-%d"}</div>
							<div>{$item['article_digest']}</div>
						</div>
					</td></tr>
					{foreachelse}
					<tr>
						<td>没有找到符合得记录</td>
					</td>
					{/foreach}
					</table>
					{include file="common/pagination.tpl"}
				</div>
			</div>
			{if $isMobile}{include file="./news_side.tpl"}{/if}
			</form>
		</div>
	</div>
{include file="common/website_footer.tpl"}