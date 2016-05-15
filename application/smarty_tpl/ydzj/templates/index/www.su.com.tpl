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
<link rel="stylesheet" href="{resource_url($cssName)}"/>
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
   		<div>
   			<img class="responed" src="{resource_url('img/pg1/top1.png')}"/>
   		</div>
   		<div id="regbg">
   			<div id="reg">
	   			{form_open(site_url('index/index'),'id="registerForm"')}
		        <input type="hidden" name="inviter" value="{$inviter}"/>
		        <input type="hidden" name="inviteFrom" value="{$inviteFrom}"/>
	   			<div class="username"><input type="text" class="txt noround" autocomplete="off" name="username" value="{set_value('username')}" placeholder="请输入用户名称"/></div>
	   			<div class="mobile"><input type="text" class="txt noround" autocomplete="off" name="mobile" id="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/></div>
	   			<div class="auth_code"><input type="text" class="txt noround" name="auth_code" autocomplete="off" value="" style="width:70%" placeholder="请输入您的验证码"/><input type="button" class="getCode noround" name="authCodeBtn" value="获取验证码"/></div>
	   			<div class="sb"><input type="image" src="{resource_url('img/pg1/reg_btn.jpg')}" /></div>
	   		</div>
   		</div>
   		<div>
   			<div class="hide">最新行情资讯:专业老师实时解答行情信息，教您一眼看出行情走势波动。</div>
   			<div class="hide">青西油基础知识:投资学院、投资者教育，帮您解答各种青西油基础知识，了解炒青西油。</div>
   			<div class="hide">移动端炒青西油:移动端也能炒青西油，随时随地买涨买跌。</div>
   			<div><img class="responed" src="{resource_url('img/pg1/pc.jpg')}"/></div>
   		</div>
   		<div>
   			<div class="hide">查找微信号：JSDDY168 随时微信联系分析师 随时微信观看直播间 随时微信接收操作建议</div>
   			<div><img class="responed" src="{resource_url('img/pg1/bt_1.png')}"/></div>
   		</div>
   		<div>
   			<div class="hide">
   				<h3>江苏点点赢投资管理有限公司<h3>
   				<strong>电话：0512-57329555</strong>
   				<strong>咨询热线：4006-222-066</strong>
   				<span>邮箱：<a href="mailto:346851263@qq.com">346851263@qq.com</a></span>
   				<span>网站：<a target="_blank" href="http://www.ddy168.com">www.ddy168.com</a></span>
   			</div>
   			<div><img class="responed" src="{resource_url('img/pg1/bt_2.jpg')}"/></div>
   		</div>
   		{*
   		<ul class="introlist clearfix">
   			<li>
   				<div><img src="{resource_url('img/pg1/pic1.png')}"/></div>
   				<h3>最新行情资讯</h3>
   				<p>专业老师实时解答行情信息，教您一眼看出行情走势波动。</p>
   			</li>
   			<li>
   				<div><img src="{resource_url('img/pg1/pic2.png')}"/></div>
   				<h3>青西油基础知识</h3>
   				<p>投资学院、投资者教育，帮您解答各种青西油基础知识，了解炒青西油。</p>
   			</li>
   			<li>
   				<div><img src="{resource_url('img/pg1/pic3.jpg')}"/></div>
   				<h3>移动端炒青西油</h3>
   				<p>移动端也能炒青西油，随时随地买涨买跌。</p>
   			</li>
   		</ul>
   		<ul>
   		
   			<li><img src="{resource_url('img/pg1/pic4.png')}"/></li>
   			<li><img src="{resource_url('img/pg1/pic5.png')}"/></li>
   			<li><img src="{resource_url('img/pg1/pic6.png')}"/></li>
   		</ul>
   		*}
	</div><!-- //end of wrap -->
	<script type="text/javascript">
	var authCodeURL ="{site_url('api/register/authcode')}";
	</script>
	<script type="text/javascript" src="{resource_url('js/jquery.validation.min.js')}"></script>
	<script type="text/javascript" src="{resource_url('js/register.js')}"></script>
</body>
</html>