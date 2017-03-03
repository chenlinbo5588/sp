	
	<div id="homeSwiper">
	    <div class="sliderItem" style="background:url({resource_url('static/attach/2017/03/02/20150429184803483.jpg')}) no-repeat 50% 0"></div>
	    <div class="sliderItem" style="background:url({resource_url('static/attach/2017/03/02/201504291847294729.jpg')}) no-repeat 50% 0"></div>
	    <div class="sliderItem" style="background:url({resource_url('static/attach/2017/03/02/201702161640144014.jpg')}) no-repeat 50% 0"></div>
	    <div class="sliderItem" style="background:url({resource_url('static/attach/2017/03/02/201702161640474047.jpg')}) no-repeat 50% 0"></div>
	</div>
	<div class="boxz"><div id="homeSwiperPager"></div></div>
	<div class="boxz mg10">
		<div class="searchbar clearfix">
			<span class="hotlink">
				<strong>热门搜索:　</strong>
				{foreach from=$hotwords item=item}
				<a href="{site_url('product/plist/')}?keyword={urlencode($item)}">{$item|escape}</a>
				{/foreach}
			</span>
			<div class="searchform">
				{form_open(site_url('product/plist'),'id="searchForm"')}
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
						{if !$isMobile}
						{include file="./index_goin.tpl"}
						{include file="./index_intro.tpl"}
						{else}
						{include file="./index_intro.tpl"}
						{include file="./index_goin.tpl"}
						{/if}
					</div>
				</li>
				<li class="col" >
					<div class="colPanel newslist bd">
						<h3 class="panelTitel"><span>企业新闻</span><a class="more fr" href="{site_url('news/news_list?ac_id=15')}">更多&gt;&gt;</a></h3>
						<ol>
							{foreach from=$qiyeList item=item}
							<li><a href="{site_url('news/detail/')}?id={$item['article_id']}">{$item['article_title']|escape}</a><span>{$item['article_time']|date_format:"%Y-%m-%d"}</span></li>
							{/foreach}
							{*
							<li><a href="/news/detail/?id=39">积极备战2015泰国国际塑胶展</a><span>2016-09-09</span></li>
							<li><a href="/news/detail/?id=39">通佳集团将亮相第十四届亚太国际塑胶工业展览会</a><span>2016-09-09</span></li>
							<li><a href="/news/detail/?id=39">新起点，新面貌，通佳机械全力备战第118届广交会</a><span>2016-09-09</span></li>
							<li><a href="/news/detail/?id=39">中国塑料机械行业的影响力在逐渐提高</a><span>2016-09-09</span></li>
							<li><a href="/news/detail/?id=39">领航智能装备制造，助力中国塑机腾飞</a><span>2016-09-09</span></li>
							<li><a href="/news/detail/?id=39">新闻标题1</a><span>2016-09-09</span></li>
							*}
						</ol>
					</div>
				</li>
			 	<li class="col">
					<div class="colPanel newslist">
						<h3 class="panelTitel"><span>行业动态</span><a class="more fr" href="{site_url('news/news_list?ac_id=16')}">更多&gt;&gt;</a></h3>
						<ol class="clearfix">
							{foreach from=$industryList item=item}
							<li><a href="{site_url('news/detail/')}?id={$item['article_id']}">{$item['article_title']|escape}</a><span>{$item['article_time']|date_format:"%Y-%m-%d"}</span></li>
							{/foreach}
							{*
							<li><a href="/news/detail/?id=39">2015年越南国际塑胶工业展，秣马厉兵，通佳人准备</a><span>2016-09-09</span></li>
							<li><a href="/news/detail/?id=39">积极备战2015泰国国际塑胶展</a><span>2016-09-09</span></li>
							<li><a href="/news/detail/?id=39">通佳集团将亮相第十四届亚太国际塑胶工业展览会</a><span>2016-09-09</span></li>
							<li><a href="/news/detail/?id=39">新起点，新面貌，通佳机械全力备战第118届广交会</a><span>2016-09-09</span></li>
							<li><a href="/news/detail/?id=39">中国塑料机械行业的影响力在逐渐提高</a><span>2016-09-09</span></li>
							<li><a href="/news/detail/?id=39">领航智能装备制造，助力中国塑机腾飞</a><span>2016-09-09</span></li>
							<li><a href="/news/detail/?id=39">新闻标题1</a><span>2016-09-09</span></li>
							*}
						</ol>
					</div>
				</li>
			</ul>
			<div class="panelContent" id="starProducts">
				<h3><strong>推荐产品</strong></h3>
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
		{*
		<div class="friendLinks panelContent">
			<h3><strong>官方店铺</strong></h3>
			<a href="http://www.taobao.com" title="淘宝店铺"><img class="nature" src="{resource_url('img/taobao.jpg')}" style="width:80px;"/></a>
			<a href="http://www.alibaba.com.cn" title="阿里巴巴"><img class="nature" src="{resource_url('img/alibaba.jpg')}"/></a>
		</div>
		*}
	</div>