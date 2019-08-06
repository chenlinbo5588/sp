{include file="common/header.tpl"}
  <link href="{resource_url('css/tdkcCss/view.css')}" rel="stylesheet" type="text/css" /> 
  <link href="{resource_url('css/tdkcCss/461385_Pc_zh-CN.css')}" rel="stylesheet" />
  <script type="text/javascript" id="jssor-all" src="{resource_url('js/tdkcJs/jssor.slider-22.2.16-all.min.js')}"></script>
  <script type="text/javascript" id="jqueryzoom" src="{resource_url('js/tdkcJs/jquery.jqueryzoom.js')}"></script>
  <input type="hidden" id="pageinfo" value="461385" data-type="1" data-device="Pc" data-entityid="461385" /> 
  <input id="txtDeviceSwitchEnabled" value="show" type="hidden" /> 
  <input type="hidden" id="secUrl" data-host="c1797065108ffy.scd.wezhan.cn" data-pathname="/xwzxtk" data-search="" data-hash="" /> 
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
      <div class="smvWrapper" style="min-width:1000px;  position: relative; background-color: transparent; background-image: none; background-repeat: no-repeat; background:-moz-linear-gradient(top, none, none);background:-webkit-gradient(linear, left top, left bottom, from(none), to(none));background:-o-linear-gradient(top, none, none);background:-ms-linear-gradient(top, none, none);background:linear-gradient(top, none, none);;background-position:0 0;background-size:;" bgscroll="none">
       <div class="smvContainer" id="smv_Main" cpid="461385" style="min-height:400px;width:1000px;height:1539px;  position: relative; ">
        <div id="smv_con_71_27" ctype="banner" class="esmartMargin smartAbs " cpid="461385" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="y" daxis="Y" isdeletable="True" style="height: 327px; width: 100%; left: 0px; top: 0px;z-index:1;">
         <div class="yibuFrameContent con_71_27  banner_Style1  " style="overflow:visible;;">
          <div class="fullcolumn-inner smAreaC" id="smc_Area0" cid="con_71_27" style="width:1000px"> 
           <div id="smv_con_2_19" ctype="text" smanim="{ &quot;delay&quot;:0.0,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Down&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461385" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_71_27" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 152px; width: 269px; left: 365px; top: 63px; z-index: 3; opacity: 1;" sm-finished="true" smexecuted="1">
            <div class="yibuFrameContent con_2_19  text_Style1  " style="overflow:hidden;;">
             <div id="txt_con_2_19" style="height: 100%;"> 
              <div class="editableContent" id="txtc_con_2_19" style="height: 100%; word-wrap:break-word;"> 
               <p style="text-align:center"><span style="color:#ffffff"><strong><span style="font-size:36px"><span style="line-height:2">新闻资讯</span></span></strong></span></p> 
               <p style="text-align:center"><span style="color:#ffffff"><span style="font-size:24px">News information</span></span></p> 
              </div> 
             </div> 
            </div>
           </div>
           <div id="smv_con_96_39" ctype="image" class="esmartMargin smartAbs " cpid="461385" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_71_27" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 56px; width: 56px; left: 471px; top: 189px;z-index:4;">
            <div class="yibuFrameContent con_96_39  image_Style1  " style="overflow:visible;;"> 
             <div class="w-image-box" data-filltype="0" id="div_con_96_39" style="height: 56px; width: 56px;"> 
              <a target="_self"> <img src="{resource_url('img/tdkcImg/6111258.png')}" alt="-10278" title="-10278" id="img_smv_con_96_39" style="width: 56px; height:56px;" /> </a> 
             </div> 
             <script type="text/javascript">
    $(function () {
        InitImageSmv("con_96_39", "56", "56", "0");
    });
</script> 
            </div>
           </div>
          </div> 
          <div id="bannerWrap_con_71_27" class="fullcolumn-outer" style="position: absolute; top: 0px; bottom: 0px; left: -456px; width: 1912px;"> 
          </div> 
          <script type="text/javascript">

    $(function () {
        var resize = function () {
            $("#smv_con_71_27 >.yibuFrameContent>.fullcolumn-inner").width($("#smv_con_71_27").parent().width());
            $('#bannerWrap_con_71_27').fullScreen(function (t) {
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
        <div id="smv_con_70_9" ctype="banner" class="esmartMargin smartAbs " cpid="461385" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="y" daxis="Y" isdeletable="True" style="height: 839px; width: 100%; left: 0px; top: 700px;z-index:1;">
         <div class="yibuFrameContent con_70_9  banner_Style1  " style="overflow:visible;;">
          <div class="fullcolumn-inner smAreaC" id="smc_Area0" cid="con_70_9" style="width:1000px"> 
           <div id="smv_con_103_56" ctype="area" class="esmartMargin smartAbs " cpid="461385" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="True" pvid="con_70_9" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 751px; width: 1000px; left: 0px; top: 65px;z-index:0;">
            <div class="yibuFrameContent con_103_56  area_Style1  " style="overflow:visible;;">
             <div class="w-container"> 
              <div class="smAreaC" id="smc_Area0" cid="con_103_56"> 
               <div id="smv_con_108_24" ctype="text" class="esmartMargin smartAbs " cpid="461385" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_103_56" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 25px; width: 240px; left: 35px; top: -18px;z-index:10032;">
                <div class="yibuFrameContent con_108_24  text_Style1  " style="overflow:hidden;;">
                 <div id="txt_con_108_24" style="height: 100%;"> 
                  <div class="editableContent" id="txtc_con_108_24" style="height: 100%; word-wrap:break-word;"> 
                   <p><span style="color:#4e5f70; font-family:Microsoft YaHei; font-size:medium"><strong>快讯</strong></span></p> 
                  </div> 
                 </div> 
                </div>
               </div>
               <div id="smv_con_114_44" ctype="listnews" class="esmartMargin smartAbs " cpid="461385" cstyle="Style4" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_103_56" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 649px; width: 938px; left: 32px; top: 26px;z-index:10030;">
                <div class="yibuFrameContent con_114_44  listnews_Style4  " style="overflow:visible;;">
                 <div class="m-nodata f-clearfix">
                  <div class="m-datain">
                   <img src="{resource_url('img/tdkcImg/no-data.png')}" class="m-dataimg" />
                   <div class="m-datatext">
                    您还没有选择文章数据，请先选择数据
                   </div>
                  </div>
                 </div>
                </div>
               </div> 
              </div> 
             </div>
            </div>
           </div>
           <div id="smv_con_106_4" ctype="line" class="esmartMargin smartAbs " cpid="461385" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_70_9" tareaid="" re-direction="x" daxis="All" isdeletable="True" style="height: 20px; width: 960px; left: 20px; top: 66px;z-index:10033;">
            <div class="yibuFrameContent con_106_4  line_Style1  " style="overflow:visible;;">
             <!-- w-line --> 
             <div style="position:relative; height:100%"> 
              <div class="w-line" style="position:absolute;top:50%;" linetype="horizontal"></div> 
             </div> 
            </div>
           </div>
           <div id="smv_con_109_58" ctype="line" class="esmartMargin smartAbs " cpid="461385" cstyle="Style2" ccolor="Item3" areaid="Area0" iscontainer="False" pvid="con_70_9" tareaid="" re-direction="y" daxis="All" isdeletable="True" style="height: 20px; width: 20px; left: 10px; top: 46px;z-index:10034;">
            <div class="yibuFrameContent con_109_58  line_Style2  " style="overflow:visible;;">
             <!-- w-line --> 
             <div style="position:relative; width:100%"> 
              <div class="w-line" style="position:absolute;left:50%;" linetype="vertical"></div> 
             </div> 
            </div>
           </div>
          </div> 
          <div id="bannerWrap_con_70_9" class="fullcolumn-outer" style="position: absolute; top: 0px; bottom: 0px; left: -456px; width: 1912px;"> 
          </div> 
          <script type="text/javascript">

    $(function () {
        var resize = function () {
            $("#smv_con_70_9 >.yibuFrameContent>.fullcolumn-inner").width($("#smv_con_70_9").parent().width());
            $('#bannerWrap_con_70_9').fullScreen(function (t) {
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
        <div id="smv_con_99_51" ctype="listnews" class="esmartMargin smartAbs " cpid="461385" cstyle="Style6" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 321px; width: 1000px; left: 20px; top: 390px;z-index:10030;">
         <div class="yibuFrameContent con_99_51  listnews_Style6  " style="overflow:visible;;"> 
          <div class="w-list xn-resize"> 
           <ul class="w-list-ul f-clearfix" id="ulList_con_99_51" data-title-autolines="1" data-desc-autolines="1"> 
            <li class="w-list-item"> <a href="https://c1797065108ffy.scd.wezhan.cn/newsinfo/-1798.html" target="_self" class="w-list-link"> 
              <div class="w-list-pic"> 
               <img class="w-listpic-in" src="{resource_url('img/tdkcImg/-10275.png')}" autofill="ok" init="ok" style="width: auto; height: 220px; margin-left: -9.12698px;" /> 
              </div> <p class="w-list-title" style="height: 28px;">联合办公形势大好</p> <p class="w-list-info " style="height: 24px;">可以说，科创大厦社区自开业起就备受瞩目，目前，今日头条武汉分部、男人袜等均入驻在此，社区入驻率为110%。在此次中共武汉市常委等领导考察的过程中，与科创大厦社区入驻企业进行了密切交流，其中包括武XX网络有限公司、北京XX科技有限公司、湖北XX通用航空有限公司、武汉中观XX科技有限公司等科技创新型企业。</p> <p class="w-list-date ">08-14</p> </a> </li> 
            <li class="w-list-item"> <a href="https://c1797065108ffy.scd.wezhan.cn/newsinfo/-1797.html" target="_self" class="w-list-link"> 
              <div class="w-list-pic"> 
               <img class="w-listpic-in" src="{resource_url('img/tdkcImg/-10274.png')}" autofill="ok" init="ok" style="width: auto; height: 220px; margin-left: -9.12px;" /> 
              </div> <p class="w-list-title" style="height: 28px;">每一件事与你息息相关</p> <p class="w-list-info " style="height: 24px;">可以说，科创大厦社区自开业起就备受瞩目，目前，今日头条武汉分部、男人袜等均入驻在此，社区入驻率为110%。在此次中共武汉市常委等领导考察的过程中，与科创大厦社区入驻企业进行了密切交流，其中包括武XX网络有限公司、北京XX科技有限公司、湖北XX通用航空有限公司、武汉中观XX科技有限公司等科技创新型企业。</p> <p class="w-list-date ">08-14</p> </a> </li> 
            <li class="w-list-item"> <a href="https://c1797065108ffy.scd.wezhan.cn/newsinfo/-1799.html" target="_self" class="w-list-link"> 
              <div class="w-list-pic"> 
               <img class="w-listpic-in" src="{resource_url('img/tdkcImg/-10276.png')}" autofill="ok" init="ok" style="width: auto; height: 220px; margin-left: -9.12px;" /> 
              </div> <p class="w-list-title" style="height: 28px;">中国品牌新媒体联盟成立</p> <p class="w-list-info " style="height: 24px;">可以说，科创大厦社区自开业起就备受瞩目，目前，今日头条武汉分部、男人袜等均入驻在此，社区入驻率为110%。在此次中共武汉市常委等领导考察的过程中，与科创大厦社区入驻企业进行了密切交流，其中包括武XX网络有限公司、北京XX科技有限公司、湖北XX通用航空有限公司、武汉中观XX科技有限公司等科技创新型企业。</p> <p class="w-list-date ">08-14</p> </a> </li> 
           </ul> 
          </div> 
          <script>
        var callback_con_99_51 = function () {
            var sv = $("#smv_con_99_51");
            var titlelines = parseInt(sv.find(".w-list-ul").attr("data-title-autolines"));
            var desclines = parseInt(sv.find(".w-list-ul").attr("data-desc-autolines"));

            var titleItem = sv.find(".w-list-title");
            var title_line_height = titleItem.css("line-height");
            titleItem.css("height", parseInt(title_line_height) * titlelines);

            var desc_line_height = sv.find(".w-list-info").css("line-height");
            sv.find(".w-list-info").css("height", parseInt(desc_line_height) * desclines);

            sv.find("img").cutFill();
        }
        callback_con_99_51();
    </script> 
         </div>
        </div>
        <div id="smv_con_107_54" ctype="line" class="esmartMargin smartAbs " cpid="461385" cstyle="Style2" ccolor="Item3" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="y" daxis="All" isdeletable="True" style="height: 20px; width: 20px; left: 6px; top: 355px;z-index:10034;">
         <div class="yibuFrameContent con_107_54  line_Style2  " style="overflow:visible;;">
          <!-- w-line --> 
          <div style="position:relative; width:100%"> 
           <div class="w-line" style="position:absolute;left:50%;" linetype="vertical"></div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_105_14" ctype="text" class="esmartMargin smartAbs " cpid="461385" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 23px; width: 360px; left: 33px; top: 356px;z-index:10032;">
         <div class="yibuFrameContent con_105_14  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_105_14" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_105_14" style="height: 100%; word-wrap:break-word;"> 
            <p><span style="color:#4e5f70"><strong><span style="font-size:16px"><span style="font-family:Microsoft YaHei">热门推荐</span></span></strong></span></p> 
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
	
