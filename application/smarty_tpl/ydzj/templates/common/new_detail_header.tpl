<!DOCTYPE html>
<html class="smart-design-mode"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width">
    <meta name="description" content="{$info['digest']|escape}">
    <meta name="keywords" content="{$info['keyword']|escape}" >
    <meta name="renderer" content="webkit">
    <meta name="applicable-device" content="pc">
    <meta http-equiv="Cache-Control" content="no-transform">
    <title>{$info['article_title']}</title>
    <link rel="icon" href="{resource_url('img/tdkcImg/6056936.png')}"><link rel="shortcut icon" href="{resource_url('img/tdkcImg/6056936.png')}"><link rel="bookmark" href="{resource_url('img/tdkcImg/6056936.png')}">

    <link href="{resource_url('css/tdkcCss/reset.css')}" rel="stylesheet" type="text/css">
    <link href="{resource_url('css/tdkcCss/iconfont.css')}" rel="stylesheet" type="text/css">
    <link href="{resource_url('css/tdkcCss/iconfont(1).css')}" rel="stylesheet" type="text/css">
    <link href="{resource_url('css/tdkcCss/pager.css')}" rel="stylesheet" type="text/css">
    <link href="{resource_url('css/tdkcCss/iconfont2.css')}" rel="stylesheet" type="text/css"/>
    <link href="{resource_url('css/tdkcCss/461386_Pc_zh-CN.css')}" rel="stylesheet">
    <link href="{resource_url('css/tdkcCss/1133604_Pc_zh-CN.css')}" rel="stylesheet">
    
    <script src="{resource_url('js/tdkcJs/jquery-1.10.2.min.js')}" type="text/javascript"></script>
    <script src="{resource_url('js/tdkcJs/jquery.lazyload.min.js')}" type="text/javascript"></script>
    <script src="{resource_url('js/tdkcJs/smart.animation.min.js')}" type="text/javascript"></script>
    <script src="{resource_url('js/tdkcJs/kino.razor.min.js')}" type="text/javascript"></script>
    <script src="{resource_url('js/tdkcJs/common.min.js')}" type="text/javascript"></script>
    <script src="{resource_url('js/tdkcJs/admin.validator.min.js')}" type="text/javascript"></script>
    <script src="{resource_url('js/tdkcJs/jquery.cookie.js')}" type="text/javascript"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            cache: false,
            beforeSend: function (jqXHR, settings) {
                settings.data = settings.data && settings.data.length > 0 ? (settings.data + "&") : "";
                settings.data = settings.data + "__RequestVerificationToken=" + $('input[name="__RequestVerificationToken"]').val();
                return true;
            }
        });
    </script>


    <!-- GrowingIO Analytics code version 2.1 智能埋点-->
    <!-- Copyright 2015-2018 GrowingIO, Inc. More info available at http://www.growingio.com -->

    <script type="text/javascript">
        !function (e, t, n, g, i) { e[i] = e[i] || function () { (e[i].q = e[i].q || []).push(arguments) }, n = t.createElement("script"), tag = t.getElementsByTagName("script")[0], n.async = 1, n.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + g, tag.parentNode.insertBefore(n, tag) }(window, document, "script", "assets.giocdn.com/2.1/gio.js", "gio");
        gio('init', '91347d56b9f11729', {});

        //custom page code begin here

        //custom page code end here

        gio('send');

    </script>

<style id="table">.w-detail .selectTdClass .w-detail table.noBorderTable td,table.noBorderTable th,table.noBorderTable caption .w-detail table .w-detail td,th .w-detail caption .w-detail th .w-detail table tr.firstRow th .w-detail .ue-table-interlace-color-single  .ue-table-interlace-color-double .w-detail td p 	</style>
<link rel="stylesheet" href="{resource_url('css/tdkcCss/share_style0_16.css')}"></head>

<body id="smart-body" area="main" style="">
    <input type="hidden" id="pageinfo" value="1133604" data-type="2" data-device="Pc" data-entityid="-5910">
    <input id="txtDeviceSwitchEnabled" value="show" type="hidden">
<link href="{resource_url('css/tdkcCss/view.css')}" rel="stylesheet" type="text/css">

<input type="hidden" id="secUrl" data-host="c1797065108ffy.scd.wezhan.cn" data-pathname="/newsinfo/-5910.html" data-search="" data-hash="">

<script type="text/javascript">

    function initialDeviceSwitch() {
        var pageinfo = $("#pageinfo");
        var l = window.location;
        if (pageinfo.length == 0 && window.frames.length > 0) {
            pageinfo = $(window.frames[0].document).find("#pageinfo");
            l = window.frames[0].document.location;
        }
        $(".m-deviceSwitch").show();
        if (pageinfo.length == 0) {
            $(".m-deviceSwitch").hide();
            return;
        }
        $("#secUrl").attr("data-host", l.host).attr("data-pathname", l.pathname).attr("data-search", l.search).attr("data-hash", l.hash);
        var pagedevice = pageinfo.attr("data-device");
        $(".m-deviceSwitch").find("li[data-device=" + pagedevice + "]").addClass("active").find("a").addClass("z-current");
        $(".m-deviceSwitch").find("li").not(".active").click(function () {
            var secUrl = $("#secUrl");
            var pathname = secUrl.attr("data-pathname");
            var search = secUrl.attr("data-search");
            var hash = secUrl.attr("data-hash");
            var npagedevice = $(this).attr("data-device");
            var mobileUrl = "";
            if (npagedevice == "Pc") {
                mobileUrl = mobileUrl + pathname + search.replace("deviceModel=mobile", "deviceModel=pc") + hash;
            } else if (npagedevice == "Mobile") {
                var toUrl = pathname + search.replace("deviceModel=pc", "deviceModel=mobile") + hash;
                mobileUrl = mobileUrl + "/Runtime/MobileContainer?url=" + encodeURIComponent(toUrl);
            }
            $.ajax({
                cache: false,
                url: "/Common/ChangeRunTimeDeviceMode",
                type: "post",
                data: { type: npagedevice },
                dataType: "json",
                success: function (result) {
                    if (result.IsSuccess) {
                        window.top.location.href = mobileUrl;
                    }
                },
                error: function () { }
            });
        });
    }
    $(function () {
        if ($("#prevMainFrame").length > 0) {
            $("#prevMainFrame").load(initialDeviceSwitch);
        } else {
            initialDeviceSwitch();
        }
    });
</script>    <div id="mainContentWrapper" style="background-color: transparent; background-image: none; background-repeat: no-repeat;background-position:0 0; background:-moz-linear-gradient(top, none, none);background:-webkit-gradient(linear, left top, left bottom, from(none), to(none));background:-o-linear-gradient(top, none, none);background:-ms-linear-gradient(top, none, none);background:linear-gradient(top, none, none);;
     position: relative; width: 100%;min-width:1200px;background-size: auto;" bgscroll="none">
    <div style="background-color: transparent; background-image: none; background-repeat: no-repeat;background-position:0 0; background:-moz-linear-gradient(top, none, none);background:-webkit-gradient(linear, left top, left bottom, from(none), to(none));background:-o-linear-gradient(top, none, none);background:-ms-linear-gradient(top, none, none);background:linear-gradient(top, none, none);;
         position: relative; width: 100%;min-width:1200px;background-size: auto;" bgscroll="none">
        <div class=" header" cpid="32020" id="smv_Area0" style="width: 80%; height: 110px;  position: relative; margin: 0 auto">
            <div id="smv_tem_59_25" ctype="banner" class="esmartMargin smartAbs smartFixed   " cpid="32020" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="True" pvid="" tareaid="Area0" re-direction="y" daxis="Y" isdeletable="True" style="height: 100px; width: 100%; left: 0px; top: 0px;right:0px;margin:auto;z-index:23;"><div class="yibuFrameContent tem_59_25  banner_Style1  " style="overflow:visible;;"><div class="fullcolumn-inner smAreaC" id="smc_Area0" cid="tem_59_25" style="width:1200px">
    <div id="smv_tem_60_25" ctype="nav" class="esmartMargin smartAbs " cpid="32020" cstyle="Style5" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="tem_59_25" tareaid="Area0" re-direction="all" daxis="All" isdeletable="True" style="height: 24px; width: 120px; right:10%; top: 63px;z-index:12;"><div class="yibuFrameContent tem_60_25  nav_Style5  " style="overflow:visible;;"><div id="nav_tem_60_25" class="nav_pc_t_5">
    <ul class="w-nav" navstyle="style5">
                <li class="w-nav-inner" style="height:24px;line-height:24px;width:100%;">
                    <div class="w-nav-item">
                        <a href="{base_url('index.php/index/index')}" target="_self" class="w-nav-item-link">
                            <span class="w-link-txt">HOME</span>
                        </a>
                        <a href="{base_url('index.php/index/index')}" target="_self" class="w-nav-item-link hover" style="display: none; opacity: 1;">
                            <span class="w-link-txt">HOME</span>
                        </a>
                    </div>
                </li>

    </ul>
</div>
<script>
    $(function () {
        var itemHover, $this, item, itemAll, link;
        $('#nav_tem_60_25 .w-nav').find('.w-subnav').hide();
        $('#nav_tem_60_25 .w-nav').off('mouseenter').on('mouseenter', '.w-nav-inner', function () {
            itemAll = $('#nav_tem_60_25 .w-nav').find('.w-subnav');
            $this = $(this);
            link = $this.find('.w-nav-item-link').eq(1);
            item = $this.find('.w-subnav');
            link.stop().fadeIn(400).css("display", "block");
            item.slideDown();
        }).off('mouseleave').on('mouseleave', '.w-nav-inner', function () {
            $this = $(this);
            item = $this.find('.w-subnav');
            link = $this.find('.w-nav-item-link').eq(1);
            link.stop().fadeOut(400);
            item.stop().slideUp();
        });
        SetNavSelectedStyle('nav_tem_60_25');//选中当前导航
    });
</script></div></div><div id="smv_tem_61_25" ctype="nav" class="esmartMargin smartAbs " cpid="32020" cstyle="Style5" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="tem_59_25" tareaid="Area0" re-direction="all" daxis="All" isdeletable="True" style="height: 24px; width: 120px; right:10%; top: 34px;z-index:12;"><div class="yibuFrameContent tem_61_25  nav_Style5  " style="overflow:visible;;"><div id="nav_tem_61_25" class="nav_pc_t_5">
    <ul class="w-nav" navstyle="style5">
                <li class="w-nav-inner" style="height:24px;line-height:24px;width:100%;">
                    <div class="w-nav-item">
                        <a href="{base_url('index.php/news/index')}" target="_self" class="w-nav-item-link">
                            <span class="w-link-txt">返回</span>
                        </a>
                        <a href="{base_url('index.php/news/index')}" target="_self" class="w-nav-item-link hover" style="display: none; opacity: 1;">
                            <span class="w-link-txt">返回</span>
                        </a>
                    </div>
                </li>

    </ul>
</div>
<script>
    $(function () {
        var itemHover, $this, item, itemAll, link;
        $('#nav_tem_61_25 .w-nav').find('.w-subnav').hide();
        $('#nav_tem_61_25 .w-nav').off('mouseenter').on('mouseenter', '.w-nav-inner', function () {
            itemAll = $('#nav_tem_61_25 .w-nav').find('.w-subnav');
            $this = $(this);
            link = $this.find('.w-nav-item-link').eq(1);
            item = $this.find('.w-subnav');
            link.stop().fadeIn(400).css("display", "block");
            item.slideDown();
        }).off('mouseleave').on('mouseleave', '.w-nav-inner', function () {
            $this = $(this);
            item = $this.find('.w-subnav');
            link = $this.find('.w-nav-item-link').eq(1);
            link.stop().fadeOut(400);
            item.stop().slideUp();
        });
        SetNavSelectedStyle('nav_tem_61_25');//选中当前导航
    });
</script></div></div><div id="smv_tem_68_28" ctype="logoimage" class="esmartMargin smartAbs " cpid="32020" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="tem_59_25" tareaid="Area0" re-direction="all" daxis="All" isdeletable="True" style="height: 48px; width: 49px; left: 20%; top: 29px;z-index:15;"><div class="yibuFrameContent tem_68_28  logoimage_Style1  " style="overflow:visible;;">
<div class="w-image-box" data-filltype="0" id="div_tem_68_28" style="height: 48px; width: 49px;">
    <a target="_self">
        <img src="{resource_url('img/tdkcImg/6056936.png')}" alt="" title="" id="img_smv_tem_68_28" style="width: 49px; height:48px;">
    </a>
</div>

<script type="text/javascript">
    $(function () {
        InitImageSmv("tem_68_28", "49", "48", "0");
    });
</script>

</div></div><div id="smv_tem_63_25" ctype="text" class="esmartMargin smartAbs " cpid="32020" cstyle="Style1" ccolor="Item5" areaid="Area0" iscontainer="False" pvid="tem_59_25" tareaid="Area0" re-direction="all" daxis="All" isdeletable="True" style="height: 61px; width: 406px; left: 25%; top: 24px;z-index:14;"><div class="yibuFrameContent tem_63_25  text_Style1  " style="overflow:hidden;;"><div id="txt_tem_63_25" style="height: 100%;">
    <div class="editableContent" id="txtc_tem_63_25" style="height: 100%; word-wrap:break-word;">
        <p><span style="line-height:1.5"><strong><span style="font-size:24px"><span style="color:#ffffff">慈溪市土地勘测规划设计院有限公司</span></span></strong></span></p>

<p><span style="color:#ffffff"><span style="line-height:1.5"><span style="font-size:11px">Cixi land survey planning and design institute co. LTD</span></span></span></p>

<p>&nbsp;</p>

    </div>
</div>
</div></div></div>
<div id="bannerWrap_tem_59_25" class="fullcolumn-outer" style="position: absolute; top: 0px; bottom: 0px; left: 0px; width: 1872px;">
</div>

<script type="text/javascript">

    $(function () {
        var resize = function () {
            $("#smv_tem_59_25 >.yibuFrameContent>.fullcolumn-inner").width($("#smv_tem_59_25").parent().width());
            $('#bannerWrap_tem_59_25').fullScreen(function (t) {
                if (VisitFromMobile()) {
                    t.css("min-width", t.parent().width())
                }
            });
        }
        $(window).resize(function (e) {
            if (e.target == this) {
                resize();
            }
        });
        resize();
    });
</script>
</div></div>
        </div>
    </div>
   
<!-- end of mainContentWrapper -->
	