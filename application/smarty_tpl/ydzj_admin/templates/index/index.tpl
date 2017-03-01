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
    if($.cookie('now_location_act') != null){
        openItem($.cookie('now_location_op')+','+$.cookie('now_location_act')+','+$.cookie('now_location_nav'));
    }else{
        $('#mainMenu>ul').first().css('display','block');
        //第一次进入后台时，默认定到欢迎界面
        $('#welcome_dashboard').addClass('selected');            
        $('#workspace').attr('src','{admin_site_url("dashboard/welcome")}');
    }
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
        
        $.cookie('now_location_nav',nav, { path: '/'});
        $.cookie('now_location_act',act, { path: '/'});
        $.cookie('now_location_op',op, { path: '/'});
        
        //crumbs
        $('#crumbs').html('<span>'+$('#nav_'+nav+' > span').html()+'</span><span class="arrow">&nbsp;</span><span>'+$(first_obj).text()+'</span>'); 
    }else{
	    //左侧菜单事件
	    //location
	    $.cookie('now_location_nav',nav,{ path: '/'});
	    $.cookie('now_location_act',act,{ path: '/'});
	    $.cookie('now_location_op',op, { path: '/'});
	    
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
  <div class="title">
    <h3>管理中心导航</h3>
    <span><a href="javascript:void(0);" onclick="closeBg();">关闭</a></span> </div>
    <div class="content clearfix">
	    <dl>    
	        <dt>设置</dt>
	        <dd><a href="javascript:void(0)" onclick="openItem('base,setting,setting')">站点设置</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('param,upload,setting')">上传设置</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('seoset,setting,setting')">SEO设置</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('email,message,setting')">邮件通知</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('payment,setting,setting')">支付方式</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('cache,setting,setting')">清理缓存</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('perform,setting,setting')">性能优化</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('search,setting,setting')">搜索设置</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('log,setting,setting')">操作日志</a></dd>
	    </dl>
	    {*
	    <dl>    
            <dt>会员</dt>
            <dd><a href="javascript:void(0)" onclick="openItem('index,member,member')">会员管理</a></dd>
            <dd><a href="javascript:void(0)" onclick="openItem('notify,member,member')">会员通知</a></dd>
            <dd><a href="javascript:void(0)" onclick="openItem('credits,member,member')">积分管理</a></dd>
            <dd><a href="javascript:void(0)" onclick="openItem('album,member,member')">会员相册</a></dd>
            <dd><a href="javascript:void(0)" onclick="openItem('tag,member,member')">会员标签</a></dd>
        </dl>
        <dl>    
            <dt>队伍</dt>
            <dd><a href="javascript:void(0)" onclick="openItem('setting,team,team')">队伍设置</a></dd>
            <dd><a href="javascript:void(0)" onclick="openItem('level,team,team')">队伍头衔设置</a></dd>
            <dd><a href="javascript:void(0)" onclick="openItem('category,team,team')">队伍分类管理</a></dd>
            <dd><a href="javascript:void(0)" onclick="openItem('index,team,team')">队伍管理</a></dd>
            <dd><a href="javascript:void(0)" onclick="openItem('member_list,team,team')">队伍成员管理</a></dd>
            <dd><a href="javascript:void(0)" onclick="openItem('inform,team,team')">队伍举报管理</a></dd>
            <dd><a href="javascript:void(0)" onclick="openItem('adv,team,team')">队伍首页广告</a></dd>
            <dd><a href="javascript:void(0)" onclick="openItem('cache,team,team')">更新队伍缓存</a></dd>
        </dl>
        <dl>    
            <dt>场馆</dt>
            <dd><a href="javascript:void(0)" onclick="openItem('setting,stadium,stadium')">场馆等级</a></dd>
            <dd><a href="javascript:void(0)" onclick="openItem('index,stadium,stadium')">场馆管理</a></dd>
        </dl>
        *}
	    <dl>    
	        <dt>商品</dt>
	        <dd><a href="javascript:void(0)" onclick="openItem('category,goods,goods')">分类管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('index,brand,goods')">品牌管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('index,goods,goods')">商品管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('type,goods,goods')">类型管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('spec,goods,goods')">规格管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('album,goods,goods')">图片空间</a></dd>
	    </dl>
	    {*
	    <dl>    
	        <dt>交易</dt>
	        <dd><a href="javascript:void(0)" onclick="openItem('index,order,trade')">订单管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('refund,order,trade')">退款管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('returned,order,trade')">退货管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('consulting,trade,trade')">咨询管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('inform,trade,trade')">举报管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('evaluate,trade,trade')">评价管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('complain,trade,trade')">投诉管理</a></dd>
	    </dl>
	    *}
	    <dl>    
	        <dt>网站</dt>
	        <dd><a href="javascript:void(0)" onclick="openItem('category,article_class,website')">文章分类</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('index,article,website')">文章管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('index,comment,website')">评论管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('index,tag,website')">标签管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('index,recommend,website')">推荐位</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('index,adv,website')">广告管理</a></dd>
	    </dl>
	    {*
	    <dl>    
	        <dt>运营</dt>
	        <dd><a href="javascript:void(0)" onclick="openItem('setting,operation,operation')">基本设置</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('groupbuy,operation,operation')">团购管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('zk,operation,operation')">限时折扣</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('mansong,operation,operation')">满即送</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('bundling,operation,operation')">优惠套装</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('voucher,operation,operation')">代金券</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('bill,operation,operation')">结算管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('activity,operation,operation')">活动管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('pointprod,operation,operation')">兑换礼品</a></dd>
	    </dl>
	    <dl>    
	        <dt>统计</dt>
	        <dd><a href="javascript:void(0)" onclick="openItem('member,stat,stat')">会员统计</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('statdium,stat,stat')">店铺统计</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('trade,stat,stat')">销量分析</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('marketing,stat,stat')">营销分析</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('aftersale,stat,stat')">售后分析</a></dd>
	    </dl>
	    <dl>    
	        <dt>微信营销</dt>
	        <dd><a href="javascript:void(0)" onclick="openItem('article,weixin,weixin')">微信文章管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('adv,weixin,weixin')">微信广告管理</a></dd>
	    </dl>*}
	    <dl>    
	        <dt>CMS</dt>
	        <dd><a href="javascript:void(0)" onclick="openItem('setting,cms,cms')">CMS管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('index,cms_article,cms')">文章管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('category,cms_article,cms')">文章分类</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('index,magazine,cms')">画报管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('category,magazine,cms')">画报分类</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('index,special,cms')">专题管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('index,tag,cms')">标签管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('index,comment,cms')">评论管理</a></dd>
	    </dl>
	    
	    <dl>    
	        <dt>权限</dt>
	        <dd><a href="javascript:void(0)" onclick="openItem('user,authority,authority')">管理员管理</a></dd>
	        <dd><a href="javascript:void(0)" onclick="openItem('role,authority,authority')">角色管理</a></dd>
	    </dl>
    </div>
    
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
	                <li><a class="link actived" id="nav_dashboard" href="javascript:;" onclick="openItem('dashboard');"><span>控制台</span></a></li>
					<li><a class="link" id="nav_setting" href="javascript:;" onclick="openItem('setting');"><span>设置</span></a></li>
					{*<li><a class="link" id="nav_member" href="javascript:;" onclick="openItem('member');"><span>会员</span></a></li>
					<li><a class="link" id="nav_team" href="javascript:;" onclick="openItem('team');"><span>队伍</span></a></li>
					<li><a class="link" id="nav_stadium" href="javascript:;" onclick="openItem('stadium');"><span>场馆</span></a></li>
					<li><a class="link" id="nav_game" href="javascript:;" onclick="openItem('game');"><span>赛事</span></a></li>*}
					<li><a class="link" id="nav_goods" href="javascript:;" onclick="openItem('goods');"><span>商品</span></a></li>
					{*<li><a class="link" id="nav_trade" href="javascript:;" onclick="openItem('trade');"><span>交易</span></a></li>*}
					<li><a class="link" id="nav_website" href="javascript:;" onclick="openItem('website');"><span>网站</span></a></li>
					{*<li><a class="link" id="nav_operation" href="javascript:;" onclick="openItem('operation');"><span>运营</span></a></li>
					<li><a class="link" id="nav_stat" href="javascript:;" onclick="openItem('stat');"><span>统计</span></a></li>
					<li><a class="link" id="nav_weixin" href="javascript:;" onclick="openItem('weixin');"><span>微信营销</span></a></li>*}
					<li><a class="link" id="nav_cms" href="javascript:;" onclick="openItem('cms');"><span>CMS</span></a></li>
					<li><a class="link" id="nav_authority" href="javascript:;" onclick="openItem('authority');"><span>权限</span></a></li>
	            </ul>
	          </nav>
	          <div class="loca"><strong>您的位置:</strong>
	            <div id="crumbs" class="crumbs"><span>控制台</span><span class="arrow">&nbsp;</span><span>欢迎页面</span> </div>
	          </div>
	          <div class="toolbar">
	            <ul id="skin" class="skin"><span>换肤</span>
	              <li id="skin_0" class="" title="默认风格"></li>
	            </ul>
	            <div class="sitemap"><a id="siteMapBtn" href="#rhis" onclick="showBg('dialog','dialog_content');"><span>管理地图</span></a></div>
	            <div class="toolmenu"><span class="bar-btn"></span>
	              <ul class="bar-list">
	                <li><a onclick="openItem('clear,cache,setting');" href="javascript:void(0)">更新站点缓存</a></li>
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
                    {*<li><a href="javascript:void(0);" id="aboutus_dashboard" onclick="openItem('aboutus,dashboard,dashboard');">关于我们</a></li>*}
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
                    <li><a href="javascript:void(0);" id="base_setting" onclick="openItem('base,setting,setting');">站点设置</a></li>
                    <li><a href="javascript:void(0);" id="param_upload" onclick="openItem('param,upload,setting');">上传设置</a></li>
                    <li><a href="javascript:void(0);" id="seoset_setting" onclick="openItem('seoset,setting,setting');">SEO设置</a></li>
                    <li><a href="javascript:void(0);" id="email_message" onclick="openItem('email,message,setting');">消息通知</a></li>
                    {*<li><a href="javascript:void(0);" id="system_payment" onclick="openItem('system,payment,setting');">支付方式</a></li>
                    <li><a href="javascript:void(0);" id="express_setting" onclick="openItem('express,setting,setting');">快递公司</a></li>
                    <li><a href="javascript:void(0);" id="area_setting" onclick="openItem('area,setting,setting');">配送地区</a></li>
                    <li><a href="javascript:void(0);" id="cache_setting" onclick="openItem('cache,setting,setting');">清理缓存</a></li>
                    <li><a href="javascript:void(0);" id="perform_setting" onclick="openItem('perform,setting,setting');">性能优化</a></li>*}
                    <li><a href="javascript:void(0);" id="search_setting" onclick="openItem('search,setting,setting');">搜索设置</a></li>
                    {*<li><a href="javascript:void(0);" id="log_setting" onclick="openItem('log,setting,setting');">操作日志</a></li>*}
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>
          {*
          <ul id="sort_member">
            <li>
              <dl>
                <dd>
                  <ol>
                    <li><a href="javascript:void(0);" id="index_member" onclick="openItem('index,member,member');">会员管理</a></li>
                    <li><a href="javascript:void(0);" id="notice_member" onclick="openItem('notice,member,member');">会员通知</a></li>
                    <li><a href="javascript:void(0);" id="credits_member" onclick="openItem('credits,member,member');">积分管理</a></li>
                    <li><a href="javascript:void(0);" id="album_member" onclick="openItem('album,member,member');">会员相册</a></li>
                    <li><a href="javascript:void(0);" id="tag_member" onclick="openItem('tag,member,member');">会员标签</a></li>
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>
          <ul id="sort_team">
            <li>
              <dl>
                <dd>
                  <ol>
                    <li><a href="JavaScript:void(0);" id="index_team" onclick="openItem('index,team,team');">队伍管理</a></li>
                    <li><a href="JavaScript:void(0);" id="category_team" onclick="openItem('category,team,team');">队伍分类管理</a></li>
                    <li><a href="JavaScript:void(0);" id="level_team" onclick="openItem('level,team,team');">队伍头衔设置</a></li>
                    <li><a href="JavaScript:void(0);" id="member_list_team" onclick="openItem('member_list,team,team');">队伍成员管理</a></li>
                    <li><a href="JavaScript:void(0);" id="inform_team" onclick="openItem('inform,team,team');">队伍举报管理</a></li>
                    <li><a href="JavaScript:void(0);" id="setting_team" onclick="openItem('setting,team,team');">队伍设置</a></li>
                    <li><a href="JavaScript:void(0);" id="adv_team" onclick="openItem('adv,team,team');">队伍首页广告</a></li>
                    <li><a href="JavaScript:void(0);" id="cache_team" onclick="openItem('cache,team,team');">更新队伍缓存</a></li>
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>
          
          <ul id="sort_stadium">
            <li>
              <dl>
                <dd>
                  <ol>
                    <li><a href="javascript:void(0);" id="setting_stadium" onclick="openItem('setting,stadium,stadium');">场馆设置</a></li>
                    <li><a href="javascript:void(0);" id="index_stadium" onclick="openItem('index,stadium,stadium');">场馆管理</a></li>
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>
          *}
          <ul id="sort_goods">
            <li>
              <dl>
                <dd>
                  <ol>
                    <li><a href="javascript:void(0);" id="category_goods_class" onclick="openItem('category,goods_class,goods');">分类管理</a></li>
                    <li><a href="javascript:void(0);" id="index_brand" onclick="openItem('index,brand,goods');">品牌管理</a></li>
                    <li><a href="javascript:void(0);" id="index_goods" onclick="openItem('index,goods,goods');">商品管理</a></li>
                    {*<li><a href="javascript:void(0);" id="type_goods" onclick="openItem('type,goods,goods');">类型管理</a></li>
                    <li><a href="javascript:void(0);" id="spec_goods" onclick="openItem('spec,goods,goods');">规格管理</a></li>
                    <li><a href="javascript:void(0);" id="album_goods" onclick="openItem('album,goods,goods');">图片空间</a></li>*}
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>
          {*
          <ul id="sort_trade">
            <li>
              <dl>
                <dd>
                  <ol>
                    <li><a href="javascript:void(0);" id="index_order" onclick="openItem('index,order,trade');">订单管理</a></li>
                    <li><a href="javascript:void(0);" id="refund_order" onclick="openItem('refund,order,trade');">退款管理</a></li>
                    <li><a href="javascript:void(0);" id="returned_order" onclick="openItem('returned,order,trade');">退货管理</a></li>
                    <li><a href="javascript:void(0);" id="consulting_trade" onclick="openItem('consulting,trade,trade');">咨询管理</a></li>
                    <li><a href="javascript:void(0);" id="inform_trade" onclick="openItem('inform,trade,trade');">举报管理</a></li>
                    <li><a href="javascript:void(0);" id="evaluate_trade" onclick="openItem('evaluate,trade,trade');">评价管理</a></li>
                    <li><a href="javascript:void(0);" id="complain_trade" onclick="openItem('complain,trade,trade');">投诉管理</a></li>
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>
          *}
          <ul id="sort_website">
            <li>
              <dl>
                <dd>
                  <ol>
                    <li><a href="javascript:void(0);" id="category_article_class" onclick="openItem('category,article_class,website');">文章分类</a></li>
                    <li><a href="javascript:void(0);" id="index_article" onclick="openItem('index,article,website');">文章管理</a></li>
                    {*<li><a href="JavaScript:void(0);" id="index_tag" onclick="openItem('index,tag,website');">标签管理</a></li>
                    <li><a href="JavaScript:void(0);" id="index_comment" onclick="openItem('index,comment,website');">评论管理</a></li>
                    <li><a href="javascript:void(0);" id="index_adv" onclick="openItem('index,adv,website');">广告管理</a></li>
                    <li><a href="javascript:void(0);" id="index_recommend" onclick="openItem('index,recommend,website');">推荐位</a></li>*}
                    <li><a href="javascript:void(0);" id="index_suggestion" onclick="openItem('index,suggestion,website');">投诉建议</a></li>
                    {*<li><a href="javascript:void(0);" id="index_leavemsg" onclick="openItem('index,leavemsg,website');">客户留言</a></li>*}
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>
          {*
          <ul id="sort_operation">
            <li>
              <dl>
                <dd>
                  <ol>
                    <li><a href="javascript:void(0);" id="setting_operation" onclick="openItem('setting,operation,operation');">基本设置</a></li>
                    <li><a href="javascript:void(0);" id="groupbuy_operation" onclick="openItem('groupbuy,operation,operation');">团购管理</a></li>
                    <li><a href="javascript:void(0);" id="zk_operation" onclick="openItem('zk,operation,operation');">限时折扣</a></li>
                    <li><a href="javascript:void(0);" id="mansong_operation" onclick="openItem('mansong,operation,operation');">满即送</a></li>
                    <li><a href="javascript:void(0);" id="bundling_operation" onclick="openItem('bundling,operation,operation');">优惠套装</a></li>
                    <li><a href="javascript:void(0);" id="voucher_operation" onclick="openItem('voucher,operation,operation');">代金券</a></li>
                    <li><a href="javascript:void(0);" id="bill_operation" onclick="openItem('bill,operation,operation');">结算管理</a></li>
                    <li><a href="javascript:void(0);" id="activity_operation" onclick="openItem('activity,operation,operation');">活动管理</a></li>
                    <li><a href="javascript:void(0);" id="pointprod_operation" onclick="openItem('pointprod,operation,operation');">兑换礼品</a></li>
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>

          <ul id="sort_stat">
            <li>
              <dl>
                <dd>
                  <ol>
                    <li><a href="javascript:void(0);" id="newmember_stat" onclick="openItem('newmember,stat,stat');">会员统计</a></li>
                    <li><a href="javascript:void(0);" id="newstadium_stat" onclick="openItem('newstadium,stat,stat');">场馆统计</a></li>
                    <li><a href="javascript:void(0);" id="newteam_stat" onclick="openItem('newteam,stat,stat');">队伍统计</a></li>
                    <li><a href="javascript:void(0);" id="game_stat" onclick="openItem('game,stat,stat');">比赛统计</a></li>
                    <li><a href="javascript:void(0);" id="marketing_stat" onclick="openItem('marketing,stat,stat');">营销分析</a></li>
                    <li><a href="javascript:void(0);" id="refund_stat" onclick="openItem('refund,stat,stat');">退款分析</a></li>
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>
          <ul id="sort_weixin">
            <li>
              <dl>
                <dd>
                  <ol>
                    <li><a href="javascript:void(0);" id="article_weixin" onclick="openItem('article,weixin,weixin');">微信文章管理</a></li>
                    <li><a href="javascript:void(0);" id="adv_weixin" onclick="openItem('adv,weixin,weixin');">微信广告管理</a></li>
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>
          *}
          <ul id="sort_cms">
            <li>
              <dl>
                <dd>
                  <ol>
                    <li><a href="JavaScript:void(0);" id="index_cms" onclick="openItem('index,cms,cms');">CMS设置</a></li>
                    <li><a href="JavaScript:void(0);" id="index_cms_article" onclick="openItem('index,cms_article,cms');">CMS文章管理</a></li>
                    <li><a href="JavaScript:void(0);" id="category_cms_article_class" onclick="openItem('category,cms_article_class,cms');">CMS文章分类</a></li>
                    {*<li><a href="JavaScript:void(0);" id="index_magazine" onclick="openItem('index,magazine,cms');">画报管理</a></li>
                    <li><a href="JavaScript:void(0);" id="category_magazine" onclick="openItem('category,magazine,cms');">画报分类</a></li>
                    <li><a href="JavaScript:void(0);" id="index_special" onclick="openItem('index,special,cms');">专题管理</a></li>*}
                    
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
                    <li><a href="JavaScript:void(0);" id="user_authority" onclick="openItem('user,authority,authority');">管理员管理</a></li>
                    <li><a href="JavaScript:void(0);" id="role_authority" onclick="openItem('role,authority,authority');">角色管理</a></li>
                    {*<li><a href="JavaScript:void(0);" id="menu_authority" onclick="openItem('menu,authority,authority');">菜单管理</a></li>*}
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>
        </div>
        <div class="copyright">
            <p>Powered By <em><a href="{site_url('index')}" target="_blank"><span class="vol"><font class="b">{config_item('site_name')}</font></span></a></em></p>
            <p>&copy;2010-{$smarty.now|date_format:"%Y"} <a href="{site_url('index')}" target="_blank">Jay Inc.</a></p>
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