{include file="common/header_main_nav.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			<form id="listForm" name="listForm" action="{site_url('news/doc_list')}" method="get">
			<input type="hidden" name="page" value="{$currentPage}"/>
			<input type="hidden" name="ac_id" value="{$currentAcId}"/>
			{include file="news/news_side.tpl"}
			<div class="contentArea">
				<div class="breadcrumb"><span>您所在的位置:</span>{$breadcrumb}</div>
				<div class="bd bdlist">
					<a name="listmao"></a>
					<table class="liststyle1">
					{foreach from=$list['data'] item=item}
					<tr class="liststyle1Item"><td>
						<span>文件: </span><a href="{$item['article_url']}">{$item['article_title']|escape}</a>
						<strong>发布时间：{$item['gmt_create']|date_format:"%Y-%m-%d"}</strong>
					</td
					></tr>
					{foreachelse}
					<tr>
						<td>没有找到符合得记录</td>
					</td>
					{/foreach}
					</table>
					{include file="common/pagination.tpl"}
				</div>
			</div>
			</form>
		</div>
	</div>
{include file="common/footer.tpl"}