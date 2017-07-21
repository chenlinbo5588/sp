<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>{$SEO_title}</title>
<link href="{resource_url('css/admin_login.css')}" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="{resource_url('js/jquery.js')}"></script>
<script type="text/javascript" src="{resource_url('js/common.js',true)}"></script>
<style type="text/css">
body {
	background-color: #666666;
	background-image: url("");
	background-repeat: no-repeat;
	background-position: center top;
	background-attachment: fixed;
	background-clip: border-box;
	background-size: cover;
	background-origin: padding-box;
	width: 100%;
	padding: 0;
}
</style>
</head>
<body>
<div class="bg-dot"></div>
<div class="login-layout">
  <div class="top">
    <h5>{$SEO_title}<em></em></h5>
    <h2>平台管理中心</h2>
    <h6></h6>
  </div>
  <div class="box">
    {form_open(site_url('member/admin_login'))}
      <span><label>帐号</label><input name="email" id="user_name" autocomplete="off" type="text" class="input-text"/></span>
      <span><label>密码</label><input name="password" id="password" class="input-password" autocomplete="off" type="password" /></span>
      <span>
      	<div class="code">
        	<div class="arrow"></div>
        	<div class="code-img" id="authImg"></div>
        </div>
      	<input name="auth_code" type="text" class="input-code" id="captcha" placeholder="输入验证" title="验证码为4个字符" autocomplete="off" value="" >
      </span> 
      <span><input name="nchash" type="hidden" value="08b16cf4" /><input name="" class="input-button" type="submit" value="登录"></span>
    </form>
  </div>
</div>
<div class="bottom">
  <h5>Powered by ShopNC</h5>
  <h6 title="慈溪市土地勘测规划设计院有限公司">Coppyright &copy; {$smarty.now|date_format:"%Y"} {config_item('site_name')} ALL rights reserved.</h6>
</div>
<script type="text/javascript">
	$(document).ready(function(){
	    var random_bg=Math.floor(Math.random()*4+1);
	    
	    var bg='url({resource_url('img/login/bg_')}'+random_bg+'.jpg)';
	    $("body").css("background-image",bg);
	    //Hide Show verification code
	    $("#hide").click(function(){
	        $(".code").fadeOut("slow");
	    });
	    $("#captcha").focus(function(){
	        $(".code").fadeIn("fast");
	    });
	    
	    var imgCode1 = $.fn.imageCode({ wrapId: "#authImg", captchaUrl : "{site_url('captcha/index')}" });
	    setTimeout(imgCode1.refreshImg,500);
	    
	});
</script>
</body>
</html>