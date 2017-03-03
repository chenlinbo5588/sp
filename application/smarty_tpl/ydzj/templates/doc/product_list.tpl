{include file="common/website_header.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			<form id="listForm" name="listForm" action="{site_url('doc/product_list')}" method="get">
			<input type="hidden" name="page" value="{$currentPage}"/>
			<input type="hidden" name="ac_id" value="{$currentAcId}"/>
			{if !$isMobile}{include file="news/news_side.tpl"}{/if}
			<div class="contentArea">
				<div class="breadcrumb"><span>您所在的位置:</span>{$breadcrumb}</div>
				<div class="bd bdlist">
					<a name="listmao"></a>
					<ul class="filelist">
					{foreach from=$list['data'] item=item}
					{if $inMobile}
						<li class="fileitem">
							<span class="filename"><em>文件:</em><strong>{$item['article_title']|escape}</strong></span>
						</li>
						<li>
							<span class="filetype"><em>类型:</em><strong>{if $fileAssoc[$item['article_id']]}{$fileAssoc[$item['article_id']][0]['file_ext']}{/if}</strong></span>
							<span class="filesize"><em>大小:</em><strong>{if $fileAssoc[$item['article_id']]}{byte_format($fileAssoc[$item['article_id']][0]['file_size'])}{/if}</strong></span>
						</li>
						<li>{if $fileAssoc[$item['article_id']]}<a class="downlink" href="{resource_url($fileAssoc[$item['article_id']][0]['file_url'])}">点击下载</a>{/if}
							{*<span class="pubtime"><em>点击：</em><strong>{$item['gmt_create']|date_format:"%Y-%m-%d"}</strong></span>*}
						</li>
					{else}
						<li class="fileitem">
							<span class="filename"><em>文件:</em><strong>{$item['article_title']|escape}</strong></span>
							<span class="filetype"><em>类型:</em><strong>{if $fileAssoc[$item['article_id']]}{$fileAssoc[$item['article_id']][0]['file_ext']}{/if}</strong></span>
							<span class="filesize"><em>大小:</em><strong>{if $fileAssoc[$item['article_id']]}{byte_format($fileAssoc[$item['article_id']][0]['file_size'])}{/if}</strong></span>
							{if $fileAssoc[$item['article_id']]}<a class="downlink" href="{resource_url($fileAssoc[$item['article_id']][0]['file_url'])}">点击下载</a>{/if}
							{*<span class="pubtime"><em>点击：</em><strong>{$item['gmt_create']|date_format:"%Y-%m-%d"}</strong></span>*}
						</li>
					{/if}
					{foreachelse}
					<li>没有找到符合得记录</li>
					{/foreach}
					</ul>
					{include file="common/pagination.tpl"}
				</div>
			</div>
			{if $isMobile}{include file="news/news_side.tpl"}{/if}
			</form>
		</div>
	</div>
{include file="common/website_footer.tpl"}