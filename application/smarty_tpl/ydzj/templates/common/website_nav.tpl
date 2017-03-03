			<ul id="homeNav">
       			<li class="level0 {if $currentModule == 'home'}current{/if}"><a class="link0" href="/">首页</a></li>
       			<li class="level0 {if $currentModule == 'about'}current{/if}">
       				<a class="link0" href="{site_url('about/index')}">走进陆恒</a>
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
       					<li><a class="link1" href="{site_url('news/news_list/?ac_id=22#listmao')}">行业动态</a></li>
       					{*<li><a class="link1" href="{site_url('news/news_list/?ac_id=17#listmao')}">促销信息</a></li>*}
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
       			</li>
       			<li class="level0">
       				<a class="link0" href="{site_url('market/agency')}">营销招商</a>
       				<ul class="sublist">
       					<li><a class="link1" href="{site_url('market/agency')}">经销招商</a></li>
       					<li><a class="link1" href="{site_url('market/cooperation')}">运营特点</a></li>
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