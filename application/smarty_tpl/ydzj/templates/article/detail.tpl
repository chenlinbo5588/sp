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
			text-indent: 2em;
		}

	</style>
	<div id="wrap">
		<header class="articleTitle">
			<h1 class="articleMainTitle">{$article['article_title']|escape}</h1>
			<div class="articleSubTitle">
				<h4 class="articleSubTitle articleSubPublisher">{$siteSetting['site_name']|escape}</h4>
				<h4 class="articleSubTitle articleSubTime">{$article['publish_time']|date_format:"%Y-%m-%d %H:%M"}</h4>
			</div>
		</header>
		<div class="fgx"></div>
		<article class="articleContent">
			{*<div class="digest">{$article['digest']|escape}</div>*}
			<div class="text">{$article['content']}</div>
			
			<div class="footer1">
				阅读数：{$article['article_click']}
			</div>
		</article>
		
		{*
		<div id="relateArticle">
			<div class="articleList">
				<div class="articleItem clearfix">
					<div class="articleTxt">
						<a href="javascript:void(0);">哈哈杀手撒谎好</a>
						<h4></h4>
					</div>
					<div class="articleImg"><img src="http://img01.sogoucdn.com/net/a/04/link?appid=100520033&url=http%3A%2F%2Fmmbiz.qpic.cn%2Fmmbiz_jpg%2FO9F3NTo58yrsqSGEFAozFSJ8IYoN5UeCvsePbH1Jjc3Ar3fpibg4ZgQml4O5o7ll6rdWTtMyOf5jKmAPPxQz7QA%2F0%3Fwx_fmt%3Djpeg"/></div>   
				</div>
				<div class="articleItem clearfix">
					<div class="articleTxt">
						<a href="javascript:void(0);">哈哈杀手撒谎好</a>
						<h4></h4>
					</div>
					<div class="articleImg"><img src="http://img01.sogoucdn.com/net/a/04/link?appid=100520033&url=http%3A%2F%2Fmmbiz.qpic.cn%2Fmmbiz_jpg%2FO9F3NTo58yrsqSGEFAozFSJ8IYoN5UeCvsePbH1Jjc3Ar3fpibg4ZgQml4O5o7ll6rdWTtMyOf5jKmAPPxQz7QA%2F0%3Fwx_fmt%3Djpeg"/></div>   
				</div>
				<div class="articleItem clearfix">
					<div class="articleTxt">
						<a href="javascript:void(0);">哈哈杀手撒谎好</a>
						<h4></h4>
					</div>
					<div class="articleImg"><img src="http://img01.sogoucdn.com/net/a/04/link?appid=100520033&url=http%3A%2F%2Fmmbiz.qpic.cn%2Fmmbiz_jpg%2FO9F3NTo58yrsqSGEFAozFSJ8IYoN5UeCvsePbH1Jjc3Ar3fpibg4ZgQml4O5o7ll6rdWTtMyOf5jKmAPPxQz7QA%2F0%3Fwx_fmt%3Djpeg"/></div>   
				</div>
			</div>
		</div>
		*}
		
	</div>
</body>
</html>