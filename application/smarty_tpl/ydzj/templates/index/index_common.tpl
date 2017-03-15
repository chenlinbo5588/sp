	
	<div id="homeSwiper">
	    <div class="sliderItem" style="background:url({resource_url('static/attach/2017/03/02/20150429184803483.jpg')}) no-repeat 50% 0"></div>
	    <div class="sliderItem" style="background:url({resource_url('static/attach/2017/03/02/201504291847294729.jpg')}) no-repeat 50% 0"></div>
	    <div class="sliderItem" style="background:url({resource_url('static/attach/2017/03/02/201702161640144014.jpg')}) no-repeat 50% 0"></div>
	    <div class="sliderItem" style="background:url({resource_url('static/attach/2017/03/02/201702161640474047.jpg')}) no-repeat 50% 0"></div>
	</div>
	<div class="boxz"><div id="homeSwiperPager"></div></div>
	<div class="boxz mg5">
		<div class="searchbar clearfix">
			<span class="hotlink">
				<strong>热门搜索:　</strong>
				{foreach from=$hotwords item=item}
				<a href="{base_url('product/plist/keyword/'|cat:urlencode($item))}">{$item|escape}</a>
				{/foreach}
			</span>
			<div class="searchform">
				{form_open(base_url('product/plist'),'id="searchForm"')}
				<input type="text" name="keyword" class="txt" value="" placeholder="输入产品名称"/><input type="image" name="search" src="{resource_url('img/btns/search_btn.png')}"/>
				</form>
			</div>
		</div>
	</div>
	<div class="boxz bdwrap">
		<div class="moreInfo">
			<ul class="threeCol clearfix">
				<li class="col">
					<div class="colPanel">
						<h3 class="panelTitel">走进{config_item('site_name')}</h3>
						{include file="./index_goin.tpl"}
					</div>
				</li>
				<li class="col" >
					<div class="colPanel newslist bd">
						<h3 class="panelTitel"><span>企业新闻</span><a class="more fr" href="{site_url('news/plist/acid/11')}">更多&gt;&gt;</a></h3>
						<ol>
							{foreach from=$qiyeList item=item}
							<li><a href="{base_url('news/detail/'|cat:$item['article_id'])}">{$item['article_title']|escape}</a><span>【{$item['publish_time']|date_format:"%Y-%m-%d"}】</span></li>
							{/foreach}
						</ol>
					</div>
				</li>
			 	<li class="col">
					<div class="colPanel newslist">
						<h3 class="panelTitel"><span>行业动态</span><a class="more fr" href="{base_url('news/plist/acid/12')}">更多&gt;&gt;</a></h3>
						<ol class="clearfix">
							{foreach from=$industryList item=item}
							<li><a href="{base_url('news/detail/'|cat:$item['article_id'])}">{$item['article_title']|escape}</a><span>【{$item['publish_time']|date_format:"%Y-%m-%d"}】</span></li>
							{/foreach}
						</ol>
					</div>
				</li>
			</ul>
			<div class="panelContent" id="starProducts">
				<h3 class="panelTitel"><span>推荐产品</span><a class="more fr" href="{site_url('product/plist')}">更多&gt;&gt;</a></h3>
				<div class="product pd5">
					<ul id="hotProductSwiper">
						{foreach from=$goodsList item=item}
			            <li>
			            	<div class="pic"><a href="{site_url('product/detail/')}?id={$item['goods_id']}&gc_id={$item['gc_id']}" target="_blank"><img class="respond_img" src="{if $item['goods_pic_m']}{resource_url($item['goods_pic_m'])}{else if $item['goods_pic_b']}{resource_url($item['goods_pic_b'])}{else}{resource_url('img/default.jpg')}{/if}" title="{$item['goods_name']|escape}"/></a></div>
			            	<div class="title"><a href="{site_url('product/detail/')}?id={$item['goods_id']}&gc_id={$item['gc_id']}" target="_blank">{$item['goods_name']|escape}</a></div>
			            </li>
			            {/foreach}
				    </ul>
			    </div>
			</div>
		</div>
		{*
		<div class="friendLinks panelContent">
			<h3><strong>官方店铺</strong></h3>
			<a href="http://www.taobao.com" title="淘宝店铺"><img class="nature" src="{resource_url('img/taobao.jpg')}" style="width:80px;"/></a>
			<a href="http://www.alibaba.com.cn" title="阿里巴巴"><img class="nature" src="{resource_url('img/alibaba.jpg')}"/></a>
		</div>
		*}
	</div>