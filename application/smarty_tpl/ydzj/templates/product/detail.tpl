{include file="common/website_header.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			<form id="detailForm" name="detailForm" action="{base_url('product/plist')}" method="get">
			{if !$isMobile}{include file="./product_side.tpl"}{/if}
			<div class="contentArea">
				<div class="breadcrumb"><span>您所在的位置:</span>{$breadcrumb}</div>
				<div class="bd bdlist">
					{if $info}
					<div class="productInfo">
						<div class="album" {if !$isMobile}style="width:500px;"{/if}>
							<ul class="bxslider">
							  <li><img src="{if $info['goods_pic_b']}{resource_url($info['goods_pic_b'])}{else}{resource_url($info['goods_pic'])}{/if}" width="500" height="280" /></li>
							  {foreach from=$imgList item=item}
							  <li><img src="{if $item['goods_image_b']}{resource_url($item['goods_image_b'])}{else}{resource_url($item['goods_image'])}{/if}" width="500" height="280" /></li>
							  {/foreach}
							</ul>
						</div>
						<div class="textDesc">
							<div><em class="mute">产品名称:</em><strong>{$info['goods_name']|escape}</strong></div>
							<div><em class="mute">产品编号:</em><strong class="goodscode">{$info['goods_code']|escape}</strong></div>
							<div><em class="mute">浏览次数:</em><strong>{$info['goods_click']}</strong></div>
							<div><em class="mute">上架时间:</em><strong>{$info['gmt_create']|date_format:"%Y-%m-%d"}</strong></div>
						</div>
						<div class="articleContent">
							{$info['goods_intro']}
						</div>
						<div class="articleRelate clearfix">
							<div class="fr"><span>上一个产品：{if empty($preProduct)}无{else}<a href="{$preProduct['url']}">{$preProduct['goods_name']|escape}</a>{/if}</div>
							<div class="fl"><span>下一个产品：{if empty($nextProduct)}无{else}<a href="{$nextProduct['url']}">{$nextProduct['goods_name']|escape}</a>{/if}</div>
						</div>
					</div>
					{else}
					<div class="errorPage">抱歉，产品不存在 <a href="javascript:history.go(-1);">返回</a></div>
					{/if}
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