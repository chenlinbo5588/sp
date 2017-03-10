{include file="common/website_header.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			<form id="listForm" name="listForm" action="{site_url('product/plist')}" method="get">
			<input type="hidden" name="page" value="{$currentPage}"/>
			<input type="hidden" name="gc_id" value="{$currentGcId}"/>
			{if !$isMobile}{include file="./product_side.tpl"}{/if}
			<div class="contentArea">
				<div class="breadcrumb"><span>您所在的位置:</span>{$breadcrumb}</div>
				<div class="bd bdlist">
					<a name="listmao"></a>
					<ul class="liststyle1 productList clearfix">
					{foreach from=$list['data'] item=item}
					<li class="liststyle1Item">
						<a class="priviewLink" href="{site_url('product/detail/?id=')}{$item['goods_id']}&gc_id={$item['gc_id']}"><img class="previewPic" src="{if $item['goods_pic_m']}{resource_url($item['goods_pic_m'])}{else if $item['goods_pic_b']}{resource_url($item['goods_pic_b'])}{else}{resource_url($item['goods_pic'])}{/if}" alt="{$item['article_title']|escape}"/></a>
						<div class="previewCont">
							<h4 class="productName"><a href="{site_url('product/detail/?id=')}{$item['goods_id']}&gc_id={$item['gc_id']}">{$item['goods_name']|escape}</a></h4>
							<p class="productDetail mute">{$item['digest']}</p>
						</div>
					</li>
					{foreachelse}
						<li>没有找到符合得记录</li>
					{/foreach}
					</ul>
				</div>
				{include file="common/pagination.tpl"}
			</div>
			{if $isMobile}{include file="./product_side.tpl"}{/if}
			</form>
		</div>
	</div>
{include file="common/website_footer.tpl"}