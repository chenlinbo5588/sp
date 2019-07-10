{include file="common/share_header.tpl"}
<body bgcolor ="2d3c50" {if $bodyClass}class="{$bodyClass}"{/if}>
	<style>
		img { 
		    height: auto; 
		    width: auto\9; 
		    width:100%;
		}
		
		.footer1 {
			margin: 15px 0 ;
			font-size:14px;
			color:#ffffff;
		}
		.articleTitle{
			color:#ffffff;
		}
		body{
			background-color: #2d3c50;
			height: 100vh;
		}
		.fgx{
			width: 93vw;
			margin-left: 3%;
			margin-right: 3%;
			border: 1px solid #ffffff;
		}
		.articleContent{
			background-color: #2d3c50;
		}
		.articleSubTitle{
			font-weight:normal;
		}
		.articleSubPublisher{
			display: inline-block;
			width: 50%;
		}
		.articleSubTime{
			float: right;
			display: inline-block;
		}
		.text{
			color: #ffffff;
			text-indent: 2em;
		}

		*{
			color: #ffffff !important;
		}
		img{
			width: 90%;
			margin-right: 5%;
			margin-top: 3%;
			margin-bottom: 3%;
		}
		.lxdw{
			margin-left: 5%;
			font-size: 14px;
		}

	</style>

	<div id="wrap">
		<header class="articleTitle">
			<h1 class="articleMainTitle">{$article['article_title']|escape}</h1>
		</header>
		<div class="lxdw">立项单位：{$article['article_origin']}</div>
		<article class="articleContent">
			{*<div class="digest">{$article['digest']|escape}</div>*}
			<div class="text" id="text">{$article['content']}</div>
			
		</article>
		
		
	</div>
</body>
</html>