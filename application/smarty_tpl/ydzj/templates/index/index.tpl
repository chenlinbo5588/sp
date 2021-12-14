{include file="common/header.tpl"}
  <script type='text/javascript' id='jssor-all' src="{resource_url('js/tdkcJs/jssor.slider-22.2.16-all.min.js')}" ></script><script type='text/javascript' id='jqueryzoom' src="{resource_url('js/tdkcJs/jquery.jqueryzoom.js')}" ></script><script type='text/javascript' id='slideshow' src="{resource_url('js/tdkcJs/slideshow.js')}" ></script><script type='text/javascript' id='slideshown' src="{resource_url('js/tdkcJs/slideshow.js')}" ></script>
  <link href="{resource_url('css/tdkcCss/view.css')}" rel="stylesheet" type="text/css" /> 
  <link href="{resource_url('css/tdkcCss/459757_Pc_zh-CN.css')}" rel="stylesheet" />
  <input type="hidden" id="pageinfo" value="459757" data-type="1" data-device="Pc" data-entityid="459757" /> 
  <input id="txtDeviceSwitchEnabled" value="show" type="hidden" /> 
  
  <input type="hidden" id="secUrl" /> 
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
      <div class="smvWrapper" style="min-width:1200px;  position: relative; background-color: transparent; background-image: none; background-repeat: no-repeat; background:-moz-linear-gradient(top, none, none);background:-webkit-gradient(linear, left top, left bottom, from(none), to(none));background:-o-linear-gradient(top, none, none);background:-ms-linear-gradient(top, none, none);background:linear-gradient(top, none, none);;background-position:0 0;background-size:auto;" bgscroll="none">
       <div class="smvContainer" id="smv_Main" cpid="459757" style="min-height:400px;width:1200px;height:4410px;  position: relative; ">
        <div id="smv_con_23_31" ctype="banner" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="y" daxis="Y" isdeletable="True" style="height: 599px; width: 100%; left: 0px; top: 3649px;z-index:2;">
         <div class="yibuFrameContent con_23_31  banner_Style1  " style="overflow:visible;;">
          <div class="fullcolumn-inner smAreaC" id="smc_Area0" cid="con_23_31" style="width:1200px"> 
           <div id="smv_con_177_31" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item3" areaid="Area0" iscontainer="False" pvid="con_23_31" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 45px; width: 265px; left: 467px; top: 60px;z-index:3;">
            <div class="yibuFrameContent con_177_31  text_Style1  " style="overflow:hidden;;">
             <div id="txt_con_177_31" style="height: 100%;"> 
              <div class="editableContent" id="txtc_con_177_31" style="height: 100%; word-wrap:break-word;"> 
               <p style="text-align:center"><strong><span style="font-family:Arial,Helvetica,sans-serif"><span style="line-height:2"><span style="font-size:24px"><span style="color:#555555">新闻中心 </span>/</span><span style="color:#434a54"><span style="font-size:24px">&nbsp;</span></span><span style="color:#579cf9"><span style="font-size:24px">News Center</span></span></span></span></strong></p> 
              </div> 
             </div> 
            </div>
           </div>
           <div id="smv_con_248_35" ctype="listnews" class="esmartMargin smartAbs " cpid="459757" cstyle="Style7" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_23_31" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 322px; width: 483px; left: 19px; top: 172px;z-index:12;">
            <div class="yibuFrameContent con_248_35  listnews_Style7  " style="overflow:visible;;"> 
             <div class="w-al xn-resize"> 
              <ul class="w-al-list clearfix" id="ulList_con_248_35"> 
               <li class="w-al-unit "> <a href="{base_url('index.php/news/detail?id=')}{$info[0]['id']}" target="_self"> 
                 <div class="w-al-pic">
                  <img src="{base_url($info[0]['image_url'])}" />
                 </div> 
                 <div class="w-al-text"> 
                  <h5 class="w-al-title">{$info[0]['article_title']}</h5> 
                  <p class="w-al-desc" style="padding-right:30px;">{$info[0]['digest']}</p> 
                 </div> </a> </li> 
              </ul> 
             </div> 
             <script type="text/javascript">
        var callback_con_248_35 = function () {
            var sv = $("#smv_con_248_35");
            var dur = parseInt("50");
            sv.find(".w-al-unit").hover(function () {
                var totalHeight = $(this).height();
                var h5Height = $(this).find(".w-al-title").height();
                var descHeight = $(this).find(".w-al-desc").height();
                var padTop = (totalHeight - h5Height - descHeight) / 2 + "px";

                $(this).find(".w-al-text").stop().animate({ height: "100%" }, dur);
                $(this).find(".w-al-text h5").stop().animate({ paddingTop: padTop }, dur);
            }, function () {
                $(this).find(".w-al-text").stop().animate({ height: "38px" }, dur);
                $(this).find(".w-al-text h5").stop().animate({ paddingTop: "0px" }, dur);
                });

            //裁减填充
            sv.find("img").cutFill();
        }
        callback_con_248_35();
    </script> 
            </div>
           </div>
           <div id="smv_con_239_33" ctype="listnews" class="esmartMargin smartAbs " cpid="459757" cstyle="Style4" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_23_31" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 300px; width: 638px; left: 561px; top: 166px;z-index:12;">
            <div class="yibuFrameContent con_239_33  listnews_Style4  " style="overflow:visible;;"> 
             <div class="w-list xn-resize"> 
              <ul class="w-list-ul w-list-imgno" id="ulList_con_239_33" data-title-autolines="1" data-desc-autolines="2"> 
               <li class="w-list-item f-clearfix"> 
                <div class="w-list-pic"> 
                 <a href="5916.html" target="_self" class="w-list-piclink"> <img class="w-listpic-in" src="{base_url($info[1]['image_url'])}" /> </a> 
                </div> 
                <div class="w-list-r"> 
                 <div class="w-list-r-in"> 
                  <h3 class="w-list-title"> <a href="{base_url('index.php/news/detail?id=')}{$info[1]['id']}" target="_self" class="w-list-titlelink">{$info[1]['article_title']}</a> </h3> 
                  <p class="w-list-desc ">{$info[1]['digest']}</p> 
                  <div class="w-list-bottom clearfix "> 
                   <span class="w-list-viewnum w-hide"><i class="w-list-viewicon mw-iconfont"></i><span class="AR" data-dt="nvc" data-v="-5916">0</span></span> 
                   <span class="w-list-date ">{$info[1]['time']}</span> 
                  </div> 
                 </div> 
                </div> </li> 
               <li class="w-list-item f-clearfix"> 
                <div class="w-list-pic"> 
                 <a target="_self" class="w-list-piclink"> <img class="w-listpic-in" src="{base_url($info[2]['image_url'])}" /> </a> 
                </div> 
                <div class="w-list-r"> 
                 <div class="w-list-r-in"> 
                  <h3 class="w-list-title"> <a href="{base_url('index.php/news/detail?id=')}{$info[2]['id']}" target="_self" class="w-list-titlelink">{$info[2]['article_title']}</a> </h3> 
                  <p class="w-list-desc ">{$info[2]['digest']}</p> 
                  <div class="w-list-bottom clearfix "> 
                   <span class="w-list-viewnum w-hide"><i class="w-list-viewicon mw-iconfont">넶</i><span class="AR" data-dt="nvc" data-v="-5915">0</span></span> 
                   <span class="w-list-date ">{$info[2]['time']}</span> 
                  </div> 
                 </div> 
                </div> </li> 
               <li class="w-list-item f-clearfix"> 
                <div class="w-list-pic"> 
                 <a href="-5913.html" target="_self" class="w-list-piclink"> <img class="w-listpic-in" src="{base_url($info[3]['image_url'])}" /> </a> 
                </div> 
                <div class="w-list-r"> 
                 <div class="w-list-r-in"> 
                  <h3 class="w-list-title"> <a href="{base_url('index.php/news/detail?id=')}{$info[3]['id']}" target="_self" class="w-list-titlelink">{$info[3]['article_title']}</a> </h3> 
                  <p class="w-list-desc ">{$info[3]['digest']}</p> 
                  <div class="w-list-bottom clearfix "> 
                   <span class="w-list-viewnum w-hide"><i class="w-list-viewicon mw-iconfont">넶</i><span class="AR" data-dt="nvc" data-v="-5913">0</span></span> 
                   <span class="w-list-date ">{$info[3]['time']}</span> 
                  </div> 
                 </div> 
                </div> </li> 
              </ul> 
             </div> 
             <script>
        var callback_con_239_33 = function() {
            var sv = $("#smv_con_239_33");
            var titlelines = parseInt(sv.find(".w-list-ul").attr("data-title-autolines"));
            var desclines = parseInt(sv.find(".w-list-ul").attr("data-desc-autolines"));
            var desc_line_height =sv.find(".w-list-desc").css("line-height");
            sv.find(".w-list-desc").css("max-height", parseInt(desc_line_height) * desclines);

            var titleItem = sv.find(".w-list-titlelink");
            var title_height = titleItem.css("height");
            sv.find(".w-list-item.w-list-nopic").css("min-height", parseInt(title_height));

            var title_line_height = titleItem.css("line-height");
            titleItem.css("max-height", parseInt(title_line_height) * titlelines);
            sv.find("img").cutFill();
        }
        callback_con_239_33();
    </script> 
            </div>
           </div>
           <div id="smv_con_244_13" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item5" areaid="Area0" iscontainer="False" pvid="con_23_31" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 29px; width: 56px; left: 1px; top: 568px;z-index:27;">
            <div class="yibuFrameContent con_244_13  text_Style1  " style="overflow:hidden;;">
             <div id="txt_con_244_13" style="height: 100%;"> 
              <div class="editableContent" id="txtc_con_244_13" style="height: 100%; word-wrap:break-word;"> 
               <p><span style="color:#656d78; font-family:Microsoft YaHei"><span style="font-size:12px"><a id="5" name="5"></a></span></span></p> 
              </div> 
             </div> 
            </div>
           </div>
          </div> 
          <div id="bannerWrap_con_23_31" class="fullcolumn-outer" style="position: absolute; top: 0; bottom: 0;"> 
          </div> 
          <script type="text/javascript">

    $(function () {
        var resize = function () {
            $("#smv_con_23_31 >.yibuFrameContent>.fullcolumn-inner").width($("#smv_con_23_31").parent().width());
            $('#bannerWrap_con_23_31').fullScreen(function (t) {
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
         </div>
        </div>
        <div id="smv_con_587_35" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item2" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 35px; width: 111px; left: 544px; top: 2827px;z-index:41;">
         <div class="yibuFrameContent con_587_35  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_587_35" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_587_35" style="height: 100%; word-wrap:break-word;"> 
            <p><span style="color:#555555"><strong><span style="letter-spacing:5px"><span style="font-size:22px"><span style="font-family:&quot;Source Han Sans&quot;,Geneva,sans-serif">工程展示</span></span></span></strong></span></p> 
           </div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_485_11" ctype="banner" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="y" daxis="Y" isdeletable="True" style="height: 1065px; width: 100%; left: 0px; top: 1675px;z-index:2;">
         <div class="yibuFrameContent con_485_11  banner_Style1  " style="overflow:visible;;">
          <div class="fullcolumn-inner smAreaC" id="smc_Area0" cid="con_485_11" style="width:1200px"> 
           <div id="smv_con_557_38" ctype="multicolumn" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="True" pvid="con_485_11" tareaid="" re-direction="y" daxis="Y" isdeletable="True" style="height: 389px; width: 100%; left: 0px; top: 572px;z-index:3;">
            <div class="yibuFrameContent con_557_38  multicolumn_Style1  " style="overflow:visible;;"> 
             <div class="w-columns " id="mc_con_557_38" data-spacing="0" data-pagewidth="1200" style="width: 1200px;"> 
              <ul class="w-columns-inner"> 
               <li class="w-columns-item" data-area="columnArea0" data-width="33"> 
                <div class="w-columns-interval"> 
                 <div class="w-columns-content" style="background-color: rgb(233, 237, 239); background-image: none; background-repeat: repeat; background-position: 50% 50%; background: -moz-linear-gradient(top, none, none);background: -ms-linear-gradient(none, none);background: -webkit-gradient(linear, left top, left bottom, from(none), to(none));background: -o-linear-gradient(top, none, none);background: linear-gradient(top, none, none);background-size:auto;"> 
                  <div class="w-columns-content-inner smAreaC" id="smc_columnArea0" cid="con_557_38" style="width:396px;"> 
                   <div id="smv_con_558_38" ctype="area" smanim="{ &quot;delay&quot;:0.0,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;&quot;,&quot;animationName&quot;:&quot;fadeIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="459757" cstyle="Style1" ccolor="Item1" areaid="columnArea0" iscontainer="True" pvid="con_557_38" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 430px; width: 304px; left: 47px; top: 32px;z-index:3;">
                    <div class="yibuFrameContent con_558_38  area_Style1  " style="overflow:visible;;">
                     <div class="w-container"> 
                      <div class="smAreaC" id="smc_Area0" cid="con_558_38"> 
                       <div id="smv_con_561_38" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item3" areaid="Area0" iscontainer="False" pvid="con_558_38" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 60px; width: 286px; left: 9px; top: 224px;z-index:4;">
                        <div class="yibuFrameContent con_561_38  text_Style1  " style="overflow:hidden;;">
                         <div id="txt_con_561_38" style="height: 100%;"> 
                          <div class="editableContent" id="txtc_con_561_38" style="height: 100%; word-wrap:break-word;"> 
                           <p style="text-align:center"><span style="font-size:14px"><span style="line-height:1.5">界桩埋设、边界点测定、边界线及相关地形要素调绘、边界协议书附图标绘</span></span></p> 
                          </div> 
                         </div> 
                        </div>
                       </div>
                       <div id="smv_con_559_38" ctype="image" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_558_38" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 61px; width: 60px; left: 121px; top: 36px;z-index:2;">
                        <div class="yibuFrameContent con_559_38  image_Style1  " style="overflow:visible;;"> 
                         <div class="w-image-box" data-filltype="0" id="div_con_559_38"> 
                          <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/7569988.png')}" alt="1" title="1" id="img_smv_con_559_38" style="width: 60px; height:61px;" /> </a> 
                         </div> 
                         <script type="text/javascript">
    $(function () {
        InitImageSmv("con_559_38", "60", "61", "0");
    });
</script> 
                        </div>
                       </div>
                       <div id="smv_con_560_38" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_558_38" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 46px; width: 284px; left: 5px; top: 138px;z-index:3;">
                        <div class="yibuFrameContent con_560_38  text_Style1  " style="overflow:hidden;;">
                         <div id="txt_con_560_38" style="height: 100%;"> 
                          <div class="editableContent" id="txtc_con_560_38" style="height: 100%; word-wrap:break-word;"> 
                           <p style="text-align:center"><span style="color:#555555"><span style="font-size:30px">行政区域界线测绘</span></span></p> 
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
                </div> </li> 
               <li class="w-columns-item" data-area="columnArea1" data-width="33"> 
                <div class="w-columns-interval"> 
                 <div class="w-columns-content" style="background-color: rgb(190, 197, 214); background-image: none; background-repeat: repeat; background-position: 50% 50%; background: -moz-linear-gradient(top, none, none);background: -ms-linear-gradient(none, none);background: -webkit-gradient(linear, left top, left bottom, from(none), to(none));background: -o-linear-gradient(top, none, none);background: linear-gradient(top, none, none);background-size:auto;"> 
                  <div class="w-columns-content-inner smAreaC" id="smc_columnArea1" cid="con_557_38" style="width:396px;"> 
                   <div id="smv_con_563_38" ctype="area" smanim="{ &quot;delay&quot;:0.25,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;&quot;,&quot;animationName&quot;:&quot;fadeIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="459757" cstyle="Style1" ccolor="Item1" areaid="columnArea1" iscontainer="True" pvid="con_557_38" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 430px; width: 304px; left: 51px; top: 26px;z-index:3;">
                    <div class="yibuFrameContent con_563_38  area_Style1  " style="overflow:visible;;">
                     <div class="w-container"> 
                      <div class="smAreaC" id="smc_Area0" cid="con_563_38"> 
                       <div id="smv_con_566_38" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_563_38" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 92px; width: 208px; left: 48px; top: 138px;z-index:3;">
                        <div class="yibuFrameContent con_566_38  text_Style1  " style="overflow:hidden;;">
                         <div id="txt_con_566_38" style="height: 100%;"> 
                          <div class="editableContent" id="txtc_con_566_38" style="height: 100%; word-wrap:break-word;"> 
                           <p style="text-align:center"><span style="color:#444444"><span style="line-height:1.2"><span style="font-size:30px">地理信息系统工程土地规划</span></span></span></p> 
                          </div> 
                         </div> 
                        </div>
                       </div>
                       <div id="smv_con_565_38" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item3" areaid="Area0" iscontainer="False" pvid="con_563_38" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 60px; width: 280px; left: 14px; top: 232px;z-index:4;">
                        <div class="yibuFrameContent con_565_38  text_Style1  " style="overflow:hidden;;">
                         <div id="txt_con_565_38" style="height: 100%;"> 
                          <div class="editableContent" id="txtc_con_565_38" style="height: 100%; word-wrap:break-word;"> 
                           <p style="text-align:center"><span style="font-size:14px">土地利用总体规划、土地开发整理复垦</span></p> 
                          </div> 
                         </div> 
                        </div>
                       </div>
                       <div id="smv_con_567_38" ctype="image" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_563_38" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 73px; width: 64px; left: 120px; top: 31px;z-index:2;">
                        <div class="yibuFrameContent con_567_38  image_Style1  " style="overflow:visible;;"> 
                         <div class="w-image-box" data-filltype="0" id="div_con_567_38"> 
                          <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/7569989.png')}" alt="2" title="2" id="img_smv_con_567_38" style="width: 64px; height:73px;" /> </a> 
                         </div> 
                         <script type="text/javascript">
    $(function () {
        InitImageSmv("con_567_38", "64", "73", "0");
    });
</script> 
                        </div>
                       </div> 
                      </div> 
                     </div>
                    </div>
                   </div> 
                  </div> 
                 </div> 
                </div> </li> 
               <li class="w-columns-item" data-area="columnArea2" data-width="34"> 
                <div class="w-columns-interval"> 
                 <div class="w-columns-content" style="background-color: rgb(35, 94, 155); background-image: none; background-repeat: repeat; background-position: 50% 50%; background: -moz-linear-gradient(top, none, none);background: -ms-linear-gradient(none, none);background: -webkit-gradient(linear, left top, left bottom, from(none), to(none));background: -o-linear-gradient(top, none, none);background: linear-gradient(top, none, none);background-size:auto;"> 
                  <div class="w-columns-content-inner smAreaC" id="smc_columnArea2" cid="con_557_38" style="width:408px;"> 
                   <div id="smv_con_568_38" ctype="area" smanim="{ &quot;delay&quot;:0.5,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;&quot;,&quot;animationName&quot;:&quot;fadeIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="459757" cstyle="Style1" ccolor="Item1" areaid="columnArea2" iscontainer="True" pvid="con_557_38" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 430px; width: 304px; left: 58px; top: 29px;z-index:3;">
                    <div class="yibuFrameContent con_568_38  area_Style1  " style="overflow:visible;;">
                     <div class="w-container"> 
                      <div class="smAreaC" id="smc_Area0" cid="con_568_38"> 
                       <div id="smv_con_570_38" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_568_38" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 46px; width: 160px; left: 72px; top: 138px;z-index:3;">
                        <div class="yibuFrameContent con_570_38  text_Style1  " style="overflow:hidden;;">
                         <div id="txt_con_570_38" style="height: 100%;"> 
                          <div class="editableContent" id="txtc_con_570_38" style="height: 100%; word-wrap:break-word;"> 
                           <p style="text-align:center"><span style="color:#ffffff"><span style="font-size:30px">其他业务</span></span></p> 
                          </div> 
                         </div> 
                        </div>
                       </div>
                       <div id="smv_con_571_38" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item3" areaid="Area0" iscontainer="False" pvid="con_568_38" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 87px; width: 292px; left: 6px; top: 229px;z-index:4;">
                        <div class="yibuFrameContent con_571_38  text_Style1  " style="overflow:hidden;;">
                         <div id="txt_con_571_38" style="height: 100%;"> 
                          <div class="editableContent" id="txtc_con_571_38" style="height: 100%; word-wrap:break-word;"> 
                           <p style="text-align:center"><span style="font-size:14px"><span style="color:#ffffff"><span style="line-height:1.5">矿业权核查、日照分析、规划指标复核、测绘航空摄影、工程土地规划、摄影测量与遥感、土地利用总体规划修编、林业调查规划设计、不动产权藉调查</span></span></span></p> 
                          </div> 
                         </div> 
                        </div>
                       </div>
                       <div id="smv_con_569_38" ctype="image" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_568_38" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 67px; width: 64px; left: 120px; top: 40px;z-index:2;">
                        <div class="yibuFrameContent con_569_38  image_Style1  " style="overflow:visible;;"> 
                         <div class="w-image-box" data-filltype="0" id="div_con_569_38"> 
                          <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/7569993.png')}" alt="更多图标" title="更多图标" id="img_smv_con_569_38" style="width: 64px; height:67px;" /> </a> 
                         </div> 
                         <script type="text/javascript">
    $(function () {
        InitImageSmv("con_569_38", "64", "67", "0");
    });
</script> 
                        </div>
                       </div> 
                      </div> 
                     </div>
                    </div>
                   </div> 
                  </div> 
                 </div> 
                </div> </li> 
              </ul> 
             </div> 
             <script type="text/javascript">
    $(function () {
        $("#mc_con_557_38>ul >li.w-columns-item").hover(function () {
            $("#smv_con_557_38").attr("selectArea", $(this).attr("data-area"));
        });
        $("#smv_con_557_38").attr("selectArea", "columnArea0");
    });
</script>
            </div>
           </div>
           <div id="smv_con_541_53" ctype="multicolumn" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="True" pvid="con_485_11" tareaid="" re-direction="y" daxis="Y" isdeletable="True" style="height: 389px; width: 100%; left: 0px; top: 162px;z-index:3;">
            <div class="yibuFrameContent con_541_53  multicolumn_Style1  " style="overflow:visible;;"> 
             <div class="w-columns " id="mc_con_541_53" data-spacing="0" data-pagewidth="1200" style="width: 1200px;"> 
              <ul class="w-columns-inner"> 
               <li class="w-columns-item" data-area="columnArea0" data-width="33"> 
                <div class="w-columns-interval"> 
                 <div class="w-columns-content" style="background-color: rgb(233, 237, 239); background-image: none; background-repeat: repeat; background-position: 50% 50%; background: -moz-linear-gradient(top, none, none);background: -ms-linear-gradient(none, none);background: -webkit-gradient(linear, left top, left bottom, from(none), to(none));background: -o-linear-gradient(top, none, none);background: linear-gradient(top, none, none);background-size:auto;"> 
                  <div class="w-columns-content-inner smAreaC" id="smc_columnArea0" cid="con_541_53" style="width:396px;"> 
                   <div id="smv_con_542_53" ctype="area" smanim="{ &quot;delay&quot;:0.0,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;&quot;,&quot;animationName&quot;:&quot;fadeIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="459757" cstyle="Style1" ccolor="Item1" areaid="columnArea0" iscontainer="True" pvid="con_541_53" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 430px; width: 304px; left: 47px; top: 32px;z-index:3;">
                    <div class="yibuFrameContent con_542_53  area_Style1  " style="overflow:visible;;">
                     <div class="w-container"> 
                      <div class="smAreaC" id="smc_Area0" cid="con_542_53"> 
                       <div id="smv_con_544_53" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_542_53" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 46px; width: 160px; left: 72px; top: 138px;z-index:3;">
                        <div class="yibuFrameContent con_544_53  text_Style1  " style="overflow:hidden;;">
                         <div id="txt_con_544_53" style="height: 100%;"> 
                          <div class="editableContent" id="txtc_con_544_53" style="height: 100%; word-wrap:break-word;"> 
                           <p style="text-align:center"><span style="color:#4a5752"><span style="font-size:30px">工程测量</span></span></p> 
                          </div> 
                         </div> 
                        </div>
                       </div>
                       <div id="smv_con_545_53" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item3" areaid="Area0" iscontainer="False" pvid="con_542_53" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 90px; width: 294px; left: 9px; top: 224px;z-index:4;">
                        <div class="yibuFrameContent con_545_53  text_Style1  " style="overflow:hidden;;">
                         <div id="txt_con_545_53" style="height: 100%;"> 
                          <div class="editableContent" id="txtc_con_545_53" style="height: 100%; word-wrap:break-word;"> 
                           <p style="text-align:center"><span style="font-size:14px"><span style="line-height:1.5">控制测量、地形测量、城乡规划定线测量、城乡用地测量、建筑工程测量、日照测量、道路桥梁测量、线路工程测量、隧道测量、竣工测量</span></span></p> 
                          </div> 
                         </div> 
                        </div>
                       </div>
                       <div id="smv_con_543_53" ctype="image" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_542_53" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 61px; width: 60px; left: 121px; top: 36px;z-index:2;">
                        <div class="yibuFrameContent con_543_53  image_Style1  " style="overflow:visible;;"> 
                         <div class="w-image-box" data-filltype="0" id="div_con_543_53"> 
                          <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/7569990.png')}" alt="3" title="3" id="img_smv_con_543_53" style="width: 60px; height:61px;" /> </a> 
                         </div> 
                         <script type="text/javascript">
    $(function () {
        InitImageSmv("con_543_53", "60", "61", "0");
    });
</script> 
                        </div>
                       </div> 
                      </div> 
                     </div>
                    </div>
                   </div> 
                  </div> 
                 </div> 
                </div> </li> 
               <li class="w-columns-item" data-area="columnArea1" data-width="33"> 
                <div class="w-columns-interval"> 
                 <div class="w-columns-content" style="background-color: rgb(190, 197, 214); background-image: none; background-repeat: repeat; background-position: 50% 50%; background: -moz-linear-gradient(top, none, none);background: -ms-linear-gradient(none, none);background: -webkit-gradient(linear, left top, left bottom, from(none), to(none));background: -o-linear-gradient(top, none, none);background: linear-gradient(top, none, none);background-size:auto;"> 
                  <div class="w-columns-content-inner smAreaC" id="smc_columnArea1" cid="con_541_53" style="width:396px;"> 
                   <div id="smv_con_547_53" ctype="area" smanim="{ &quot;delay&quot;:0.25,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;&quot;,&quot;animationName&quot;:&quot;fadeIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="459757" cstyle="Style1" ccolor="Item1" areaid="columnArea1" iscontainer="True" pvid="con_541_53" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 430px; width: 304px; left: 51px; top: 26px;z-index:3;">
                    <div class="yibuFrameContent con_547_53  area_Style1  " style="overflow:visible;;">
                     <div class="w-container"> 
                      <div class="smAreaC" id="smc_Area0" cid="con_547_53"> 
                       <div id="smv_con_549_53" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item3" areaid="Area0" iscontainer="False" pvid="con_547_53" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 60px; width: 280px; left: 12px; top: 230px;z-index:4;">
                        <div class="yibuFrameContent con_549_53  text_Style1  " style="overflow:hidden;;">
                         <div id="txt_con_549_53" style="height: 100%;"> 
                          <div class="editableContent" id="txtc_con_549_53" style="height: 100%; word-wrap:break-word;"> 
                           <p style="text-align:center"><span style="font-size:14px">控制测量、界址测量、地籍调查、面积量算</span></p> 
                          </div> 
                         </div> 
                        </div>
                       </div>
                       <div id="smv_con_551_53" ctype="image" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_547_53" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 73px; width: 64px; left: 120px; top: 31px;z-index:2;">
                        <div class="yibuFrameContent con_551_53  image_Style1  " style="overflow:visible;;"> 
                         <div class="w-image-box" data-filltype="0" id="div_con_551_53"> 
                          <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/7569992.png')}" alt="地籍图标" title="地籍图标" id="img_smv_con_551_53" style="width: 64px; height:73px;" /> </a> 
                         </div> 
                         <script type="text/javascript">
    $(function () {
        InitImageSmv("con_551_53", "64", "73", "0");
    });
</script> 
                        </div>
                       </div>
                       <div id="smv_con_550_53" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_547_53" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 46px; width: 208px; left: 48px; top: 138px;z-index:3;">
                        <div class="yibuFrameContent con_550_53  text_Style1  " style="overflow:hidden;;">
                         <div id="txt_con_550_53" style="height: 100%;"> 
                          <div class="editableContent" id="txtc_con_550_53" style="height: 100%; word-wrap:break-word;"> 
                           <p style="text-align:center"><span style="color:#4a5752"><span style="font-size:30px">地籍测绘</span></span></p> 
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
                </div> </li> 
               <li class="w-columns-item" data-area="columnArea2" data-width="34"> 
                <div class="w-columns-interval"> 
                 <div class="w-columns-content" style="background-color: rgb(35, 94, 155); background-image: none; background-repeat: repeat; background-position: 50% 50%; background: -moz-linear-gradient(top, none, none);background: -ms-linear-gradient(none, none);background: -webkit-gradient(linear, left top, left bottom, from(none), to(none));background: -o-linear-gradient(top, none, none);background: linear-gradient(top, none, none);background-size:auto;"> 
                  <div class="w-columns-content-inner smAreaC" id="smc_columnArea2" cid="con_541_53" style="width:408px;"> 
                   <div id="smv_con_552_53" ctype="area" smanim="{ &quot;delay&quot;:0.5,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;&quot;,&quot;animationName&quot;:&quot;fadeIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="459757" cstyle="Style1" ccolor="Item1" areaid="columnArea2" iscontainer="True" pvid="con_541_53" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 430px; width: 304px; left: 58px; top: 29px;z-index:3;">
                    <div class="yibuFrameContent con_552_53  area_Style1  " style="overflow:visible;;">
                     <div class="w-container"> 
                      <div class="smAreaC" id="smc_Area0" cid="con_552_53"> 
                       <div id="smv_con_553_53" ctype="image" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_552_53" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 67px; width: 64px; left: 120px; top: 40px;z-index:2;">
                        <div class="yibuFrameContent con_553_53  image_Style1  " style="overflow:visible;;"> 
                         <div class="w-image-box" data-filltype="0" id="div_con_553_53"> 
                          <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/7569991.png')}" alt="6084528" title="6084528" id="img_smv_con_553_53" style="width: 64px; height:67px;" /> </a> 
                         </div> 
                         <script type="text/javascript">
    $(function () { 
        InitImageSmv("con_553_53", "64", "67", "0");
    });
</script> 
                        </div>
                       </div>
                       <div id="smv_con_555_53" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item3" areaid="Area0" iscontainer="False" pvid="con_552_53" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 60px; width: 292px; left: 7px; top: 223px;z-index:4;">
                        <div class="yibuFrameContent con_555_53  text_Style1  " style="overflow:hidden;;">
                         <div id="txt_con_555_53" style="height: 100%;"> 
                          <div class="editableContent" id="txtc_con_555_53" style="height: 100%; word-wrap:break-word;"> 
                           <p style="text-align:center"><span style="font-size:14px"><span style="line-height:1.5"><span style="color:#ffffff">平面控制测量、房产面积预测算、房产面积测算、房产调查与测量、房产变更调查与测量、房产图测绘</span></span></span></p> 
                          </div> 
                         </div> 
                        </div>
                       </div>
                       <div id="smv_con_554_53" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_552_53" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 46px; width: 160px; left: 72px; top: 138px;z-index:3;">
                        <div class="yibuFrameContent con_554_53  text_Style1  " style="overflow:hidden;;">
                         <div id="txt_con_554_53" style="height: 100%;"> 
                          <div class="editableContent" id="txtc_con_554_53" style="height: 100%; word-wrap:break-word;"> 
                           <p style="text-align:center"><span style="color:#ffffff"><span style="font-size:30px">房产测绘</span></span></p> 
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
                </div> </li> 
              </ul> 
             </div> 
             <script type="text/javascript">
    $(function () {
        $("#mc_con_541_53>ul >li.w-columns-item").hover(function () {
            $("#smv_con_541_53").attr("selectArea", $(this).attr("data-area"));
        });
        $("#smv_con_541_53").attr("selectArea", "columnArea0");
    });
</script>
            </div>
           </div>
           <div id="smv_con_522_54" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_485_11" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 35px; width: 109px; left: 547px; top: 39px;z-index:6;">
            <div class="yibuFrameContent con_522_54  text_Style1  " style="overflow:hidden;;">
             <div id="txt_con_522_54" style="height: 100%;"> 
              <div class="editableContent" id="txtc_con_522_54" style="height: 100%; word-wrap:break-word;"> 
               <p><span style="color:#555555"><strong><span style="letter-spacing:5px"><span style="font-size:22px"><span style="font-family:&quot;Source Han Sans&quot;,Geneva,sans-serif">业务介绍</span></span></span></strong></span></p> 
              </div> 
             </div> 
            </div>
           </div>
           <div id="smv_con_523_54" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_485_11" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 29px; width: 93px; left: 553px; top: 77px;z-index:7;">
            <div class="yibuFrameContent con_523_54  text_Style1  " style="overflow:hidden;;">
             <div id="txt_con_523_54" style="height: 100%;"> 
              <div class="editableContent" id="txtc_con_523_54" style="height: 100%; word-wrap:break-word;"> 
               <p><span style="color:#235e9b"><span style="font-size:22px">Business</span></span></p> 
              </div> 
             </div> 
            </div>
           </div>
           <div id="smv_con_524_9" ctype="line" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item3" areaid="Area0" iscontainer="False" pvid="con_485_11" tareaid="" re-direction="x" daxis="All" isdeletable="True" style="height: 20px; width: 43px; left: 577px; top: 105px;z-index:8;">
            <div class="yibuFrameContent con_524_9  line_Style1  " style="overflow:visible;;">
             <!-- w-line --> 
             <div style="position:relative; height:100%"> 
              <div class="w-line" style="position:absolute;top:50%;" linetype="horizontal"></div> 
             </div> 
            </div>
           </div>
          </div> 
          <div id="bannerWrap_con_485_11" class="fullcolumn-outer" style="position: absolute; top: 0; bottom: 0;"> 
          </div> 
          <script type="text/javascript">

    $(function () {
        var resize = function () {
            $("#smv_con_485_11 >.yibuFrameContent>.fullcolumn-inner").width($("#smv_con_485_11").parent().width());
            $('#bannerWrap_con_485_11').fullScreen(function (t) {
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
         </div>
        </div>
        <div id="smv_con_309_25" ctype="banner" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="y" daxis="Y" isdeletable="True" style="height: 656px; width: 100%; left: 0px; top: 1019px;z-index:2;">
         <div class="yibuFrameContent con_309_25  banner_Style1  " style="overflow:visible;;">
          <div class="fullcolumn-inner smAreaC" id="smc_Area0" cid="con_309_25" style="width:1200px"> 
           <div id="smv_con_313_25" ctype="slide" class="esmartMargin smartAbs " cpid="459757" cstyle="Style2" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_309_25" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 339px; width: 342px; left: 111px; top: 181px;z-index:9;">
            <div class="yibuFrameContent con_313_25  slide_Style2  " style="overflow:visible;;">
             <!--w-slide--> 
             <div class="w-slider" id="slider_smv_con_313_25"> 
              <div class="w-slider-wrap" data-u="slides"> 
               <div> 
                <a href="" target="_self" class="w-imgauto"> <img data-u="image" src="{resource_url('img/tdkcImg/2000000787.jpg')}" alt="组合控件-通栏容器01" title="组合控件-通栏容器01" class="CutFill" /> </a> 
               </div> 
               <div> 
                <a href="" target="_self" class="w-imgauto"> <img data-u="image" src="{resource_url('img/tdkcImg/2000000788.jpg')}" alt="组合控件-通栏容器01" title="组合控件-通栏容器01" class="CutFill" /> </a> 
               </div> 
              </div> 
             </div> 
             <!--/w-slide--> 
             <script type="text/javascript">
    con_313_25_slider3_init = function () {
        var jssor_1_options = {
            $AutoPlay: "on" == "on",//自动播放
            $PlayOrientation: "1",//2为向上滑，1为向左滑
            $Loop: parseInt("1"),//循环
            $SlideDuration: "1000",//延时
            $Idle: parseInt("5000"),//切换间隔
            $SlideEasing: $Jease$.$OutQuint,
            
             $SlideshowOptions: {
                $Class: $JssorSlideshowRunner$,
                $Transitions: GetSlideAnimation("9", "1000"),
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
        var slide = new $JssorSlider$("slider_smv_con_313_25", jssor_1_options);
        $('#smv_con_313_25').data('jssor_slide', slide);
    }
    $(function () {
        con_313_25_slider3_init();
        var imgWidth = $('#slider_smv_con_313_25').width();
        var imgHeight = $('#slider_smv_con_313_25').height();
        $('#slider_smv_con_313_25 img').cutFill(imgWidth, imgHeight);
    });

</script>
            </div>
           </div>
           <div id="smv_con_432_54" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item1" areaid="Area0" iscontainer="False" pvid="con_309_25" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 31px; width: 117px; left: 545px; top: 34px;z-index:31;">
            <div class="yibuFrameContent con_432_54  text_Style1  " style="overflow:hidden;;">
             <div id="txt_con_432_54" style="height: 100%;"> 
              <div class="editableContent" id="txtc_con_432_54" style="height: 100%; word-wrap:break-word;"> 
               <p><strong><span style="letter-spacing:5px"><span style="color:#555555"><span style="font-size:22px">公司简介</span></span></span></strong></p> 
              </div> 
             </div> 
            </div>
           </div>
           <div id="smv_con_346_52" ctype="image" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_309_25" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 20px; width: 20px; left: 500px; top: 362px;z-index:30;">
            <div class="yibuFrameContent con_346_52  image_Style1  " style="overflow:visible;;"> 
             <div class="w-image-box" data-filltype="0" id="div_con_346_52"> 
              <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/6085562.png')}" alt="箭头" title="箭头" id="img_smv_con_346_52" style="width: 20px; height:20px;" /> </a> 
             </div> 
             <script type="text/javascript">
    $(function () {
        InitImageSmv("con_346_52", "20", "20", "0");
    });
</script> 
            </div>
           </div>
           <div id="smv_con_347_16" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item5" areaid="Area0" iscontainer="False" pvid="con_309_25" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 73px; width: 483px; left: 534px; top: 355px;z-index:10;">
            <div class="yibuFrameContent con_347_16  text_Style1  " style="overflow:hidden;;">
             <div id="txt_con_347_16" style="height: 100%;"> 
              <div class="editableContent" id="txtc_con_347_16" style="height: 100%; word-wrap:break-word;"> 
               <p><strong><span style="color:#666666"><span style="line-height:2"><span style="font-size:14px"><span style="font-family:&quot;Microsoft YaHei&quot;">业务范围广</span></span></span></span></strong></p> 
               <p><span style="color:#777777"><span style="line-height:1.5"><span style="font-size:14px">业务范围覆盖国土、规划、交通、水利、农业、城建等诸多行业，工作业绩遍及杭州、嘉兴、绍兴、温州、衢州、宁波等全省各地。</span></span></span></p> 
               <p><span style="color:#777777"><span style="line-height:1.5"><span style="font-size:14px"><span style="font-family:&quot;Microsoft YaHei&quot;"><strong>&nbsp;</strong></span></span></span></span></p> 
              </div> 
             </div> 
            </div>
           </div>
           <div id="smv_con_348_1" ctype="image" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_309_25" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 20px; width: 20px; left: 500px; top: 455px;z-index:30;">
            <div class="yibuFrameContent con_348_1  image_Style1  " style="overflow:visible;;"> 
             <div class="w-image-box" data-filltype="0" id="div_con_348_1"> 
              <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/6085562.png')}" alt="箭头" title="箭头" id="img_smv_con_348_1" style="width: 20px; height:20px;" /> </a> 
             </div> 
             <script type="text/javascript">
    $(function () {
        InitImageSmv("con_348_1", "20", "20", "0");
    });
</script> 
            </div>
           </div>
           <div id="smv_con_349_1" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item5" areaid="Area0" iscontainer="False" pvid="con_309_25" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 80px; width: 476px; left: 536px; top: 449px;z-index:10;">
            <div class="yibuFrameContent con_349_1  text_Style1  " style="overflow:hidden;;">
             <div id="txt_con_349_1" style="height: 100%;"> 
              <div class="editableContent" id="txtc_con_349_1" style="height: 100%; word-wrap:break-word;"> 
               <p><strong><span style="line-height:2"><span style="color:#666666; font-family:Microsoft YaHei"><span style="font-size:14px">技术应用领先</span></span></span></strong></p> 
               <p><span style="font-size:14px"><span style="color:#777777"><span style="line-height:1.5">除了常规测绘外，我们已经开始配置应用无人机等尖端测绘技术，加强测绘业务的效率、效果。</span></span></span></p> 
              </div> 
             </div> 
            </div>
           </div>
           <div id="smv_con_433_7" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_309_25" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 29px; width: 180px; left: 507px; top: 74px;z-index:32;">
            <div class="yibuFrameContent con_433_7  text_Style1  " style="overflow:hidden;;">
             <div id="txt_con_433_7" style="height: 100%;"> 
              <div class="editableContent" id="txtc_con_433_7" style="height: 100%; word-wrap:break-word;"> 
               <p><span style="color:#235e9b"><span style="font-size:22px"><span style="font-family:&quot;Source Han Sans&quot;,Geneva,sans-serif">Company Profile</span></span></span></p> 
              </div> 
             </div> 
            </div>
           </div>
           <div id="smv_con_435_55" ctype="line" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item3" areaid="Area0" iscontainer="False" pvid="con_309_25" tareaid="" re-direction="x" daxis="All" isdeletable="True" style="height: 20px; width: 52px; left: 572px; top: 100px;z-index:34;">
            <div class="yibuFrameContent con_435_55  line_Style1  " style="overflow:visible;;">
             <!-- w-line --> 
             <div style="position:relative; height:100%"> 
              <div class="w-line" style="position:absolute;top:50%;" linetype="horizontal"></div> 
             </div> 
            </div>
           </div>
           <div id="smv_con_437_34" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item3" areaid="Area0" iscontainer="False" pvid="con_309_25" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 149px; width: 476px; left: 532px; top: 177px;z-index:36;">
            <div class="yibuFrameContent con_437_34  text_Style1  " style="overflow:hidden;;">
             <div id="txt_con_437_34" style="height: 100%;"> 
              <div class="editableContent" id="txtc_con_437_34" style="height: 100%; word-wrap:break-word;"> 
               <p><span style="color:#555555"><span style="line-height:1.75"><span style="font-size:16px"><span style="font-family:&quot;Source Han Sans&quot;,Geneva,sans-serif">慈溪市土地勘测规划设计院有限公司是慈溪市国土资源局所属的原国有企业（慈溪市土地勘测规划设计院）改制后的股份制民营企业。公司创建于1993年。是以测量为主业，集土地、房屋测量，土地规划设计，地理信息服务，矿业权核查，房地产登记代理于一体，经营多元、</span></span></span><span style="font-size:16px">结构合理的现代企业。</span></span></p> 
              </div> 
             </div> 
            </div>
           </div>
           <div id="smv_con_438_33" ctype="button" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item3" areaid="Area0" iscontainer="False" pvid="con_309_25" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 19px; width: 75px; left: 926px; top: 298px;z-index:37;">
            
           </div>
          </div> 
          <div id="bannerWrap_con_309_25" class="fullcolumn-outer" style="position: absolute; top: 0; bottom: 0;"> 
          </div> 
          <script type="text/javascript">

    $(function () {
        var resize = function () {
            $("#smv_con_309_25 >.yibuFrameContent>.fullcolumn-inner").width($("#smv_con_309_25").parent().width());
            $('#bannerWrap_con_309_25').fullScreen(function (t) {
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
         </div>
        </div>
        <div id="smv_con_242_20" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item5" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 30px; width: 56px; left: 0px; top: 3713px;z-index:27;">
         <div class="yibuFrameContent con_242_20  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_242_20" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_242_20" style="height: 100%; word-wrap:break-word;"> 
            <p><span style="color:#656d78; font-family:Microsoft YaHei"><span style="font-size:12px"><a id="2" name="2"></a></span></span></p> 
           </div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_245_26" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item5" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 29px; width: 56px; left: 0px; top: 4156px;z-index:27;">
         <div class="yibuFrameContent con_245_26  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_245_26" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_245_26" style="height: 100%; word-wrap:break-word;"> 
            <p><span style="color:#656d78; font-family:Microsoft YaHei"><span style="font-size:12px"><a id="3" name="3"></a></span></span></p> 
           </div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_421_38" ctype="qqservice" class="esmartMargin smartAbs smartFixed   " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="x" daxis="All" isdeletable="True" style="height: 183px; width: 195px; right: 0px; top: 0px;bottom:0px;margin:auto;z-index:34;">
         <div class="yibuFrameContent con_421_38  qqservice_Style1  " style="overflow:hidden;;"> 
          <!--w-cs--> 
          <div class="con_421_38_c w-cs" id="qqservice_con_421_38"> 
           <ul class="w-cs-btn"> 
            <li class="w-cs-list w-cs-phoneBtn w-hide"> <a href="javascript:void(0);" class="w-cs-icon"><img src="{resource_url('img/btns/1.jpg')}" class="mw-iconfont icon-phone"></img></a> 
             <ul class="w-cs-menu w-cs-phone"> 
              <li><h3>客服电话</h3></li> 
              <li>88888888</li> 
             </ul> </li> 
            <li class="w-cs-list w-cs-qqBtn w-hide"> <a href="javascript:void(0);" class="w-cs-icon"><img  class="mw-iconfont"></img></a> 
             <ul class="w-cs-menu w-cs-qq"> 
              <li><a href="//wpa.qq.com/msgrd?v=3&amp;uin=123456&amp;site=qq&amp;menu=yes" target="_blank">QQ客服</a></li> 
             </ul> </li> 
            <li class="w-cs-list w-cs-clockBtn "> <a href="javascript:void(0);" class="w-cs-icon"><img  src="{resource_url('img/btns/1.jpg')}" class="mw-iconfont icon-clock"></img></a> 
             <ul class="w-cs-menu w-cs-clock"> 
              <li><h3>电话热线</h3></li> 
              <li>{$siteSetting['site_phone']}</li> 
             </ul> </li> 
            <li class="w-cs-list w-cs-qrcodeBtn "> <a href="javascript:void(0);" class="w-cs-icon"><img src="{resource_url('img/btns/2.jpg')}" class="mw-iconfont icon-qrcode" ></img></a> 
             <ul class="w-cs-menu w-cs-qrcode"> 
              <li><h3>微信二维码</h3></li> 
              <li class="w-cs-qrcode-img"><img src="https://www.cxmap.net/static/img/tdkcImg/ewm.jpg" alt="" /></li> 
             </ul> </li> 

            <li class="w-cs-list w-cs-upBtn "> <a href="javascript:void(0);" onclick="gotoTop();return false;" class="w-cs-icon"><img src="{resource_url('img/btns/3.jpg')}" class="mw-iconfont icon-up"></img></a> </li> 
           </ul> 
          </div> 
          <!--/w-cs--> 
          <script>

    $(function () {
        var sv = $("#qqservice_con_421_38");

        var numbers =[];
        $.each(sv.find(".w-cs-menu"), function() { numbers.push(this.scrollWidth); });
        var maxInNumbers = Math.max.apply(Math, numbers);

        sv.find(".w-cs-menu").css("width", maxInNumbers + "px");
        //  显示
        sv.find(".w-cs-list").hover(function () {
            $(this).find("ul.w-cs-menu").stop().animate({ right: 61 }, 200);
        }, function () {
            $(this).find("ul.w-cs-menu").stop().animate({ right: "0" }, 200);
        });
            
                $("#smv_con_421_38").addClass('exist').appendTo($('body'));
            
    });
    function gotoTop(acceleration, stime) {
        acceleration = acceleration || 0.1;
        stime = stime || 10;
        var x1 = 0;
        var y1 = 0;
        var x2 = 0;
        var y2 = 0;
        if (document.documentElement) {
            x1 = document.documentElement.scrollLeft || 0;
            y1 = document.documentElement.scrollTop || 0;
        }
        if (document.body) {
            x2 = document.body.scrollLeft || 0;
            y2 = document.body.scrollTop || 0;
        }
        var x3 = window.scrollX || 0;
        var y3 = window.scrollY || 0;

        // 滚动条到页面顶部的水平距离
        var x = Math.max(x1, Math.max(x2, x3));
        // 滚动条到页面顶部的垂直距离
        var y = Math.max(y1, Math.max(y2, y3));

        // 滚动距离 = 目前距离 / 速度, 因为距离原来越小, 速度是大于 1 的数, 所以滚动距离会越来越小
        var speeding = 1 + acceleration;
        window.scrollTo(Math.floor(x / speeding), Math.floor(y / speeding));

        // 如果距离不为零, 继续调用函数
        if (x > 0 || y > 0) {
            var run = "gotoTop(" + acceleration + ", " + stime + ")";
            window.setTimeout(run, stime);
        }
    }
</script>
         </div>
        </div>
        <div id="smv_con_588_10" ctype="text" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item2" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 29px; width: 94px; left: 553px; top: 2868px;z-index:42;">
         <div class="yibuFrameContent con_588_10  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_588_10" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_588_10" style="height: 100%; word-wrap:break-word;"> 
            <p><span style="letter-spacing:1px"><span style="color:#235e9b"><span style="font-size:22px">Projects</span></span></span></p> 
           </div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_628_48" ctype="slideset" class="esmartMargin smartAbs " cpid="459757" cstyle="Style4" ccolor="Item0" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 460px; width: 1000px; left: 95px; top: 3002px;z-index:3;">
         <div class="yibuFrameContent con_628_48  slideset_Style4  " style="overflow:visible;;"> 
          <!--w-slide--> 
          <div class="w-slide" id="slider_smv_con_628_48"> 
           <div class="w-slide-inner" data-u="slides"> 
            <div class="content-box" data-area="Area0"> 
             <div id="smc_Area0" cid="con_628_48" class="smAreaC slideset_AreaC"> 
              <div id="smv_con_636_48" ctype="image" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_628_48" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 388px; width: 483px; left: 457px; top: 47px;z-index:10;">
               <div class="yibuFrameContent con_636_48  image_Style1  " style="overflow:visible;;"> 
                <div class="w-image-box" data-filltype="2" id="div_con_636_48"> 
                 <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/201012221426469036.jpg')}" alt="pic01_06" title="pic01_06" id="img_smv_con_636_48" style="width: 483px; height:388px;" /> </a> 
                </div> 
                <script type="text/javascript">
    $(function () {
        InitImageSmv("con_636_48", "483", "388", "2");
    });
</script> 
               </div>
              </div>
              <div id="smv_con_629_48" ctype="area" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item1" areaid="Area0" iscontainer="True" pvid="con_628_48" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 391px; width: 401px; left: 46px; top: 34px;z-index:5;">
               <div class="yibuFrameContent con_629_48  area_Style1  " style="overflow:visible;;">
                <div class="w-container"> 
                 <div class="smAreaC" id="smc_Area0" cid="con_629_48"> 
                  <div id="smv_con_632_48" ctype="text" smanim="{ &quot;delay&quot;:0.75,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Left&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_629_48" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 89px; width: 339px; left: 73px; top: 155px;z-index:4;">
                   <div class="yibuFrameContent con_632_48  text_Style1  " style="overflow:hidden;;">
                    <div id="txt_con_632_48" style="height: 100%;"> 
                     <div class="editableContent" id="txtc_con_632_48" style="height: 100%; word-wrap:break-word;"> 
                      <p><span style="line-height:1.5"><span style="color:#143a42"><span style="font-size:30px"><span style="font-family:&quot;Microsoft YaHei&quot;">慈溪市开心基础设施</span></span></span></span></p> 
                  <p><span style="line-height:1.5"><span style="color:#75a19f"><span style="font-size:12px"><span style="font-family:&quot;Microsoft YaHei&quot;">规划设计图</span></span></span></span></p> 
                     
                     </div> 
                    </div> 
                   </div>
                  </div>
                  <div id="smv_con_630_48" ctype="image" smanim="{ &quot;delay&quot;:0.25,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Left&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_629_48" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 342px; width: 226px; left: 48px; top: 28px;z-index:2;">
                   <div class="yibuFrameContent con_630_48  image_Style1  " style="overflow:visible;;"> 
                    
                    <script type="text/javascript">
    $(function () {
        InitImageSmv("con_630_48", "226", "342", "2");
    });
</script> 
                   </div>
                  </div> 
                 </div> 
                </div>
               </div>
              </div> 
             </div> 
             <div class="content-box-inner" style="background-image:none;background-gradient-bottom:none;background-gradient-top:none;background-color:#e3e7ea;opacity:1"></div> 
            </div> 
            <div class="content-box" data-area="Area1"> 
             <div id="smc_Area1" cid="con_628_48" class="smAreaC slideset_AreaC"> 
              <div id="smv_con_639_48" ctype="image" smanim="{ &quot;delay&quot;:0.75,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;&quot;,&quot;animationName&quot;:&quot;fadeIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area1" iscontainer="False" pvid="con_628_48" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 200px; width: 193px; left: 1008px; top: -9px;z-index:3;">
               <div class="yibuFrameContent con_639_48  image_Style1  " style="overflow:visible;;"> 
                <div class="w-image-box" data-filltype="2" id="div_con_639_48"> 
                 <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/2000000632.png')}" alt="tree_14" title="tree_14" id="img_smv_con_639_48" style="width: 193px; height:200px;" /> </a> 
                </div> 
                <script type="text/javascript">
    $(function () {
        InitImageSmv("con_639_48", "193", "200", "2");
    });
</script> 
               </div>
              </div>
              <div id="smv_con_645_48" ctype="text" smanim="{ &quot;delay&quot;:1.0,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Right&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area1" iscontainer="False" pvid="con_628_48" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 71px; width: 303px; left: 612px; top: 274px;z-index:10;">
               <div class="yibuFrameContent con_645_48  text_Style1  " style="overflow:hidden;;">
                <div id="txt_con_645_48" style="height: 100%;"> 
                 <div class="editableContent" id="txtc_con_645_48" style="height: 100%; word-wrap:break-word;"> 
                  <p><span style="line-height:1.5"><span style="color:#143a42"><span style="font-size:22px"><span style="font-family:&quot;Microsoft YaHei&quot;">慈溪市水云浦-半掘浦段围涂地</span></span></span></span></p> 
                  <p><span style="line-height:1.5"><span style="color:#75a19f"><span style="font-size:12px"><span style="font-family:&quot;Microsoft YaHei&quot;">开发造地项目规划图</span></span></span></span></p> 
                 </div> 
                </div> 
               </div>
              </div>
              <div id="smv_con_640_48" ctype="area" smanim="{ &quot;delay&quot;:0.5,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Right&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="459757" cstyle="Style1" ccolor="Item1" areaid="Area1" iscontainer="True" pvid="con_628_48" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 206px; width: 224px; left: 578px; top: 23px;z-index:7;">
               <div class="yibuFrameContent con_640_48  area_Style1  " style="overflow:visible;;">
                <div class="w-container"> 
                 <div class="smAreaC" id="smc_Area0" cid="con_640_48"> 
                 </div> 
                </div>
               </div>
              </div>
              <div id="smv_con_638_48" ctype="image" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="Area1" iscontainer="False" pvid="con_628_48" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 405px; width: 300px; left: 50px; top: 20px;z-index:2;">
               <div class="yibuFrameContent con_638_48  image_Style1  " style="overflow:visible;;"> 
                <div class="w-image-box" data-filltype="2" id="div_con_638_48"> 
                 <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/201012221432874070.jpg')}" alt="pic01_15" title="pic01_15" id="img_smv_con_638_48" style="width: 483px; height:405px;" /> </a> 
                </div> 
                <script type="text/javascript">
    $(function () {
        InitImageSmv("con_638_48", "567", "405", "2");
    });
</script> 
               </div>
              </div> 
             </div> 
             <div class="content-box-inner" style="background-image:none;background-gradient-bottom:none;background-gradient-top:none;background-color:#add0cc;opacity:1"></div> 
            </div> 
           </div> 
           <!-- Bullet Navigator --> 
           <div data-u="navigator" class="w-slide-btn-box " data-autocenter="1"> 
            <!-- bullet navigator item prototype --> 
            <div class="w-slide-btn" data-u="prototype"></div> 
           </div> 
           <!-- 1Arrow Navigator --> 
           <span data-u="arrowleft" id="left_con_628_48" class="w-slide-arrowl slideArrow " data-autocenter="2"> <i class="w-itemicon mw-iconfont"><</i> </span> 
           <span data-u="arrowright" id="right_con_628_48" class="w-slide-arrowr slideArrow " data-autocenter="2"> <i class="w-itemicon mw-iconfont">></i> </span> 
          </div> 
          <!--/w-slide--> 
          <script type="text/javascript">
    con_628_48_page = 1;
    con_628_48_firstTime = true;
    con_628_48_sliderset3_init = function () {
        var jssor_1_options_con_628_48 = {
            $AutoPlay: "False"=="True"?false:"on" == "on",//自动播放
            $PlayOrientation: 1,//2为向上滑，1为向左滑
            $Loop: 1,//循环
            $Idle: parseInt("4000"),//切换间隔
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
        var slide = new $JssorSlider$("slider_smv_con_628_48", jssor_1_options_con_628_48);
        $('#smv_con_628_48').data('jssor_slide', slide);

        //幻灯栏目自动或手动切换时触发的事件
        slide.$On($JssorSlider$.$EVT_PARK, function (slideIndex, fromIndex) {
            var $slideWrapper = $("#slider_smv_con_628_48 .w-slide-inner:last");
            var $fromSlide = $slideWrapper.find(".content-box:eq(" + fromIndex + ")");
            var $curSlide = $slideWrapper.find(".content-box:eq(" + slideIndex + ")");
            var $nextSlide = $slideWrapper.find(".content-box:eq(" + (slideIndex + 1) + ")");
            $("#smv_con_628_48").attr("selectArea", $curSlide.attr("data-area"));
            $fromSlide.find(".animated").smanimate("stop");
            $curSlide.find(".animated").smanimate("stop");
            $nextSlide.find(".animated").smanimate("stop");
            $("#switch_con_628_48 .page").html(slideIndex + 1);
            $curSlide.find(".animated").smanimate("replay");
            return false;
        });
        //切换栏点击事件
        $("#switch_con_628_48 .left").unbind("click").click(function () {
            if(con_628_48_page==1){
                con_628_48_page =2;
            } else {
                con_628_48_page = con_628_48_page - 1;
            }
            $("#switch_con_628_48 .page").html(con_628_48_page);
            slide.$Prev();
            return false;
        });
        $("#switch_con_628_48 .right").unbind("click").click(function () {
            if(con_628_48_page==2){
                con_628_48_page = 1;
            } else {
                con_628_48_page = con_628_48_page + 1;
            }
           $("#switch_con_628_48 .page").html(con_628_48_page);
         slide.$Next();
         return false;
        });
    };
    $(function () {
        //获取幻灯显示动画类型
        con_628_48_sliderset3_init();
        var areaId = $("#smv_con_628_48").attr("tareaid");
        if(areaId==""){
            var mainWidth = $("#smv_Main").width();
            $("#smv_con_628_48 .slideset_AreaC").css({ "width":mainWidth+"px","position":"relative","margin":"0 auto" });
        }else{
            var controlWidth = $("#smv_con_628_48").width();
            $("#smv_con_628_48 .slideset_AreaC").css({ "width":controlWidth+"px","position":"relative","margin":"0 auto" });
        }
        $("#smv_con_628_48").attr("selectArea", "Area0");

        var arrowHeight = $('#slider_smv_con_628_48 .w-slide-arrowl').eq(-1).outerHeight();
        var arrowTop = (18 - arrowHeight) / 2;
        $('#slider_smv_con_628_48 .w-slide-arrowl').eq(-1).css('top', arrowTop);
        $('#slider_smv_con_628_48 .w-slide-arrowr').eq(-1).css('top', arrowTop);
    });

</script> 
         </div>
        </div>
        <div id="smv_con_605_29" ctype="line" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item3" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="x" daxis="All" isdeletable="True" style="height: 20px; width: 45px; left: 576px; top: 2905px;z-index:43;">
         <div class="yibuFrameContent con_605_29  line_Style1  " style="overflow:visible;;">
          <!-- w-line --> 
          <div style="position:relative; height:100%"> 
           <div class="w-line" style="position:absolute;top:50%;" linetype="horizontal"></div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_413_32" ctype="banner" class="esmartMargin smartAbs " cpid="459757" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="y" daxis="Y" isdeletable="True" style="height: 1018px; width: 100%; left: 0px; top: 0px;z-index:2;">
         <div class="yibuFrameContent con_413_32  banner_Style1  " style="overflow:visible;">
          <div class="fullcolumn-inner smAreaC" id="smc_Area0" cid="con_413_32" style="width:1200px"> 
          </div> 
          <div id="bannerWrap_con_413_32" class="fullcolumn-outer" style="position: absolute; top: 0; bottom: 0;right:0;left:0; background-size:100% 100%;">
          </div> 
          <script type="text/javascript">

    $(function () {
        var resize = function () {
            $("#smv_con_413_32 >.yibuFrameContent>.fullcolumn-inner").width($("#smv_con_413_32").parent().width());
            $('#bannerWrap_con_413_32').fullScreen(function (t) {
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
         </div>
        </div>
       </div>
      </div>
      <input type="hidden" name="__RequestVerificationToken" id="token__RequestVerificationToken" value="CvCgwoB6FkDPMdakRHKxQo8jD5HOCrO-mYBWbmLwtWQnQ4Dd-gYT4dWgbsrzhpbpCiqEJK9hwvXBaA9BsxZlB3BEHT_GPRA08fVd4QdMxoTWddQ_oq4P_HlXejdk9BQc0" /> 
     </div> 
    </div> 
   </div> 
  {include file="common/footer.tpl"}
	
