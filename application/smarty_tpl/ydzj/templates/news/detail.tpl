{include file="common/website_header.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			<form id="detailForm" name="detailForm" action="{site_url('news/news_list')}" method="get">
			{if !$isMobile}{include file="./news_side.tpl"}{/if}
			<div class="contentArea">
				<div class="breadcrumb"><span>{$your_position}:</span>{$breadcrumb}</div>
				<div class="bd bdlist">
					{if $info}
					<div class="articleHeader">
						<h1>{$info['article_title']|escape}</h1>
						<div><em class="mute">{$visit_count}:</em><strong>{$info['article_click']}</strong><em class="mute">{$online_date}:</em><strong>{$info['gmt_create']|date_format:"%Y-%m-%d"}</strong></div>
					</div>
					<div class="articleContent">
						{$info['content']}
					</div>
					
					<div class="articleRelate clearfix">
						<div class="prevArticle"><span>{$prev_article}：{if empty($preArticle)}{$none}{else}<a href="{$preArticle['article_url']}">{$preArticle['article_title']|escape}</a>{/if}</div>
						<div class="nextArticle"><span>{$next_article}：{if empty($nextArticle)}{$none}{else}<a href="{$nextArticle['article_url']}">{$nextArticle['article_title']|escape}</a>{/if}</div>
					</div>
					{else}
					<div class="errorPage">{$not_found_msg}<a href="javascript:history.go(-1);">{$goback}</a></div>
					{/if}
				</div>
			</div>
			</form>
			{if $isMobile}{include file="./news_side.tpl"}{/if}
		</div>
	</div>
{include file="common/website_footer.tpl"}