{include file="common/website_header.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			<form id="detailForm" name="detailForm" action="{site_url('news/news_list')}" method="get">
			{if !$isMobile}{include file="./news_side.tpl"}{/if}
			<div class="contentArea">
				<div class="breadcrumb"><span>您所在的位置:</span>{$breadcrumb}</div>
				<div class="bd bdlist">
					{if $info}
					<div class="articleHeader">
						<h1>{$info['article_title']|escape}</h1>
						<div><em class="mute">浏览次数:</em><strong>{$info['article_click']}</strong><em class="mute">日期:</em><strong>{$info['gmt_create']|date_format:"%Y-%m-%d"}</strong></div>
					</div>
					<div class="articleContent">
						{$info['content']}
					</div>
					
					<div class="articleRelate clearfix">
						<div class="prevArticle"><span>上一篇：{if empty($preArticle)}无{else}<a href="{$preArticle['article_url']}">{$preArticle['article_title']|escape}</a>{/if}</div>
						<div class="nextArticle"><span>下一篇：{if empty($nextArticle)}无{else}<a href="{$nextArticle['article_url']}">{$nextArticle['article_title']|escape}</a>{/if}</div>
					</div>
					{else}
					<div class="errorPage">抱歉，文章不存在 <a href="javascript:history.go(-1);">返回</a></div>
					{/if}
				</div>
			</div>
			</form>
			{if $isMobile}{include file="./news_side.tpl"}{/if}
		</div>
	</div>
{include file="common/website_footer.tpl"}