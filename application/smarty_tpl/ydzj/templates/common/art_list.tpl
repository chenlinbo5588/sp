{include file="common/header.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			<form id="listForm" name="listForm" action="{site_url('news/news_list')}" method="get">
			<input type="hidden" name="page" value="{$currentPage}"/>
			<input type="hidden" name="id" value="{$currentId}"/>
			<div class="sideNav">
				<ul class="sideItem">
					<li class="itemTitle"><h3><a href="{site_url('news/news_list')}">{$sideTitle}</h3></li>
					{foreach from=$sideNavs item=item key=key}
					<li><a href="{$item}#listmao">{$key}</a></li>
					{/foreach}
				</ul>
				<div class="search sideItem">
					<h3 class="itemTitle">新闻搜索</h3>
					<div><input type="text" name="keyword" value="{$keyword}" placeholder="请输入关键字"/></div>
					<div><input type="submit" class="orangeBtn btn" name="search" value="查询"/></div>
				</div>
			</div>
			<div class="contentArea">
				<div class="breadcrumb"><span>您所在的位置:</span>{$breadcrumb}</div>
				<div class="bd bdlist">
					<table class="liststyle1">
					{foreach from=$list['data'] item=item}
					<tr class="liststyle1Item"><td>
						<a href="{$item['url']}"><img class="previewPic" src="{$item['title_img']}" alt="{$item['article_title']|escape}"/></a>
						<div class="previewCont">
							<div><a href="{$item['url']}">{$item['article_title']|escape}</a></div>
							<div>发布时间：{$item['gmt_create']|date_format:"%Y-%m-%d"}</div>
							<div>{$item['digest']}</div>
						</div>
					</td></tr>
					{foreachelse}
					<tr>
						<td>没有找到符合得记录</td>
					</td>
					{/foreach}
					</table>
					{include file="common/pagination.tpl"}
					<a name="listmao"></a>
				</div>
			</div>
			</form>
		</div>
	</div>
{include file="common/footer.tpl"}