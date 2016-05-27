{include file="common/header_main_nav.tpl"}
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
					<table class="liststyle1 productList">
					{foreach from=$list['data'] item=item}
					<tr class="liststyle1Item"><td>
						<a href="{site_url('product/detail/?id=')}{$item['goods_id']}&gc_id={$item['gc_id']}"><img class="previewPic" src="{resource_url($item['goods_pic'])}" alt="{$item['article_title']|escape}"/></a>
						<div class="previewCont">
							<div class="productName"><a target="_blank" href="{site_url('product/detail/?id=')}{$item['goods_id']}&gc_id={$item['gc_id']}">{$item['goods_name']|escape}</a></div>
							<div class="productDetail"><span class="mute">设备介绍:</span>{$item['digest']}</div>
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
			{if $isMobile}{include file="./product_side.tpl"}{/if}
			</form>
		</div>
	</div>
{include file="common/footer.tpl"}