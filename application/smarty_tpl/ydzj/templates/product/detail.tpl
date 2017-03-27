{include file="common/website_header.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			<form id="detailForm" name="detailForm" action="{base_url('product/plist')}" method="get">
			{if !$isMobile}{include file="./product_side.tpl"}{/if}
			<div class="contentArea">
				<div class="breadcrumb"><span>{$your_position}:</span>{$breadcrumb}</div>
				<div class="bd bdlist">
				    <article>
					{if $info}
					<div class="productInfo">
						<div class="album" {if !$isMobile}style="width:500px;"{/if}>
							<ul class="bxslider">
							  {if $info['goods_pic']}<li><img src="{if $info['goods_pic_b']}{resource_url($info['goods_pic_b'])}{else}{resource_url($info['goods_pic'])}{/if}" width="500" height="400" /></li>{/if}
							  {foreach from=$imgList item=item}
							  <li><img src="{if $item['goods_image_b']}{resource_url($item['goods_image_b'])}{else}{resource_url($item['goods_image'])}{/if}" width="500" height="400" /></li>
							  {/foreach}
							</ul>
						</div>
						<div class="textDesc">
							<div><em class="mute">{$product_name}:</em><strong>{$info['goods_name']|escape}</strong></div>
							<div><em class="mute">{$product_no}:</em><strong class="goodscode">{$info['goods_code']|escape}</strong></div>
							<div><em class="mute">{$visit_count}:</em><strong>{$info['goods_click']}</strong></div>
							<div><em class="mute">{$online_date}:</em><strong>{$info['gmt_create']|date_format:"%Y-%m-%d"}</strong></div>
							{include file="common/baidu_share.tpl"}
						</div>
						
						<div class="articleContent">
							{$info['goods_intro']}
						</div>
						<div class="articleRelate clearfix">
							<div class="fr"><span>{$prev_product}：{if empty($preProduct)}{$cm_none}{else}<a href="{$preProduct['url']}">{$preProduct['goods_name']|escape}</a>{/if}</div>
							<div class="fl"><span>{$next_product}：{if empty($nextProduct)}{$cm_none}{else}<a href="{$nextProduct['url']}">{$nextProduct['goods_name']|escape}</a>{/if}</div>
						</div>
					</div>
					{else}
					<div class="errorPage">{$not_found_msg}<a href="javascript:history.go(-1);">{$cm_goback}</a></div>
					{/if}
					</article>
				</div>
			</div>
			{if $isMobile}{include file="./product_side.tpl"}{/if}
			</form>
		</div>
	</div>
	{include file="common/bxslider.tpl"}
	<script>
		{if $imgList}
		$(document).ready(function(){
		  $('.bxslider').bxSlider({
		    slideWidth: 500,
		    minSlides: 1,
		    slideMargin: 10,
		    pager:false
		  });
		});
		{/if}
	</script>
{include file="common/website_footer.tpl"}