{include file="common/website_header.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			<form id="listForm" name="listForm" action="{base_url('product/plist.html')}" method="get">
			<input type="hidden" name="page" value="{$currentPage}"/>
			
			{if !$isMobile}{include file="./product_side.tpl"}{/if}
			<div class="contentArea">
				<div class="breadcrumb"><span>{$your_position}:</span>{$breadcrumb}</div>
				<div class="bd bdlist">
					<a name="listmao"></a>
					<ul class="liststyle1 productList clearfix">
					{foreach from=$list['data'] item=item}
					<li class="liststyle1Item">
						<a class="priviewLink" href="{base_url('product/detail/'|cat:$item['gc_id']|cat:'_'|cat:$item['goods_id']|cat:'.html')}"><img class="previewPic" src="{if $item['goods_pic_m']}{resource_url($item['goods_pic_m'])}{else if $item['goods_pic_b']}{resource_url($item['goods_pic_b'])}{else}{resource_url($item['goods_pic'])}{/if}" alt="{$item['article_title']|escape}"/></a>
						<div class="previewCont">
							<h4 class="productName"><a href="{base_url('product/detail/'|cat:$item['gc_id']|cat:'_'|cat:$item['goods_id']|cat:'.html')}">{$item['goods_name']|escape}</a></h4>
							<p class="productDetail mute">{$item['digest']}</p>
						</div>
					</li>
					{foreachelse}
						<li>{$not_found}</li>
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