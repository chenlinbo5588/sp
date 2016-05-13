<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>{$SEO_title}</title>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="{$SEO_description}" />
<meta name="keywords" content="{$SEO_keywords}" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no,minimal-ui">
<link rel="stylesheet" href="{resource_url('css/site.css')}"/>
<script type="text/javascript" src="{resource_url('js/jquery.js')}"></script>
<script>
var cookiedomain = "",
    cookiepath = "{config_item('cookie_path')}",
    cookiepre = "{config_item('cookie_prefix')}",
    formhash = "{$formhash}",
    SITEURL = '{base_url()}',
    cityUrl = "{site_url('district/index/')}",
    LOADING_IMAGE = "{resource_url('img/loading/loading.gif')}";
</script>
<script type="text/javascript" src="{resource_url('js/common.js')}"></script>
<!--[if lte IE 9]>
<script type="text/javascript" src="{resource_url('js/html5shiv.js')}"></script>
<![endif]-->
<script type="text/javascript" src="{resource_url('js/respond.min.js')}"></script>

</head>
<body {if $bodyClass}class="{$bodyClass}"{/if}>
   <div id="wrap">
   	<div id="topBar" class="clearfix">
       <div class="boxz clearfix">
       		<div class="fl" id="siteLogo"></div>
       		<div class="fr">
           		<a href="/" title="首页">您好,欢迎访问 {config_item('site_name')}</a>
           		<a href="/">Tel: ＋86-13792366525</a>
           		<a href="/">E-mail: sales@tongjia.com</a>
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
       			</li>
       			<li class="level0">
       				<a class="link0" href="javascript:void(0)">营销招商</a>
       				<ul class="sublist">
       					<li><a class="link1" href="/">经销商网络</a></li>
       					<li><a class="link1" href="/">合作加盟</a></li>
       				</ul>
       			</li>
       			<li class="level0">
       				<a class="link0" href="javascript:void(0)">服务中心</a>
       				<ul class="sublist">
       					<li><a class="link1" href="/">客户服务</a></li>
       					<li><a class="link1" href="/">产品资料</a></li>
       					<li><a class="link1" href="/">下载中心</a></li>
       				</ul>
       			</li>
       			<li class="level0">
       				<a class="link0" href="javascript:void(0)">联系我们</a>
       				<ul class="sublist">
       					<li><a class="link1" href="/">售后中心</a></li>
       					<li><a class="link1" href="/">招商电话</a></li>
       					<li><a class="link1" href="/">投诉建议</a></li>
       					<li><a class="link1" href="/">在线地图</a></li>
       				</ul>
       			</li>
       		</ul>
       </div>
   </div>
       
