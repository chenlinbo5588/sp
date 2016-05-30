   	<div id="topBar" class="clearfix">
       <div class="boxz clearfix">
       		<div id="siteLogo"><a href="/"><img src="{resource_url('img/cmp/logo.jpg')}" style=""/></a></div>
       		<div id="siteContacts">
           		<a href="/" title="首页">您好,欢迎访问{config_item('site_name')}官方网站</a>
           		{*<a href="javascript:void(0);">Tel: {$siteSetting['site_phone']|escape}</a>
           		<a href="mailto:{$siteSetting['email_addr']}">E-mail: {$siteSetting['email_addr']|escape}</a>*}
           		{if $isMobile}
           		<a href="javascript:void(0);" id="naviText">导航</a>
           		{/if}
       		</div>
       	</div>
       	
       	<div class="boxz clearfix">
       		<ul id="homeNav">
       			<li class="level0"><a class="link0" href="/">首页</a></li>
       			<li class="level0">
       				<a class="link0" href="{site_url('about/index')}">走进标度</a>
       				<ul class="sublist">
       					<li><a class="link1" href="{site_url('about/index')}">企业简介</a></li>
       					<li><a class="link1" href="{site_url('about/thinking')}">公司理念</a></li>
       					<li><a class="link1" href="{site_url('about/moreintro')}">企业风采</a></li>
       				</ul>
       			</li>
       			<li class="level0">
       				<a class="link0" href="{site_url('news/news_list')}">新闻资讯</a>
       				<ul class="sublist">
       					<li><a class="link1" href="{site_url('news/news_list/?ac_id=15#listmao')}">企业新闻</a></li>
       					<li><a class="link1" href="{site_url('news/news_list/?ac_id=16#listmao')}">行业动态</a></li>
       					<li><a class="link1" href="{site_url('news/news_list/?ac_id=17#listmao')}">促销信息</a></li>
       				</ul>
       			</li>
       			<li class="level0">
       				<a class="link0" href="{site_url('product/plist')}">产品中心</a>
       				<ul class="sublist">
       					{foreach from=$goodsList item=item}
       					<li class="level1">
       						<a class="link1" href="{site_url('product/detail/')}?id={$item['goods_id']}&gc_id={$item['gc_id']}">{$item['goods_name']|escape}</a>
       					</li>
       					{/foreach}
       				</ul>
       				{*
       				<ul class="sublist clearfix" id="menuProducts">
       					<li class="level1">
       						<div><a href="{site_url('news/news_list')}"><img src="{resource_url('img/cmp/product.jpg')}"/><div>系列1</div></a></div>
       						<ol class="serialProduct">
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       						</ol>
       					</li>
       					<li class="level1">
       						<div><a href="/"><img src="{resource_url('img/cmp/product.jpg')}"/><div>系列1</div></a></div>
       						<ol class="serialProduct">
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       						</ol>
       					</li>
       					<li class="level1">
       						<div><a href="/"><img src="{resource_url('img/cmp/product.jpg')}"/><div>系列1</div></a></div>
       						<ol class="serialProduct">
       							<li><a href="">PE多层气dd垫膜生产线</a></li>
       							<li><a href="">PE多层气垫dsd膜生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层气垫膜vv生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层气垫vv膜生产线</a></li>
       						</ol>
       					</li>
       					<li class="level1">
       						<div><a href="/"><img src="{resource_url('img/cmp/product.jpg')}"/><div>系列1</div></a></div>
       						<ol class="serialProduct">
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层sds气垫膜生产线</a></li>
       							<li><a href="">PE多asd层气垫膜生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       						</ol>
       					</li>
       					<li class="level1">
       						<div><a href="/"><img src="{resource_url('img/cmp/product.jpg')}"/><div>系列1</div></a></div>
       						<ol class="serialProduct">
       							<li><a href="">PE多层d气垫膜生产线</a></li>
       							<li><a href="">PE多层气ss垫膜生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层dd气垫膜生产线</a></li>
       							<li><a href="">PE多层气垫膜生产线</a></li>
       							<li><a href="">PE多层ss气垫膜生产线</a></li>
       						</ol>
       					</li>
       				</ul>
       				*}
       			</li>
       			<li class="level0">
       				<a class="link0" href="{site_url('market/agency')}">营销招商</a>
       				<ul class="sublist">
       					<li><a class="link1" href="{site_url('market/agency')}">经销商网络</a></li>
       					<li><a class="link1" href="{site_url('market/cooperation')}">合作加盟</a></li>
       				</ul>
       			</li>
       			<li class="level0">
       				<a class="link0" href="{site_url('service/customer')}">服务中心</a>
       				<ul class="sublist">
       					<li><a class="link1" href="{site_url('service/customer')}">客户服务</a></li>
       					<li><a class="link1" href="{site_url('doc/product_list')}">产品资料</a></li>
       				</ul>
       			</li>
       			<li class="level0">
       				<a class="link0" href="{site_url('contacts/index')}">联系我们</a>
       				<ul class="sublist">
       					<li><a class="link1" href="{site_url('contacts/customer_service')}">售后中心</a></li>
       					<li><a class="link1" href="{site_url('contacts/merchants_telephone')}">招商电话</a></li>
       					<li><a class="link1" href="{site_url('contacts/suggestion')}">投诉建议</a></li>
       					<li><a class="link1" href="{site_url('contacts/map')}">在线地图</a></li>
       				</ul>
       			</li>
       		</ul>
       </div>
   </div>
       
