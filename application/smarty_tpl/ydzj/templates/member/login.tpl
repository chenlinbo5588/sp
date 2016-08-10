<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>实验室药品仪器管理平台登录</title>
<script type="text/javascript" src="{resource_url('js/jquery.js')}"></script>
<script type="text/javascript" src="{resource_url('js/jquery.validation.min.js')}"></script>
<link href="{resource_url('css/base.css')}" rel="stylesheet" type="text/css">
<link href="{resource_url('css/lab.css')}" rel="stylesheet" type="text/css">

<link href="{resource_url('font/font-awesome/css/font-awesome.min.css')}" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="{resource_url('font/font-awesome/css/font-awesome-ie7.min.css')}">
<![endif]-->

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="{resource_url('js/html5shiv.js')}"></script>
      <script src="{resource_url('js/respond.min.js')}"></script>
<![endif]-->
<!--[if IE 6]>
<script src="{resource_url('js/IE6_MAXMIX.js')}"></script>
<script src="{resource_url('js/IE6_PNG.js')}"></script>
<script>
DD_belatedPNG.fix('.pngFix');
</script>
<script> 
// <![CDATA[ 
if((window.navigator.appName.toUpperCase().indexOf("MICROSOFT")>=0)&&(document.execCommand)) 
try{ 
document.execCommand("BackgroundImageCache", false, true); 
   } 
catch(e){} 
// ]]> 
</script> 
<![endif]-->
{include file="common/js_var.tpl"}
<script type="text/javascript">
var checkUrl = "{site_url('captcha/index')}";

function setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
    if(cookieValue == '' || seconds < 0) {
        cookieValue = '';
        seconds = -2592000;
    }
    if(seconds) {
        var expires = new Date();
        expires.setTime(expires.getTime() + seconds * 1000);
    }
    domain = !domain ? cookiedomain : domain;
    path = !path ? cookiepath : path;
    document.cookie = escape(cookiepre + cookieName) + '=' + escape(cookieValue)
        + (expires ? '; expires=' + expires.toGMTString() : '')
        + (path ? '; path=' + path : '/')
        + (domain ? '; domain=' + domain : '')
        + (secure ? '; secure' : '');
}

function getcookie(name, nounescape) {
    name = cookiepre + name;
    var cookie_start = document.cookie.indexOf(name);
    var cookie_end = document.cookie.indexOf(";", cookie_start);
    if(cookie_start == -1) {
        return '';
    } else {
        var v = document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length));
        return !nounescape ? unescape(v) : v;
    }
}

$(document).ready(function() {
    $("#form_login").validate({
        errorPlacement:function(error, element) {
            element.prev(".repuired").append(error);
        },
        rules:{
            username:{
                required:true
            },
            password:{
                required:true
            },
            captcha:{
                required:true
            }
        },
        messages:{
            username:{
                required:"<i class='icon-exclamation-sign'></i>用户名不能为空"
            },
            password:{
                required:"<i class='icon-exclamation-sign'></i>密码不能为空"
            },
            captcha:{
                required:"<i class='icon-exclamation-sign'></i>验证码不能为空"
            }
        }
    });
    //Hide Show verification code
    $("#hide").click(function(){
        $(".code").fadeOut("slow");
    });
    $("#captcha").focus(function(){
        $(".code").fadeIn("fast");
    });    
    
    
});
</script>
</head>
<body>
<div id="loginBG01" class="ncsc-login-bg" style="display:block" >
  <p class="pngFix"></p>
</div>
<div id="welcome" style="display:none;">
    <p id="welcomeText"></p>
</div>
<div class="ncsc-login-container">
  <div class="ncsc-login-title">
    <h2>实验室药品仪器管理中心</h2>
    {*<span>请输入您注册商铺时申请的商家名称</br>
    登录密码为商城用户通用密码</span>*}</div>
    {form_open(site_url('member/login'),'id="form_login"')}
    <div class="input">
      <label>用户名</label>
      {if $errorMsg['username']}
      <span class="repuired error"><label for="username" class="error"><i class="icon-exclamation-sign"></i>{$errorMsg['username']}</label></span>
      {else}
      <span class="repuired"></span>
      {/if}
      <input name="username" type="text" autocomplete="off" class="text{if $errorMsg['username']} error{/if}" value="{$smarty.post.username}" autofocus>
      <span class="ico"><i class="icon-user"></i></span> </div>
    <div class="input">
      <label>密码</label>
      {if $errorMsg['password']}
      <span class="repuired error"><label for="password" class="error"><i class="icon-exclamation-sign"></i>{$errorMsg['password']}</label></span>
      {else}
      <span class="repuired"></span>
      {/if}
      <input name="password" type="password" autocomplete="off" class="text{if $errorMsg['password']} error{/if}">
      <span class="ico"><i class="icon-key"></i></span> </div>
    <div class="input">
      <label>验证码</label>
      {if $errorMsg['captcha']}
      <span class="repuired error"><label for="captcha" class="error"><i class="icon-exclamation-sign"></i>{$errorMsg['captcha']}</label></span>
      {else}
      <span class="repuired"></span>
      {/if}
      <input type="text" name="captcha" id="captcha" autocomplete="off" class="text{if $errorMsg['captcha']} error{/if}" style="width: 80px;" maxlength="4" size="10" />
      <div class="code">
        <div class="arrow"></div>
        <div class="code-img"><a href="javascript:void(0)" onclick="javascript:document.getElementById('codeimage').src='{site_url('captcha/index')}?t=' + Math.random();"><img src="{site_url('captcha/index')}" name="codeimage" border="0" id="codeimage"></a></div>
        <a href="javascript:void(0);" id="hide" class="close" title=""><i></i></a> <a href="javascript:void(0);" onclick="javascript:document.getElementById('codeimage').src='{site_url('captcha/index')}?t=' + Math.random();" class="change" title=""><i></i></a> </div>
      <span class="ico"><i class="icon-qrcode"></i></span>
      <input type="submit" class="login-submit" value="登录">
    </div>
  </form>
  <div class="copyright">浙ICP备15010132号 Powered by Jay © 2007-{$smarty.now|date_format:"Y"}<br/><a href="mailto:chenlinbo5588@163.com">邮件反馈问题</a><p>{include file="common/baidu_stat.tpl"}</p></div>
</div>
<script>

var welText = [
    '欢迎进入浙江省农产品加工技术研究重点实验室药品仪器管理中心！',
    '欢迎进入浙江大学生工食品学院实验室药品仪器管理中心！'
];

$(function(){
	{if $feedback}
	alert("{$feedback}");
	{/if}


    var wel = getcookie('wel');
    if(wel && wel.substring(0,wel.indexOf(',')) == 'no'){
	    var w = $(document).width();
	    var h = $(document).height();
	    
	    $("#welcomeText").html(welText[parseInt(wel.substring(wel.indexOf(',') + 1)) - 1]);
	    
	    $("#welcome").css({
	        left:0,
	        top:0,
	        zIndex:100,
	        backgroundColor:"#000",
	        opacity: 0.3
	    }).show().animate({
	        width: w + 'px',
	        height: h + 'px'
	    });
	    
	    var timer = setTimeout(function(){
	        $("#welcome").animate({
	            width:0,
	            height:0
	        } , function(){
	            $(this).hide();
	        });
	        
	        setcookie('wel','yes' +  wel.substring(wel.indexOf(',')), 86400 * 365);
	        
	    }, 3000);
    }
});
</script>
</body>
</html>