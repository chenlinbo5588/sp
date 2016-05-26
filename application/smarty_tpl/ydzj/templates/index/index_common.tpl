	<div id="homeSwiper">
	    <div class="sliderItem" style="background:url({resource_url('img/cmp/1.jpg')}) no-repeat 50% 0"></div>
        <div class="sliderItem" style="background:url({resource_url('img/cmp/2.jpg')}) no-repeat 50% 0"></div>
        <div class="sliderItem" style="background:url({resource_url('img/cmp/3.jpg')}) no-repeat 50% 0"></div>
        <div class="sliderItem" style="background:url({resource_url('img/cmp/4.jpg')}) no-repeat 50% 0"></div>
	</div>
	<div class="boxz"><div id="homeSwiperPager"></div></div>
	<div class="boxz" style="top:-60px;">
		<div class="searchbar">
			<span class="hotlink">
				<strong>热门搜索:　</strong>
				{foreach from=$hotwords item=item}
				<a href="{site_url('product/plist/')}?keyword={urlencode($item)}">{$item|escape}</a>
				{/foreach}
			</span>
			<div class="searchform clearfix">
				{form_open(site_url('product/plist'),'id="searchForm"')}
				<input type="text" name="keyword" value="" style="width:200px" placeholder="输入产品名称"/><input type="image" name="search" src="{resource_url('img/btns/search_btn.png')}"/>
				</form>
			</div>
		</div>
		<div class="moreInfo">
			<ul class="twoCol clearfix">
				<li class="col">
					<div class="colPanel">
						<h3 class="panelTitel">走进标度</h3>
						<div id="goinSwiper">
					        <div><img src="{resource_url('img/cmp/goin1.jpg')}"/></div>
					        <div><img src="{resource_url('img/cmp/goin2.jpg')}"/></div>
				    	</div>
						<div class="intro" id="goinIntro">
							<p>杭州标度环保技术有限公司，专业生产仪器仪表及精密监测仪器，是一家集科研、生产、销售、售后服务于一体的高新技术企业。</p>
							<p>公司本着“为客户提供最专业、最快速、最实惠、最简便的检测产品及服务”的宗旨，一直在完善产品功能、检测效果，致力于仪器仪表的研讨及开发，短短几年时间，迅速成为国内极具影响力的仪器仪表生产厂商之一.</p>
							<p>作为仪器仪表行业的卓越品牌，公司绝大部分产品都已取得相关证书及专利，很多研发成果在国内都属首例。包括便携式COD消解仪、数显糖度计、水质在线监测设备等。不断的发展中，公司产品已遍布全国各地各个行业：环保局、食品饮料制造行业、农业研究所、城市供水、医疗制药、水产养殖、生物工程、工业水处理等。并远销海外，拥有众多满意客户。</p>
						</div>
					</div>
				</li>
				<li class="col">
					<div class="colPanel newslist">
						<h3 class="panelTitel"><span>企业新闻</span><a class="more fr" href="{site_url('news/news_list?ac_id=15')}">更多&gt;&gt;</a></h3>
						<ol class="clearfix">
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
		            	<div class="pic"><a href="{site_url('product/detail/')}?id={$item['goods_id']}&gc_id={$item['gc_id']}" target="_blank"><img class="respond_img" {if $item['goods_pic']}src="{resource_url('img/cmp/nature1.jpg')}"{else}src="{resource_url('img/default.jpg')}"{/if} title="{$item['goods_name']|escape}"/></a></div>
		            	<div class="title"><a href="{site_url('product/detail/')}?id={$item['goods_id']}&gc_id={$item['gc_id']}" target="_blank">{$item['goods_name']|escape}</a></div>
		            </li>
		            {/foreach}
			    </ul>
			</div>
		</div>
		<div class="friendLinks panelContent">
			<h3><strong>官方店铺</strong></h3>
			<a href="http://www.taobao.com" title="淘宝店铺"><img src="{resource_url('img/taobao.jpg')}" style="width:80px;"/></a>
			<a href="http://www.alibaba.com.cn" title="阿里巴巴"><img src="{resource_url('img/alibaba.jpg')}"/></a>
		</div>
	</div>
	<script type="text/javascript" src="{resource_url('js/jquery.cycle.all.js')}"></script>