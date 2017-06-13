{include file="common/website_header.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			<form id="detailForm" name="detailForm" action="{base_url('cms/plist.html')}" method="get">
			{if !$isMobile}{include file="./cms_side.tpl"}{/if}
			<div class="contentArea">
				<div class="breadcrumb"><span>{$your_position}:</span>{$breadcrumb}</div>
				<div class="bd bdlist">
				    <article>
					{if $info}
					<div class="articleHeader">
						{include file="common/baidu_share.tpl"}
						<h1>{if $currentLang == 'english' && $info['article_title_en']}{$info['article_title_en']|escape}{else}{$info['article_title']|escape}{/if}</h1>
						<div><em class="mute">{$visit_count}:</em><strong>{$info['article_click']}</strong><em class="mute">{$online_date}:</em><strong>{$info['gmt_create']|date_format:"%Y-%m-%d"}</strong></div>
						<span></span>
					</div>
					<div class="articleContent">
						{if $currentLang == 'english' && $info['content_en']}{$info['content_en']}{else}{$info['content']}{/if}
					</div>
					
					<div class="articleRelate clearfix">
						<div class="prevArticle"><span>{$prev_article}：{if empty($preArticle)}{$cm_none}{else}<a href="{$preArticle['article_url']}">{if $currentLang == 'english' && $preArticle['article_title_en']}{$preArticle['article_title_en']|escape}{else}{$preArticle['article_title']|escape}{/if}</a>{/if}</div>
						<div class="nextArticle"><span>{$next_article}：{if empty($nextArticle)}{$cm_none}{else}<a href="{$nextArticle['article_url']}">{if $currentLang == 'english' && $nextArticle['article_title_en']}{$nextArticle['article_title_en']|escape}{else}{$nextArticle['article_title']|escape}{/if}</a>{/if}</div>
					</div>
					{else}
					<div class="errorPage">{$not_found_msg}<a href="javascript:history.go(-1);">{$cm_goback}</a></div>
					{/if}
					
					</article>
				</div>
			</div>
			</form>
			{if $isMobile}{include file="./cms_side.tpl"}{/if}
		</div>
	</div>
	
{include file="common/website_footer.tpl"}