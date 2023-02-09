	<div id="homeSwiper">
		{block_html id="101"}{/block_html}
	</div>
	<div class="boxz"><div id="homeSwiperPager"></div></div>
	<div class="boxz mg5">
		<div class="searchbar clearfix">
			<span class="hotlink">
				<strong>{$hotkeyword}:　</strong>
				{foreach from=$hotwords item=item}
				<a href="{base_url('product/plist.html?keyword='|cat:urlencode($item))}">{$item|escape}</a>
				{/foreach}
			</span>
			<div class="searchform">
				{form_open(base_url('product/plist.html'),'id="searchForm"')}
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
						<h3 class="panelTitel">{$goin} {if $currentLang == 'english'}{$siteSetting['site_shorten']|escape}{else}{$siteSetting['site_shortname']|escape}{/if}</h3>
						<div id="goinSwiper">
							<div><a href="{base_url('about/4_41.html')}"><img src="{resource_url('img/cmp/goin1.jpg')}"/></a></div>
					        <div><a href="{base_url('about/4_41.html')}"><img src="{resource_url('img/cmp/goin2.jpg')}"/></a></div>
					        <div><a href="{base_url('about/4_41.html')}"><img src="{resource_url('img/cmp/goin3.jpg')}"/></a></div>
					        <div><a href="{base_url('about/4_41.html')}"><img src="{resource_url('img/cmp/goin4.jpg')}"/></a></div>
					        <div><a href="{base_url('about/4_41.html')}"><img src="{resource_url('img/cmp/goin5.jpg')}"/></a></div>
						</div>
					</div>
				</li>
				<li class="col" >
					<div class="colPanel newslist bd">
						<h3 class="panelTitel"><span>{$news2}</span><a class="more fr" href="{base_url('cms/plist/11.html')}">{$cm_more}&gt;&gt;</a></h3>
						<ol>
							{foreach from=$qiyeList item=item}
							<li><a href="{base_url('cms/detail/'|cat:$item['id']|cat:'.html')}">{$item['article_title']|escape}</a><span>【{$item['publish_time']|date_format:"%Y-%m-%d"}】</span></li>
							{/foreach}
						</ol>
					</div>
				</li>
			 	<li class="col">
					<div class="colPanel newslist">
						<h3 class="panelTitel"><span>{$news}</span><a class="more fr" href="{base_url('cms/plist/12.html')}">{$cm_more}&gt;&gt;</a></h3>
						<ol class="clearfix">
							{foreach from=$industryList item=item}
							<li><a href="{base_url('cms/detail/'|cat:$item['id']|cat:'.html')}">{$item['article_title']|escape}</a><span>【{$item['publish_time']|date_format:"%Y-%m-%d"}】</span></li>
							{/foreach}
						</ol>
					</div>
				</li>
			</ul>
			<div class="panelContent" id="starProducts">
				<h3 class="panelTitel"><span>{$recommend_product}</span><a class="more fr" href="{site_url('product/plist')}">{$more}&gt;&gt;</a></h3>
				<div class="product pd5">
					<ul id="hotProductSwiper">
						{foreach from=$goodsList item=item}
			            <li>
			            	<div class="pic"><a href="{base_url('product/detail/'|cat:$item['gc_id']|cat:'_'|cat:$item['goods_id']|cat:'.html')}" target="_blank"><img class="respond_img" src="{if $item['goods_pic_m']}{resource_url($item['goods_pic_m'])}{else if $item['goods_pic_b']}{resource_url($item['goods_pic_b'])}{else}{resource_url('img/default.jpg')}{/if}" title="{$item['goods_name']|escape}"/></a></div>
			            	<div class="title"><a href="{base_url('product/detail/'|cat:$item['gc_id']|cat:'_'|cat:$item['goods_id']|cat:'.html')}" target="_blank">{$item['goods_name']|escape}</a></div>
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