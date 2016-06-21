<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;" charset="UTF-8">
<title>{config_item('site_name')}-管理中心</title>
<link href="{resource_url('css/skin_0.css')}" rel="stylesheet" type="text/css" id="cssfile"/>
<script src="{resource_url('js/jquery-1.11.3.js')}" type="text/javascript"></script>
<script type="text/javascript" src="{resource_url('js/jquery.cookie.js')}"></script>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="{resource_url('js/html5shiv.js')}"></script>
      <script src="{resource_url('js/respond.min.js')}"></script>
<![endif]-->

<script>
var cookiedomain = "{config_item('cookie_domain')}",
    cookiepath = "{config_item('cookie_path')}",
    cookiepre = "{config_item('cookie_prefix')}";

$(document).ready(function(){
    $('span.bar-btn').click(function () {
        $('ul.bar-list').toggle('fast');
    });

    var pagestyle = function() {
        var iframe = $("#workspace");
        var h = $(window).height() - iframe.offset().top;
        var w = $(window).width() - iframe.offset().left;
        if(h < 300) h = 300;
        if(w < 973) w = 973;
        iframe.height(h);
        iframe.width(w);
    }
    pagestyle();
    $(window).resize(pagestyle);
    //turn location
    
    $('#mainMenu>ul').first().css('display','block');
    //第一次进入后台时，默认定到欢迎界面
    $('#welcome_dashboard').addClass('selected');            
    $('#workspace').attr('src','{admin_site_url("dashboard/welcome")}');
    
    $('#iframe_refresh').click(function(){
        var fr = document.frames ? document.frames("workspace") : document.getElementById("workspace").contentWindow;;
        fr.location.reload();
    });

});

//菜单点击
function openItem(args){
    closeBg();
    //cookie
    var spl = args.split(',');
    
    var op  = spl[0];
    var act,nav;
    try {
        act = spl[1];
        nav = spl[2];
    }
    catch(ex){
    
    }
        
    if (typeof(act)=='undefined'){ 
        //顶部菜单
        nav = args;
    }
    
    $('.actived').removeClass('actived');
    $('#nav_'+nav).addClass('actived');

    $('.selected').removeClass('selected'); 

    //show
    $('#mainMenu ul').css('display','none');
    $('#sort_'+nav).css('display','block'); 

    if (typeof(act)=='undefined'){ 
        //顶部菜单事件
        html = $('#sort_'+nav+'>li>dl>dd>ol>li').first().html();
        str = html.match(/openItem\('(.*)'\)/ig);
        arg = str[0].split("'");
        spl = arg[1].split(',');
        op  = spl[0];
        act = spl[1];
        nav = spl[2];
        
        first_obj = $('#sort_'+nav+'>li>dl>dd>ol>li').first().children('a');
        $(first_obj).addClass('selected');
        {*
        $.cookie('now_location_nav',nav, { path: '/'});
        $.cookie('now_location_act',act, { path: '/'});
        $.cookie('now_location_op',op, { path: '/'});
        *}
        
        //crumbs
        $('#crumbs').html('<span>'+$('#nav_'+nav+' > span').html()+'</span><span class="arrow">&nbsp;</span><span>'+$(first_obj).text()+'</span>'); 
    }else{
    	{*
	    //左侧菜单事件
	    //location
	    $.cookie('now_location_nav',nav,{ path: '/'});
	    $.cookie('now_location_act',act,{ path: '/'});
	    $.cookie('now_location_op',op, { path: '/'});
	    *}
	    
	    $('#'+op+ '_' + act).addClass('selected');
	    //crumbs
	    $('#crumbs').html('<span>'+$('#nav_'+nav+' > span').html()+'</span><span class="arrow">&nbsp;</span><span>'+$('#'+op+ '_' + act).html()+'</span>');
	    
    }    
    
    src = '{site_url('admin')}/'+act+'/'+op;
    $('#workspace').attr('src',src);
}

//显示灰色JS遮罩层 
function showBg(ct,content){ 
    var bH=$("body").height(); 
    var bW=$("body").width(); 
    var objWH=getObjWh(ct); 
    $("#pagemask").css({ width:bW,height:bH,display:"none" } ); 
    var tbT=objWH.split("|")[0]+"px"; 
    var tbL=objWH.split("|")[1]+"px"; 
    $("#"+ct).css({ top:tbT,left:tbL,display:"block" }); 
    $(window).scroll(function(){ resetBg() }); 
    $(window).resize(function(){ resetBg() }); 
}

function getObjWh(obj){ 
    var st=document.documentElement.scrollTop;//滚动条距顶部的距离 
    var sl=document.documentElement.scrollLeft;//滚动条距左边的距离 
    var ch=document.documentElement.clientHeight;//屏幕的高度 
    var cw=document.documentElement.clientWidth;//屏幕的宽度 
    var objH=$("#"+obj).height();//浮动对象的高度 
    var objW=$("#"+obj).width();//浮动对象的宽度 
    var objT=Number(st)+(Number(ch)-Number(objH))/2; 
    var objL=Number(sl)+(Number(cw)-Number(objW))/2; 
    return objT+"|"+objL; 
}

function resetBg(){ 
    var fullbg=$("#pagemask").css("display"); 
    if(fullbg=="block"){ 
        var bH2=$("body").height(); 
        var bW2=$("body").width(); 
        $("#pagemask").css({ width:bW2,height:bH2 }); 
        var objV=getObjWh("dialog"); 
        var tbT=objV.split("|")[0]+"px"; 
        var tbL=objV.split("|")[1]+"px"; 
        $("#dialog").css({ top:tbT,left:tbL }); 
    } 
} 

//关闭灰色JS遮罩层和操作窗口 
function closeBg(){ 
    $("#pagemask").css("display","none"); 
    $("#dialog").css("display","none"); 
}


$(function(){   
    var $li =$("#skin li");   
        $li.click(function(){   
        $("#"+this.id).addClass("selected").siblings().removeClass("selected");
        $("#cssfile").attr("href","{resource_url('css')}/"+ (this.id) +".css");   
        $.cookie( "MyCssSkin" ,  this.id , { path: '/', expires: 10 });  

        $('iframe').contents().find('#cssfile2').attr("href","{resource_url('css')}/"+ (this.id) +".css"); 
    });   

    var cookie_skin = $.cookie( "MyCssSkin");   
    if (cookie_skin) {   
        $("#"+cookie_skin).addClass("selected").siblings().removeClass("selected");
        $("#cssfile").attr("href","{resource_url('css')}/"+ cookie_skin +".css"); 
        $.cookie( "MyCssSkin" ,  cookie_skin  , { path: '/', expires: 10 }); 
    }   
});


</script>
</head>
<body style="margin: 0px;" scroll="no">
<div id="pagemask"></div>
<div id="dialog" style="display:none">
</div>
<table style="width: 100%;" id="frametable" height="100%" width="100%" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="2" height="90" class="mainhd"><div class="layout-header"> <!-- Title/Logo - can use text instead of image -->
          <div id="title"><a href="javascript:void(0);">{config_item('site_name')}-管理中心</a></div>
	          <!-- Top navigation -->
	          <div id="topnav" class="top-nav">
	            <ul>
	              <li class="adminid" title="您好:{$manage_profile['basic']['username']|escape}">您好&nbsp;:&nbsp;<strong>{$manage_profile['basic']['username']|escape}</strong></li>
	              <li><a href="{admin_site_url('index/profile')}" target="workspace" ><span>修改密码</span></a></li>
	              <li><a href="{admin_site_url('index/logout')}" title="退出"><span>退出</span></a></li>
	              <li><a href="{site_url('index')}" target="_blank" title="{config_item('site_name')}"><span>{config_item('site_name')}</span></a></li>
	            </ul>
	          </div>
	          <!-- End of Top navigation --> 
	          <!-- Main navigation -->
	          <nav id="nav" class="main-nav">
	            <ul>
	                <li style="display:none;"><a class="link actived" id="nav_dashboard" href="javascript:void(0);" onclick="openItem('dashboard');"><span>控制台</span></a></li>
					{if isset($permission['nav_setting'])}<li><a class="link" id="nav_setting" href="javascript:void(0);" onclick="openItem('setting');"><span>设置</span></a></li>{/if}
					{if isset($permission['nav_member'])}<li><a class="link" id="nav_member" href="javascript:void(0);" onclick="openItem('member');"><span>会员资料</span></a></li>{/if}
					{if isset($permission['nav_website'])}<li><a class="link" id="nav_website" href="javascript:void(0);" onclick="openItem('website');"><span>站点管理</span></a></li>{/if}
					{if isset($permission['nav_words'])}<li><a class="link" id="nav_words" href="javascript:void(0);" onclick="openItem('words');"><span>关键词管理</span></a></li>{/if}
					{if isset($permission['nav_authority'])}<li><a class="link" id="nav_authority" href="javascript:void(0);" onclick="openItem('authority');"><span>权限</span></a></li>{/if}
	            </ul>
	          </nav>
	          <div class="loca"><strong>您的位置:</strong>
	            <div id="crumbs" class="crumbs"><span>控制台</span><span class="arrow">&nbsp;</span><span>欢迎页面</span> </div>
	          </div>
	          <div class="toolbar">
	            <ul id="skin" class="skin"><span>换肤</span>
	              <li id="skin_0" class="" title="默认风格"></li>
	            </ul>
	            {*<div class="sitemap"><a id="siteMapBtn" href="#rhis" onclick="showBg('dialog','dialog_content');"><span>管理地图</span></a></div>*}
	            <div class="toolmenu"><span class="bar-btn"></span>
	              <ul class="bar-list">
	              {*<li><a onclick="openItem('clear,cache,setting');" href="javascript:void(0)">更新站点缓存</a></li>*}
	                <li><a href="{site_url('admin')}" id="iframe_refresh">刷新管理中心</a></li>
	              </ul>
	            </div>
	          </div>
            </div>
        </td>
    </tr>
    <tr>
      <td class="menutd" valign="top" width="161"><div id="mainMenu" class="main-menu">
          <ul id="sort_dashboard">
            <li>
              <dl>
                <dd>
                  <ol>
                    <li><a href="javascript:void(0);" id="welcome_dashboard" onclick="openItem('welcome,dashboard,dashboard');">欢迎页面</a></li>
                    <li><a href="javascript:void(0);" id="aboutus_dashboard" onclick="openItem('aboutus,dashboard,dashboard');">关于我们</a></li>
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>
          <ul id="sort_setting">
            <li>
              <dl>
                <dd>
                  <ol>
                    {if isset($permission['admin/setting/base'])}<li><a href="javascript:void(0);" id="base_setting" onclick="openItem('base,setting,setting');">站点设置</a></li>{/if}
                    {if isset($permission['admin/upload/param'])}<li><a href="javascript:void(0);" id="param_upload" onclick="openItem('param,upload,setting');">上传设置</a></li>{/if}
                    {if isset($permission['admin/setting/seoset'])}<li><a href="javascript:void(0);" id="seoset_setting" onclick="openItem('seoset,setting,setting');">SEO设置</a></li>{/if}
                    {if isset($permission['admin/message/email'])}<li><a href="javascript:void(0);" id="email_message" onclick="openItem('email,message,setting');">消息通知</a></li>{/if}
                    {*<li><a href="javascript:void(0);" id="system_payment" onclick="openItem('system,payment,setting');">支付方式</a></li>
                    <li><a href="javascript:void(0);" id="express_setting" onclick="openItem('express,setting,setting');">快递公司</a></li>
                    <li><a href="javascript:void(0);" id="area_setting" onclick="openItem('area,setting,setting');">配送地区</a></li>
                    <li><a href="javascript:void(0);" id="cache_setting" onclick="openItem('cache,setting,setting');">清理缓存</a></li>
                    <li><a href="javascript:void(0);" id="perform_setting" onclick="openItem('perform,setting,setting');">性能优化</a></li>
                    <li><a href="javascript:void(0);" id="search_setting" onclick="openItem('search,setting,setting');">搜索设置</a></li>
                    <li><a href="javascript:void(0);" id="log_setting" onclick="openItem('log,setting,setting');">操作日志</a></li>*}
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>
          <ul id="sort_member">
            <li>
              <dl>
                <dd>
                  <ol>
                    {if isset($permission['admin/member/index'])}<li><a href="javascript:void(0);" id="index_member" onclick="openItem('index,member,member');">会员资料</a></li>{/if}
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>
          <ul id="sort_website">
            <li>
              <dl>
                <dd>
                  <ol>
                    {if isset($permission['admin/website/index'])}<li><a href="javascript:void(0);" id="index_website" onclick="openItem('index,website,website');">站点管理</a></li>{/if}
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>
          <ul id="sort_words">
            <li>
              <dl>
                <dd>
                  <ol>
                    {if isset($permission['admin/market_words/index'])}<li><a href="javascript:void(0);" id="index_market_words" onclick="openItem('index,market_words,words');">关键词管理</a></li>{/if}
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>
          <ul id="sort_authority">
            <li>
              <dl>
                <dd>
                  <ol>
                    {if isset($permission['admin/authority/user'])}<li><a href="JavaScript:void(0);" id="user_authority" onclick="openItem('user,authority,authority');">管理员管理</a></li>{/if}
                    {if isset($permission['admin/authority/role'])}<li><a href="JavaScript:void(0);" id="role_authority" onclick="openItem('role,authority,authority');">权限组管理</a></li>{/if}
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>
        </div>
        <div class="copyright">
            <p>Powered By <em><a href="{site_url('index')}" target="_blank"><span class="vol"><font class="b">Jay</font></span></a></em></p>
            <p>&copy;2010-{$smarty.now|date_format:"%Y"} </p>
        </div>
      </td>
      <td valign="top" width="100%">
        <iframe src="" id="workspace" name="workspace" style="overflow: visible;" frameborder="0" width="100%" height="100%" scrolling="yes" onload="window.parent"></iframe>
      </td>
    </tr>
  </tbody>
</table>
</body>
</html>