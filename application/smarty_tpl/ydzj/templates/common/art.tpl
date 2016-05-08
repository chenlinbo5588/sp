{include file="common/header.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			<ul class="sideNav">
				<li class="first"><h3><a href="javascript:void(0);">{$sideTitle}</a></h3></li>
				{foreach from=$sideNavs item=item key=key}
				<li><a href="{$item}">{$key}</a></li>
				{/foreach}
			</ul>
			<div class="contentArea">
				<div class="breadcrumb">{$breadcrumb}</div>
				<div class="bd">
					{$article['article_content']}
				</div>
			</div>
		</div>
	</div>
	<script>
		$(function(){
			
		});
	</script>
{include file="common/footer.tpl"}