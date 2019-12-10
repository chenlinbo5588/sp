{include file="common/header.tpl"}
  <link href="{resource_url('css/tdkcCss/461386_Pc_zh-CN.css')}" rel="stylesheet">
  <link href="{resource_url('css/tdkcCss/view.css')}" rel="stylesheet" type="text/css" /> 
  <link id="lz-preview-css" href="{resource_url('css/tdkcCss/atlas-preview.css')}" rel="stylesheet">
  <script type="text/javascript" id="SuperSlide" src="{resource_url('js/tdkcjs/jquery.SuperSlide.2.1.1.js')}"></script>
  <script type="text/javascript" id="jqPaginator" src="{resource_url('js/tdkcjs/jqPaginator.min.js')}"></script>
  <script type="text/javascript" id="lz-slider" src="{resource_url('js/tdkcjs/lz-slider.min.js')}"></script>
  <script type="text/javascript" id="lz-preview" src="{resource_url('js/tdkcjs/lz-preview.min.js')}"></script>
  <script type="text/javascript" id="jssor-all" src="{resource_url('js/tdkcjs/jssor.slider-22.2.16-all.min.js')}"></script>
  <script type="text/javascript" id="slideshown" src="{resource_url('js/tdkcjs/slideshow.js')}"></script>
   <input type="hidden" id="pageinfo" value="461386" data-type="1" data-device="Pc" data-entityid="461386" /> 
  <input id="txtDeviceSwitchEnabled" value="show" type="hidden" /> 
  <input type="hidden" id="secUrl" data-host="c1797065108ffy.scd.wezhan.cn" data-pathname="/gcal_tk" data-search="" data-hash="" /> 
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
</script> 
       <script>
    $(function () {
        //宽度需要减去边框值
        var liInner = $("#nav_tem_4_19 .w-nav-inner");
        var rightBorder = parseInt(liInner.css("border-right-width"));
        var leftBorder = parseInt(liInner.css("border-left-width"));
        var topBorder = parseInt(liInner.css("border-top-width"));
        var bottomBorder = parseInt(liInner.css("border-bottom-width"));
        var totalWidth = $("#nav_tem_4_19").width();
        //总边框
        var count = liInner.length;
        var totalBorderWidth = (rightBorder + leftBorder) * count;
        var width = 0;
        if (count > 1) {
            //边距
            var marginLeft = parseInt($(liInner[1]).css("margin-left"));
            var totalMargin = marginLeft * count * 2;
            width = totalWidth - totalBorderWidth - totalMargin;
        } else {
            width = totalWidth - totalBorderWidth;
        }
        var totalHeight = liInner.height()-20;
        $('#nav_tem_4_19 .w-nav').height(totalHeight);
        $("#smv_tem_4_19").height(totalHeight + 20);
        liInner.height(totalHeight - topBorder - bottomBorder).css("line-height", totalHeight - topBorder - bottomBorder+"px");
        var realWidth = (width / count) + "px";
        //liInner.css("width", realWidth);


        $('#nav_tem_4_19 .w-nav').find('.w-subnav').hide();
        var $this, item, itemAll;
        if ("True".toLocaleLowerCase() == "true") {
        } else {
            $("#nav_tem_4_19 .w-subnav-inner").css("width", "120" + "px");
            $("#nav_tem_4_19 .w-subnav").css("width", "120" + "px");
        }
        $('#nav_tem_4_19 .w-nav').off('mouseenter').on('mouseenter', '.w-nav-inner', function () {
            itemAll = $('#nav_tem_4_19 .w-nav').find('.w-subnav');
            $this = $(this);
            item = $this.find('.w-subnav');
            item.slideDown(function () {
                item.css({
                    height: ''
                })
            });
        }).off('mouseleave').on('mouseleave', '.w-nav-inner', function () {
            item = $(this).find('.w-subnav');
            item.stop().slideUp();
        });
        SetNavSelectedStyleForInner('nav_tem_4_19');//选中当前导航
    });
</script>
      </div>
     </div> 
    </div> 
   </div> 
   <div class="main-layout-wrapper" id="smv_AreaMainWrapper" style="background-color: transparent; background-image: none;
         background-repeat: no-repeat;background-position:0 0; background:-moz-linear-gradient(top, none, none);background:-webkit-gradient(linear, left top, left bottom, from(none), to(none));background:-o-linear-gradient(top, none, none);background:-ms-linear-gradient(top, none, none);background:linear-gradient(top, none, none);;background-size: auto;" bgscroll="none"> 
    <div class="main-layout" id="tem-main-layout11" style="width: 100%;"> 
     <div style="display: none"> 
     </div> 
     <div class="" id="smv_MainContent" rel="mainContentWrapper" style="width: 100%; min-height: 300px; position: relative; "> 
      <div class="smvWrapper" style="min-width:1180px;  position: relative; background-color: transparent; background-image: none; background-repeat: no-repeat; background:-moz-linear-gradient(top, none, none);background:-webkit-gradient(linear, left top, left bottom, from(none), to(none));background:-o-linear-gradient(top, none, none);background:-ms-linear-gradient(top, none, none);background:linear-gradient(top, none, none);;background-position:0 0;background-size:auto;" bgscroll="none">
       <div class="smvContainer" id="smv_Main" cpid="461386" style="min-height:400px;width:1180px;height:750px;  position: relative; ">
        <div id="smv_con_26_24" ctype="slideset" class="esmartMargin smartAbs " cpid="461386" cstyle="Style2" ccolor="Item0" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 656px; width: 1147px; left: 32px; top: 158px;z-index:10;" selectarea="Area0">
         <div class="yibuFrameContent con_26_24  slideset_Style2  " style="overflow:visible;;"> 
          <!--w-slide--> 
          <div class="w-slide" id="slider_smv_con_26_24" data-jssor-slider="1" style="visibility: visible; width: 1147px; height: 656px;"> 
           <div style="position: absolute; display: block; top: 0px; left: 0px; width: 1147px; height: 656px;">
            <div style="position: absolute; display: block; top: 0px; left: 0px; width: 1147px; height: 656px;" data-scale-ratio="1">
             <div class="w-slide-inner" data-u="slides" style="z-index: 0; position: absolute; top: 0px; left: 0px;">
              <div style="top: 0px; left: 0px; width: 1147px; height: 656px; position: absolute; z-index: 0; pointer-events: none; display: none;"></div>
             </div>
             <div class="w-slide-inner" data-u="slides" style="z-index: 0; position: absolute; overflow: hidden; top: 0px; left: 0px;">
              <div style="top: 0px; left: 0px; width: 1147px; height: 656px; position: absolute; background-color: rgb(0, 0, 0); opacity: 0; z-index: 0;"></div> 
              <div class="content-box" data-area="Area0" style="z-index: 1; top: 0px; left: 0px; width: 1147px; height: 656px; position: absolute; overflow: hidden;"> 
               <div id="smc_Area0" cid="con_26_24" class="smAreaC slideset_AreaC" style="z-index: 1; width: 1180px; position: relative; margin: 0px auto;"> 
                <div id="smv_con_27_57" ctype="area" class="esmartMargin smartAbs " cpid="461386" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="True" pvid="con_26_24" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 480px; width: 1127px; left: 10px; top: 15px; z-index: 3;">
                 <div class="yibuFrameContent con_27_57  area_Style1  " style="overflow: visible; z-index: 1;">
                  <div class="w-container" style="z-index: 1;"> 
                   <div class="smAreaC" id="smc_Area0" cid="con_27_57" style="z-index: 1;"> 
                    <div id="smv_con_72_58" ctype="altas" class="esmartMargin smartAbs " cpid="461386" cstyle="Style2" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_27_57" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 182px; width: 780px; left: 304px; top: 37px; z-index: 25;">
                     <div class="yibuFrameContent con_72_58  altas_Style2  " style="overflow: visible; z-index: 1;">
                      <div class="w-atlas xn-resize" id="altas_con_72_58" style="z-index: 1;"> 
                       <ul class="w-atlas-ul" id="ulList_con_72_58" style="z-index: 1;"> 
                        <li style="z-index: 1;"> <a style="z-index: 1;"> <img class="lazyload NoCutFill" src="{resource_url('img/tdkcImg/201012221426469036.jpg')}" data-original="{resource_url('img/tdkcImg/201012221426469036.jpg')}" alt="慈溪市开心基础设施规划" style="z-index: 1; display: block;" /> <h3 style="margin-top: 150px; width: 232px; z-index: 9;">慈溪市开心基础设施规划</h3> </a> </li> 
                        <li style="z-index: 1;"> <a style="z-index: 1;"> <img class="lazyload NoCutFill" src="{resource_url('img/tdkcImg/201012221432874070.jpg')}" data-original="{resource_url('img/tdkcImg/201012221432874070.jpg')}" alt="慈溪市水云浦-半掘浦段围涂地开发造地项目" style="z-index: 1; display: block;" /> <h3 style="margin-top: 150px; width: 232px; z-index: 9;">慈溪市水云浦-半掘浦段围涂地开发造地项目</h3> </a> </li> 
                        <li style="z-index: 1;"> <a style="z-index: 1;"> <img class="lazyload NoCutFill" src="{resource_url('img/tdkcImg/201012221435019953.jpg')}" data-original="{resource_url('img/tdkcImg/201012221435019953.jpg')}" alt="慈溪市周巷镇西三畈土地整理项目" style="z-index: 1; display: block;" /> <h3 style="margin-top: 150px; width: 232px; z-index: 9;">慈溪市周巷镇西三畈土地整理项目</h3> </a> </li> 
                       </ul> 
                      </div> 
                      <script type="text/javascript" style="z-index: 1;">
	var con_72_58_firstClick = true;
    function callback_con_72_58() {
        con_72_58_Init();
    }
    function  con_72_58_Init() {
          $("#smv_con_72_58 .w-atlas-ul li img").hover(function () {
            var hovermargintop = parseInt("150px") - 5;
            var hoverwidth = parseInt("252") - 10;
            var hoverheight = parseInt("180") - 10;
            $(this).css("width", hoverwidth + "px");
            $(this).css("height", hoverheight + "px");
            $(this).siblings("h3").css("margin-top", hovermargintop + "px");
            $(this).siblings("h3").css("marginLeft", "5px").css("paddingRight", "0px");
        }, function () {
            var width = parseInt("252");
            var height = parseInt("180");
            $(this).css("width", width + "px");
            $(this).css("height", height + "px");
            $(this).siblings("h3").css("margin-top", "150px");
            $(this).siblings("h3").css("marginLeft", "0px").css("paddingRight", "10px");
            });

          $('#smv_con_72_58').lzpreview({
                cssLink:'/Content/css/atlas-preview.css',
                pageSize: 6,//每页最大图片数
                imgUrl: ["../../../../../static/img/tdkcImg/201012221426469036.jpg","../../../../../static/img/tdkcImg/201012221432874070.jpg","../../../../../static/img/tdkcImg/201012221435019953.jpg","../../../../../static/img/tdkcImg/2010122214253657269.jpg","../../../../../static/img/tdkcImg/2010122214262160424.jpg","../../../../../static/img/tdkcImg/2016371444743984.jpg"],
                imgAlt: ["慈溪市开心基础设施规划","慈溪市水云浦-半掘浦段围涂地开发造地项目","慈溪市周巷镇西三畈土地整理项目","慈溪市开心农场规划","慈溪市横河镇上剑山村土地综合整治项目","胜山至陆埠公路（横河-余慈界段）方案"],
                imgLink: ["javascript:void(0)","javascript:void(0)","javascript:void(0)","javascript:void(0)","javascript:void(0)","javascript:void(0)"],
                imgTarget: ["_self","_self","_self","_self","_self","_self"]
                });

          $('#smv_con_72_58').lzpreview('arrowSwitch', 'on'=="on" ? true : false);
         }
    $(function () {
        con_72_58_Init(); 
     });
    </script> 
                     </div>
                    </div>
                    <div id="smv_con_35_13" ctype="text" smanim="{ &quot;delay&quot;:0.3,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Left&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461386" cstyle="Style1" ccolor="Item1" areaid="Area0" iscontainer="False" pvid="con_27_57" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 126px; width: 196px; left: 45px; top: 37px; z-index: 22; opacity: 1;" sm-finished="true" smexecuted="1">
                     <div class="yibuFrameContent con_35_13  text_Style1  " style="overflow: hidden; z-index: 1;">
                      <div id="txt_con_35_13" style="height: 100%; z-index: 1;"> 
                       <div class="editableContent" id="txtc_con_35_13" style="height: 100%; word-wrap: break-word; z-index: 1;"> 
                        <p style="z-index: 1;"><span style="color: rgb(68, 68, 68); z-index: 1;"><span style="font-size: 30px; z-index: 1;"><span style="line-height: 1.2; z-index: 1;"><span style="font-family: Arial, Helvetica, sans-serif; z-index: 1;">Case</span></span></span></span></p> 
                        <p style="z-index: 1;"><span style="color: rgb(68, 68, 68); z-index: 1;"><span style="font-size: 30px; z-index: 1;"><span style="line-height: 1.2; z-index: 1;"><span style="font-family: Arial, Helvetica, sans-serif; z-index: 1;">Appreciations</span></span></span></span></p> 
                        <p style="z-index: 1;">&nbsp;</p> 
                        <p style="z-index: 1;"><span style="color: rgb(68, 68, 68); z-index: 1;"><span style="font-size: 14px; z-index: 1;"><strong style="z-index: 1;"><span style="font-family: &quot;Microsoft YaHei&quot;; z-index: 1;"><span style="line-height: 1.2; z-index: 1;">案例欣赏</span></span></strong></span></span></p> 
                       </div> 
                      </div> 
                     </div>
                    </div>
                    <div id="smv_con_71_15" ctype="altas" class="esmartMargin smartAbs " cpid="461386" cstyle="Style2" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_27_57" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 180px; width: 1041px; left: 42px; top: 224px; z-index: 1;">
                     <div class="yibuFrameContent con_71_15  altas_Style2  " style="overflow: visible; z-index: 1;">
                      <div class="w-atlas xn-resize" id="altas_con_71_15" style="z-index: 1;"> 
                       <ul class="w-atlas-ul" id="ulList_con_71_15" style="z-index: 1;"> 
                        <li style="z-index: 1;"> <a style="z-index: 1;"> <img class="lazyload NoCutFill" src="../../../../../static/img/tdkcImg/2010122214253657269.jpg" data-original="../../../../../static/img/tdkcImg/2010122214253657269.jpg" alt="慈溪市开心农场规划" style="z-index: 1; display: block;" /> <h3 style="margin-top: 150px; width: 232px; z-index: 9;">慈溪市开心农场规划</h3> </a> </li> 
                        <li style="z-index: 1;"> <a style="z-index: 1;"> <img class="lazyload NoCutFill" src="../../../../../static/img/tdkcImg/2010122214262160424.jpg" data-original="../../../../../static/img/tdkcImg/2010122214262160424.jpg" alt="慈溪市横河镇上剑山村土地综合整治项目" style="z-index: 1; display: block;" /> <h3 style="margin-top: 150px; width: 232px; z-index: 9;">慈溪市横河镇上剑山村土地综合整治项目</h3> </a> </li> 
                        <li style="z-index: 1;"> <a style="z-index: 1;"> <img class="lazyload NoCutFill" src="../../../../../static/img/tdkcImg/2016371444743984.jpg" data-original="../../../../../static/img/tdkcImg/2016371444743984.jpg" alt="胜山至陆埠公路（横河-余慈界段）方案" style="z-index: 1; display: block; width: 252px; height: 180px;" /> <h3 style="margin-top: 150px; width: 232px; z-index: 9; margin-left: 0px; padding-right: 10px;">胜山至陆埠公路（横河-余慈界段）方案</h3> </a> </li> 
                       </ul> 
                      </div> 
                      <script type="text/javascript" style="z-index: 1;">
	var con_71_15_firstClick = true;
    function callback_con_71_15() {
        con_71_15_Init();
    }
    function  con_71_15_Init() {
          $("#smv_con_71_15 .w-atlas-ul li img").hover(function () {
            var hovermargintop = parseInt("150px") - 5;
            var hoverwidth = parseInt("252") - 10;
            var hoverheight = parseInt("180") - 10;
            $(this).css("width", hoverwidth + "px");
            $(this).css("height", hoverheight + "px");
            $(this).siblings("h3").css("margin-top", hovermargintop + "px");
            $(this).siblings("h3").css("marginLeft", "5px").css("paddingRight", "0px");
        }, function () {
            var width = parseInt("252");
            var height = parseInt("180");
            $(this).css("width", width + "px");
            $(this).css("height", height + "px");
            $(this).siblings("h3").css("margin-top", "150px");
            $(this).siblings("h3").css("marginLeft", "0px").css("paddingRight", "10px");
            });

          $('#smv_con_71_15').lzpreview({
                cssLink:'/Content/css/atlas-preview.css',
              
                 pageSize: 6,//每页最大图片数
                imgUrl: ["../../../../../static/img/tdkcImg/2010122214253657269.jpg","../../../../../static/img/tdkcImg/2010122214262160424.jpg","../../../../../static/img/tdkcImg/2016371444743984.jpg","../../../../../static/img/tdkcImg/201012221426469036.jpg","../../../../../static/img/tdkcImg/201012221432874070.jpg","../../../../../static/img/tdkcImg/201012221435019953.jpg"],
                imgAlt: ["慈溪市开心农场规划","慈溪市横河镇上剑山村土地综合整治项目","胜山至陆埠公路（横河-余慈界段）方案","慈溪市开心基础设施规划","慈溪市水云浦-半掘浦段围涂地开发造地项目","慈溪市周巷镇西三畈土地整理项目",],
                imgLink: ["javascript:void(0)","javascript:void(0)","javascript:void(0)","javascript:void(0)","javascript:void(0)","javascript:void(0)","javascript:void(0)"],
                imgTarget: ["_self","_self","_self","_self","_self","_self","_self"]
                });

          $('#smv_con_71_15').lzpreview('arrowSwitch', 'on'=="on" ? true : false);
         }
    $(function () {
        con_71_15_Init();
     });
    </script> 
                     </div>
                    </div>
                    <div id="smv_con_75_16" ctype="area" class="esmartMargin smartAbs " cpid="461386" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="True" pvid="con_27_57" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 180px; width: 252px; left: 828px; top: 415px; z-index: 17;">
                     <div class="yibuFrameContent con_75_16  area_Style1  " style="overflow: visible; z-index: 1;">
                     
                      </div>
                     </div>
                    </div> 
                   </div> 
                  </div>
                 </div>
                </div> 
               </div> 
               <div class="content-box-inner" style="background-image: none; background-color: rgb(255, 255, 255); opacity: 1; z-index: 1;"></div> 
               <div style="top: 0px; left: 0px; width: 1147px; height: 500px; z-index: 1000; display: none;"></div>
              </div> 
              <div class="content-box" data-area="Area1" style="z-index: 1; top: 0px; left: 1147px; width: 1147px; height: 656px; position: absolute; overflow: hidden;"> 
               <div id="smc_Area1" cid="con_26_24" class="smAreaC slideset_AreaC" style="z-index: 1; width: 1180px; position: relative; margin: 0px auto;"> 
                <div id="smv_con_77_6" ctype="area" class="esmartMargin smartAbs " cpid="461386" cstyle="Style1" ccolor="Item0" areaid="Area1" iscontainer="True" pvid="con_26_24" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 635px; width: 1127px; left: 10px; top: 15px; z-index: 3;">
                 <div class="yibuFrameContent con_77_6  area_Style1  " style="overflow: visible; z-index: 1;">
                  <div class="w-container" style="z-index: 1;"> 
                   <div class="smAreaC" id="smc_Area0" cid="con_77_6" style="z-index: 1;"> 
                    <div id="smv_con_79_6" ctype="text" smanim="{ &quot;delay&quot;:0.3,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Left&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461386" cstyle="Style1" ccolor="Item1" areaid="Area0" iscontainer="False" pvid="con_77_6" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 126px; width: 196px; left: 57px; top: 56px; z-index: 22; animation-duration: 0.75s; animation-delay: 0.3s; animation-iteration-count: 1; opacity: 0;" sm-finished="true">
                     <div class="yibuFrameContent con_79_6  text_Style1  " style="overflow: hidden; z-index: 1;">
                      <div id="txt_con_79_6" style="height: 100%; z-index: 1;"> 
                       <div class="editableContent" id="txtc_con_79_6" style="height: 100%; word-wrap: break-word; z-index: 1;"> 
                        <p style="z-index: 1;"><span style="color: rgb(255, 255, 255); z-index: 1;"><span style="font-size: 30px; z-index: 1;"><span style="line-height: 1.2; z-index: 1;"><span style="font-family: Arial, Helvetica, sans-serif; z-index: 1;">Case</span></span></span></span></p> 
                        <p style="z-index: 1;"><span style="color: rgb(255, 255, 255); z-index: 1;"><span style="font-size: 30px; z-index: 1;"><span style="line-height: 1.2; z-index: 1;"><span style="font-family: Arial, Helvetica, sans-serif; z-index: 1;">Appreciations</span></span></span></span></p> 
                        <p style="z-index: 1;">&nbsp;</p> 
                        <p style="z-index: 1;"><span style="color: rgb(255, 255, 255); z-index: 1;"><span style="font-size: 14px; z-index: 1;"><strong style="z-index: 1;"><span style="font-family: &quot;Microsoft YaHei&quot;; z-index: 1;"><span style="line-height: 1.2; z-index: 1;">案例欣赏</span></span></strong></span></span></p> 
                       </div> 
                      </div> 
                     </div>
                    </div>
                    <div id="smv_con_81_6" ctype="area" class="esmartMargin smartAbs " cpid="461386" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="True" pvid="con_77_6" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 144px; width: 213px; left: 836px; top: 430px; z-index: 17;">
                     <div class="yibuFrameContent con_81_6  area_Style1  " style="overflow: visible; z-index: 1;">
                      <div class="w-container" style="z-index: 1;"> 
                       <div class="smAreaC" id="smc_Area0" cid="con_81_6" style="z-index: 1;"> 
                        <div id="smv_con_82_6" ctype="button" smanim="{ &quot;delay&quot;:0.5,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;&quot;,&quot;animationName&quot;:&quot;zoomIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461386" cstyle="Style1" ccolor="Item1" areaid="Area0" iscontainer="False" pvid="con_81_6" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 38px; width: 135px; left: 34px; top: 44px; z-index: 3; animation-duration: 0.75s; animation-delay: 0.5s; animation-iteration-count: 1; opacity: 0;" sm-finished="true">
                         <div class="yibuFrameContent con_82_6  button_Style1  " style="overflow: visible; z-index: 1;">
                          <a target="_self" class="w-button f-ellipsis" style="width: 133px; height: 36px; line-height: 36px; z-index: 1;"> <span class="w-button-position" style="z-index: 1;"> <em class="w-button-text f-ellipsis" style="z-index: 1;"> <i class="mw-iconfont w-button-icon w-icon-hide" style="z-index: 1;"></i>VIEW MORE &gt; </em> </span> </a> 
                          <script type="text/javascript" style="z-index: 1;">
        $(function () {
        });
    </script> 
                         </div>
                        </div> 
                       </div> 
                      </div>
                     </div>
                    </div>
                                        <div id="smv_con_78_6" ctype="altas" class="esmartMargin smartAbs " cpid="461386" cstyle="Style2" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_77_6" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 182px; width: 780px; left: 310px; top: 48px; z-index: 25;">
                     <div class="yibuFrameContent con_78_6  altas_Style2  " style="overflow: visible; z-index: 1;">
                      <div class="w-atlas xn-resize" id="altas_con_78_6" style="z-index: 1;"> 
                       <ul class="w-atlas-ul" id="ulList_con_78_6" style="z-index: 1;"> 
                        <li style="z-index: 1;"> <a style="z-index: 1;"> <img class="lazyload NoCutFill" src="../../../../../static/img/tdkcImg/-18999.jpg" data-original="../../../../../static/img/tdkcImg/-18999.jpg" alt="it产品2" style="z-index: 1; display: block;" /> </a> </li> 
                        <li style="z-index: 1;"> <a style="z-index: 1;"> <img class="lazyload NoCutFill" src="../../../../../static/img/tdkcImg/-18998.jpg" data-original="../../../../../static/img/tdkcImg/-18998.jpg" alt="it产品1" style="z-index: 1;" /> </a> </li> 
                        <li style="z-index: 1;"> <a style="z-index: 1;"> <img class="lazyload NoCutFill" src="../../../../../static/img/tdkcImg/-19001.jpg" data-original="../../../../../static/img/tdkcImg/-19001.jpg" alt="it产品4" style="z-index: 1;" /> </a> </li> 
                       </ul> 
                      </div> 
                      <script type="text/javascript" style="z-index: 1;">
	var con_78_6_firstClick = true;
    function callback_con_78_6() {
        con_78_6_Init();
    }
    function  con_78_6_Init() {
          $("#smv_con_78_6 .w-atlas-ul li img").hover(function () {
            var hovermargintop = parseInt("150px") - 5;
            var hoverwidth = parseInt("252") - 10;
            var hoverheight = parseInt("180") - 10;
            $(this).css("width", hoverwidth + "px");
            $(this).css("height", hoverheight + "px");
            $(this).siblings("h3").css("margin-top", hovermargintop + "px");
            $(this).siblings("h3").css("marginLeft", "5px").css("paddingRight", "0px");
        }, function () {
            var width = parseInt("252");
            var height = parseInt("180");
            $(this).css("width", width + "px");
            $(this).css("height", height + "px");
            $(this).siblings("h3").css("margin-top", "150px");
            $(this).siblings("h3").css("marginLeft", "0px").css("paddingRight", "10px");
            });

          $('#smv_con_78_6').lzpreview({
                cssLink:'/Content/css/atlas-preview.css',
                pageSize: 3,//每页最大图片数
                imgUrl: ["../../../../../static/img/tdkcImg/-18999.jpg","../../../../../static/img/tdkcImg/-18998.jpg","../../../../../static/img/tdkcImg/-19001.jpg","../../../../../static/img/tdkcImg/-12483.jpg","../../../../../static/img/tdkcImg/-12482.jpg","../../../../../static/img/tdkcImg/-12481.jpg"],
                imgAlt: ["it产品22","it产品12","it产品42","UI设计","创意设计","创意设计"],
                imgLink: ["javascript:void(0)","javascript:void(0)","javascript:void(0)","javascript:void(0)","javascript:void(0)","javascript:void(0)"],
                imgTarget: ["_self","_self","_self","_self","_self","_self"]
                });

          $('#smv_con_78_6').lzpreview('arrowSwitch', 'on'=="on" ? true : false);
         }
    $(function () {
        con_78_6_Init();
     });
    </script> 
                     </div>
                    </div> 
                   </div> 
                  </div>
                 </div>
                </div> 
               </div> 
               <div class="content-box-inner" style="background-image: none; background-color: rgb(255, 255, 255); opacity: 1; z-index: 1;"></div> 
               <div style="top: 0px; left: 0px; width: 1147px; height: 656px; z-index: 1000; display: none;"></div>
              </div> 
             </div>
            </div>
           </div> 
           <!-- Bullet Navigator --> 
           <div style="position: absolute; display: block; right: 16px; bottom: 16px; left: 556.5px; width: 34px; height: 12px;">
            <div data-u="navigator" class="w-slide-btn-box  f-hide " data-autocenter="1" data-scale-ratio="1" style="left: 0px; width: 34px; height: 12px; top: 0px;"> 
             <!-- bullet navigator item prototype --> 
             <div class="w-slide-btn w-slide-btnav" data-u="prototype" data-jssor-button="1" style="position: absolute; left: 0px; top: 0px;"></div>
             <div class="w-slide-btn" data-u="prototype" data-jssor-button="1" style="position: absolute; left: 22px; top: 0px;"></div>
            </div>
           </div> 
           <!-- 1Arrow Navigator --> 
          </div> 
          <!--/w-slide--> 
          <script type="text/javascript">
    con_26_24_page = 1;
    con_26_24_firstTime = true;
    con_26_24_sliderset3_init = function () {
        var jssor_1_options_con_26_24 = {
            $AutoPlay: "False"=="True"?false:"off" == "on",//自动播放
            $PlayOrientation: 1,//2为向上滑，1为向左滑
            $Loop: 1,//循环
            $Idle: parseInt("3000"),//切换间隔
            $SlideDuration: "1000",//延时
            $SlideEasing: $Jease$.$OutQuint,
            
             $SlideshowOptions: {
                $Class: $JssorSlideshowRunner$,
                $Transitions: GetSlideAnimation("3", "1000"),
                $TransitionsOrder: 1
            },
            
            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
            },
            $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$,
                $ActionMode: "1"
            }
        };
        //初始化幻灯
        var slide = new $JssorSlider$("slider_smv_con_26_24", jssor_1_options_con_26_24);
        $('#smv_con_26_24').data('jssor_slide', slide);

        //幻灯栏目自动或手动切换时触发的事件
        slide.$On($JssorSlider$.$EVT_PARK, function (slideIndex, fromIndex) {
            var $slideWrapper = $("#slider_smv_con_26_24 .w-slide-inner:last");
            var $fromSlide = $slideWrapper.find(".content-box:eq(" + fromIndex + ")");
            var $curSlide = $slideWrapper.find(".content-box:eq(" + slideIndex + ")");
            var $nextSlide = $slideWrapper.find(".content-box:eq(" + (slideIndex + 1) + ")");
            $("#smv_con_26_24").attr("selectArea", $curSlide.attr("data-area"));
            $fromSlide.find(".animated").smanimate("stop");
            $curSlide.find(".animated").smanimate("stop");
            $nextSlide.find(".animated").smanimate("stop");
            $("#switch_con_26_24 .page").html(slideIndex + 1);
            $curSlide.find(".animated").smanimate("replay");
            return false;
        });
        //切换栏点击事件
        $("#switch_con_26_24 .left").unbind("click").click(function () {
            if(con_26_24_page==1){
                con_26_24_page =2;
            } else {
                con_26_24_page = con_26_24_page - 1;
            }
            $("#switch_con_26_24 .page").html(con_26_24_page);
            slide.$Prev();
            return false;
        });
        $("#switch_con_26_24 .right").unbind("click").click(function () {
            if(con_26_24_page==2){
                con_26_24_page = 1;
        } else {
        con_26_24_page = con_26_24_page + 1;
    }
    $("#switch_con_26_24 .page").html(con_26_24_page);
    slide.$Next();
    return false;
    });
    };
    $(function () {
        //获取幻灯显示动画类型
        con_26_24_sliderset3_init();
        var areaId = $("#smv_con_26_24").attr("tareaid");
        if(areaId==""){
            var mainWidth = $("#smv_Main").width();
            $("#smv_con_26_24 .slideset_AreaC").css({ "width":mainWidth+"px","position":"relative","margin":"0 auto" });
        }else{
            var controlWidth = $("#smv_con_26_24").width();
            $("#smv_con_26_24 .slideset_AreaC").css({ "width":controlWidth+"px","position":"relative","margin":"0 auto" });
        }
        $("#smv_con_26_24").attr("selectArea", "Area0");

        var arrowHeight = $('#slider_smv_con_26_24 .w-slide-arrowl').eq(-1).outerHeight();
        var arrowTop = (18 - arrowHeight) / 2;
        $('#slider_smv_con_26_24 .w-slide-arrowl').eq(-1).css('top', arrowTop);
        $('#slider_smv_con_26_24 .w-slide-arrowr').eq(-1).css('top', arrowTop);
    });

</script> 
         </div>
        </div>
        <div id="smv_con_51_46" ctype="text" smanim="{ &quot;delay&quot;:0.75,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;&quot;,&quot;animationName&quot;:&quot;fadeIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461386" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 33px; width: 360px; left: 410px; top: 65px; z-index: 26; opacity: 1;" sm-finished="true" smexecuted="1">
         <div class="yibuFrameContent con_51_46  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_51_46" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_51_46" style="height: 100%; word-wrap:break-word;"> 
            <p style="text-align:center"><span style="color:#444444"><span style="font-family:&quot;Microsoft YaHei&quot;; font-size:24px">案例中心 / </span></span><span style="font-family:Arial,Helvetica,sans-serif"><span style="color:#000000; font-size:24px">&nbsp;</span><span style="color:#bdc3c7"><span style="font-size:24px">Case center</span></span></span></p> 
           </div> 
          </div> 
         </div>
        </div>
       </div>
      </div>
     </div>
    </div>
   </div>
  </div>
  {include file="common/footer.tpl"}
	
