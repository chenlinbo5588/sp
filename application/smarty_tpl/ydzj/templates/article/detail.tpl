{include file="common/share_header.tpl"}
<body {if $bodyClass}class="{$bodyClass}"{/if}>
	<style>
		img { 
		    height: auto; 
		    width: auto\9; 
		    width:100%;
		}
		
		.footer1 {
			margin: 15px 0 ;
			font-size:14px;
			color:#2d3c50;
		}
	</style>
	<div id="wrap">
		<header class="articleTitle">
			<h1 class="articleMainTitle">{$article['article_title']|escape}</h1>
			<h4 class="articleSubTitle">{$siteSetting['site_name']|escape} {$article['publish_time']|date_format:"%Y-%m-%d"}</h4>
		</header>
		
		<article class="articleContent">
			{*<div class="digest">{$article['digest']|escape}</div>*}
			<div>{$article['content']}</div>
			
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