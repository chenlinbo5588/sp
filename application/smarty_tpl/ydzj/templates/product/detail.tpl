{include file="common/header_main_nav.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			<form id="detailForm" name="detailForm" action="{site_url('product/plist')}" method="get">
			{include file="./product_side.tpl"}
			<div class="contentArea">
				<div class="breadcrumb"><span>您所在的位置:</span>{$breadcrumb}</div>
				<div class="bd bdlist">
					{if $info}
					<div class="productInfo">
						<div class="album">
							<ul class="bxslider">
							  <li><img src="{resource_url($info['goods_pic'])}" width="500" height="260" /></li>
							  {foreach from=$imgList item=item}
							  <li><img src="{resource_url($item['goods_image'])}" width="500" height="260" /></li>
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
							<div class="fr"><span>上一篇：{if empty($preProduct)}无{else}<a href="{site_url('product/detail/?id=')}{$preProduct['goods_id']}&gc_id={$preProduct['gc_id']}">{$preProduct['goods_name']|escape}</a>{/if}</div>
							<div class="fl"><span>下一篇：{if empty($nextProduct)}无{else}<a href="{site_url('product/detail/?id=')}{$nextProduct['goods_id']}&gc_id={$nextProduct['gc_id']}">{$nextProduct['goods_name']|escape}</a>{/if}</div>
						</div>
					</div>
					{else}
					<div class="errorPage">抱歉，产品不存在 <a href="javascript:history.go(-1);">返回</a></div>
					{/if}
				</div>
			</div>
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
		    slideMargin: 10
		  });
		});
		{/if}
		
	
	</script>
{include file="common/footer.tpl"}