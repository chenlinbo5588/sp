{include file="common/header.tpl"}
  <script type="text/javascript" id="SuperSlide" src="{resource_url('js/tdkcJs/jquery.SuperSlide.2.1.1.js')}"></script>
  <script type="text/javascript" id="jqPaginator" src="{resource_url('js/tdkcJs/jqPaginator.min.js')}"></script>
  <link href="{resource_url('css/tdkcCss/461159_Pc_zh-CN.css')}" rel="stylesheet">
  <link href="{resource_url('css/tdkcCss/view.css')}" rel="stylesheet" type="text/css" /> 

  <input type="hidden" id="pageinfo" value="461159" data-type="1" data-device="Pc" data-entityid="461159" /> 
  <input id="txtDeviceSwitchEnabled" value="show" type="hidden" /> 
  <input type="hidden" id="secUrl" data-host="c1797065108ffy.scd.wezhan.cn" data-pathname="/ryzztk" data-search="" data-hash="" /> 
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
      <div class="smvWrapper" style="min-width:1120px;  position: relative; background-color: transparent; background-image: none; background-repeat: no-repeat; background:-moz-linear-gradient(top, none, none);background:-webkit-gradient(linear, left top, left bottom, from(none), to(none));background:-o-linear-gradient(top, none, none);background:-ms-linear-gradient(top, none, none);background:linear-gradient(top, none, none);;background-position:0 0;background-size:auto;" bgscroll="none">
       <div class="smvContainer" id="smv_Main" cpid="461159" style="min-height:400px;width:1120px;height:2400px;  position: relative; ">
        <div id="smv_con_1_22" ctype="banner" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item1" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="y" daxis="Y" isdeletable="True" style="height: 200px; width: 100%; left: 0px; top: 1338px;z-index:2;">
         <div class="yibuFrameContent con_1_22  banner_Style1  " style="overflow:visible;;">
          <div class="fullcolumn-inner smAreaC" id="smc_Area0" cid="con_1_22" style="width:1120px"> 
           <div id="smv_con_3_27" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_1_22" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 55px; width: 456px; left: 3px; top: 74px;z-index:3;">
            <div class="yibuFrameContent con_3_27  text_Style1  " style="overflow:hidden;;">
             <div id="txt_con_3_27" style="height: 100%;"> 
              <div class="editableContent" id="txtc_con_3_27" style="height: 100%; word-wrap:break-word;"> 
               <p><span style="font-family:Verdana,Geneva,sans-serif"><span style="color:#777777"><span style="font-size:48px">Accept challenges</span></span></span></p> 
              </div> 
             </div> 
            </div>
           </div>
           <div id="smv_con_4_23" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_1_22" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 55px; width: 448px; left: 27px; top: 74px;z-index:3;">
            <div class="yibuFrameContent con_4_23  text_Style1  " style="overflow:hidden;;">
             <div id="txt_con_4_23" style="height: 100%;"> 
              <div class="editableContent" id="txtc_con_4_23" style="height: 100%; word-wrap:break-word;"> 
               <p><span style="font-family:Verdana,Geneva,sans-serif"><span style="color:#ffffff"><span style="font-size:48px">Accept challenges</span></span></span></p> 
              </div> 
             </div> 
            </div>
           </div>
           <div id="smv_con_5_29" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item1" areaid="Area0" iscontainer="False" pvid="con_1_22" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 26px; width: 231px; left: 499px; top: 92px;z-index:2;">
            <div class="yibuFrameContent con_5_29  text_Style1  " style="overflow:hidden;;">
             <div id="txt_con_5_29" style="height: 100%;"> 
              <div class="editableContent" id="txtc_con_5_29" style="height: 100%; word-wrap:break-word;"> 
               <p><span style="color:#ffffff"><span style="font-size:20px">质量求生存，挑战谋发展</span></span></p> 
              </div> 
             </div> 
            </div>
           </div>
          </div> 
          <div id="bannerWrap_con_1_22" class="fullcolumn-outer" style="position: absolute; top: 0px; bottom: 0px; left: -396px; width: 1912px;"> 
          </div> 
          <script type="text/javascript">

    $(function () {
        var resize = function () {
            $("#smv_con_1_22 >.yibuFrameContent>.fullcolumn-inner").width($("#smv_con_1_22").parent().width());
            $('#bannerWrap_con_1_22').fullScreen(function (t) {
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
        <div id="smv_con_47_46" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 38px; width: 123px; left: 479px; top: 46px;z-index:10;">
         <div class="yibuFrameContent con_47_46  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_47_46" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_47_46" style="height: 100%; word-wrap:break-word;"> 
            <p><span style="color:#444444"><span style="font-size:30px"><span style="font-family:Tahoma,Geneva,sans-serif">资质证书</span></span></span></p> 
           </div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_6_38" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item2" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 37px; width: 122px; left: 499px; top: 1622px;z-index:4;">
         <div class="yibuFrameContent con_6_38  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_6_38" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_6_38" style="height: 100%; word-wrap:break-word;"> 
            <p><span style="font-size:30px"><span style="color:#444444"><span style="font-family:Microsoft YaHei">团队介绍</span></span></span></p> 
           </div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_7_45" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item3" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 22px; width: 39px; left: 540px; top: 1664px;z-index:5;">
         <div class="yibuFrameContent con_7_45  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_7_45" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_7_45" style="height: 100%; word-wrap:break-word;"> 
            <p><span style="color:#888888"><span style="font-family:&quot;Microsoft YaHei&quot;; font-size:14px">Team</span></span></p> 
           </div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_10_38" ctype="area" smanim="{ &quot;delay&quot;:0.15,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Up&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461159" cstyle="Style1" ccolor="Item3" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 178px; width: 543px; left: 0px; top: 1743px; z-index: 8; opacity: 1;" sm-finished="true" smexecuted="1">
         <div class="yibuFrameContent con_10_38  area_Style1  " style="overflow:visible;;">
          <div class="w-container"> 
           <div class="smAreaC" id="smc_Area0" cid="con_10_38"> 
            <div id="smv_con_12_26" ctype="image" class="esmartMargin smartAbs " cpid="461159" cstyle="Style2" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_10_38" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 126px; width: 126px; left: 31px; top: 25px;z-index:1;">
             <div class="yibuFrameContent con_12_26  image_Style2  " style="overflow:visible;;"> 
              <div class="w-image-box" data-filltype="1" id="div_con_12_26" style="height: 126px;"> 
               <a target="_self"> <img src="{resource_url('img/tdkcImg/-12835.jpg')}" alt="woman-1254454_1920" title="woman-1254454_1920" id="img_smv_con_12_26" style="width: 126px; height: auto; margin-top: -31.5px; margin-left: 0px;" /> </a> 
              </div> 
              <script type="text/javascript">
$(function () {
    InitImageSmv("con_12_26", "126", "126", "1");

});
</script> 
             </div>
            </div>
            <div id="smv_con_13_8" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_10_38" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 25px; width: 79px; left: 189px; top: 37px;z-index:3;">
             <div class="yibuFrameContent con_13_8  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_13_8" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_13_8" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#000000; font-family:Microsoft YaHei; font-size:18px">泰勒 &nbsp;&nbsp;</span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_14_10" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item5" areaid="Area0" iscontainer="False" pvid="con_10_38" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 19px; width: 83px; left: 242px; top: 41px;z-index:4;">
             <div class="yibuFrameContent con_14_10  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_14_10" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_14_10" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#656d78; font-family:Microsoft YaHei; font-size:12px">研发工程师</span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_15_48" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item5" areaid="Area0" iscontainer="False" pvid="con_10_38" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 76px; width: 278px; left: 188px; top: 81px;z-index:5;">
             <div class="yibuFrameContent con_15_48  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_15_48" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_15_48" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#999999"><span style="line-height:1.5">2年以上PHP开发经验，熟悉THINKPHP开源框架；熟悉JS开发，熟悉Json和xml数据交换格式；拥有良好的代码习惯，要求代码结构清晰，命名规范。</span></span></p> 
               </div> 
              </div> 
             </div>
            </div> 
           </div> 
          </div>
         </div>
        </div>
        <div id="smv_con_16_1" ctype="area" smanim="{ &quot;delay&quot;:0.35,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Up&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461159" cstyle="Style1" ccolor="Item3" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 178px; width: 543px; left: 577px; top: 1743px; z-index: 8; opacity: 1;" sm-finished="true" smexecuted="1">
         <div class="yibuFrameContent con_16_1  area_Style1  " style="overflow:visible;;">
          <div class="w-container"> 
           <div class="smAreaC" id="smc_Area0" cid="con_16_1"> 
            <div id="smv_con_17_1" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_16_1" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 26px; width: 79px; left: 187px; top: 37px;z-index:3;">
             <div class="yibuFrameContent con_17_1  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_17_1" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_17_1" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#000000; font-family:Microsoft YaHei; font-size:18px">艾米 &nbsp;&nbsp;</span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_18_1" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item5" areaid="Area0" iscontainer="False" pvid="con_16_1" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 19px; width: 83px; left: 241px; top: 42px;z-index:4;">
             <div class="yibuFrameContent con_18_1  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_18_1" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_18_1" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#656d78; font-family:Microsoft YaHei; font-size:12px">资深设计师</span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_19_1" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item5" areaid="Area0" iscontainer="False" pvid="con_16_1" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 76px; width: 278px; left: 190px; top: 81px;z-index:5;">
             <div class="yibuFrameContent con_19_1  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_19_1" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_19_1" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="line-height:1.5"><span style="color:#999999">站酷主办 出版资深设计师的Photoshop创意课丛书人员之一。</span></span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_20_1" ctype="image" class="esmartMargin smartAbs " cpid="461159" cstyle="Style2" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_16_1" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 126px; width: 126px; left: 29px; top: 25px;z-index:1;">
             <div class="yibuFrameContent con_20_1  image_Style2  " style="overflow:visible;;"> 
              <div class="w-image-box" data-filltype="1" id="div_con_20_1" style="height: 126px;"> 
               <a target="_self"> <img src="{resource_url('img/tdkcImg/-12842.png')}" alt="-4511-1" title="-4511-1" id="img_smv_con_20_1" style="width: 126px; height: auto; margin-top: -8.59091px; margin-left: 0px;" /> </a> 
              </div> 
              <script type="text/javascript">
$(function () {
    InitImageSmv("con_20_1", "126", "126", "1");

});
</script> 
             </div>
            </div> 
           </div> 
          </div>
         </div>
        </div>
        <div id="smv_con_26_15" ctype="area" smanim="{ &quot;delay&quot;:0.55,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Up&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461159" cstyle="Style1" ccolor="Item3" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 178px; width: 543px; left: 0px; top: 1953px; z-index: 8; opacity: 1;" sm-finished="true" smexecuted="1">
         <div class="yibuFrameContent con_26_15  area_Style1  " style="overflow:visible;;">
          <div class="w-container"> 
           <div class="smAreaC" id="smc_Area0" cid="con_26_15"> 
            <div id="smv_con_27_15" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_26_15" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 26px; width: 79px; left: 187px; top: 38px;z-index:3;">
             <div class="yibuFrameContent con_27_15  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_27_15" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_27_15" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#000000; font-family:Microsoft YaHei; font-size:18px">艾丽斯 &nbsp;&nbsp;</span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_28_15" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item5" areaid="Area0" iscontainer="False" pvid="con_26_15" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 19px; width: 83px; left: 262px; top: 42px;z-index:4;">
             <div class="yibuFrameContent con_28_15  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_28_15" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_28_15" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#656d78; font-family:Microsoft YaHei; font-size:12px">资深设计师</span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_29_15" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item5" areaid="Area0" iscontainer="False" pvid="con_26_15" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 76px; width: 278px; left: 187px; top: 79px;z-index:5;">
             <div class="yibuFrameContent con_29_15  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_29_15" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_29_15" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#999999"><span style="line-height:1.5">拥有良好的代码习惯，要求代码结构清晰，命名规范，逻辑性较强，代码冗余率低。</span></span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_30_15" ctype="image" class="esmartMargin smartAbs " cpid="461159" cstyle="Style2" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_26_15" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 126px; width: 126px; left: 31px; top: 24px;z-index:1;">
             <div class="yibuFrameContent con_30_15  image_Style2  " style="overflow:visible;;"> 
              <div class="w-image-box" data-filltype="1" id="div_con_30_15" style="height: 126px;"> 
               <a target="_self"> <img src="{resource_url('img/tdkcImg/-12836.jpg')}" alt="unnamed-2" title="unnamed-2" id="img_smv_con_30_15" style="width: auto; height: 126px; margin-left: -31.3478px; margin-top: 0px;" /> </a> 
              </div> 
              <script type="text/javascript">
$(function () {
    InitImageSmv("con_30_15", "126", "126", "1");

});
</script> 
             </div>
            </div> 
           </div> 
          </div>
         </div>
        </div>
        <div id="smv_con_31_22" ctype="area" smanim="{ &quot;delay&quot;:0.75,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Up&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461159" cstyle="Style1" ccolor="Item3" areaid="Main" iscontainer="True" pvid="" tareaid="Main" re-direction="all" daxis="All" isdeletable="True" style="height: 178px; width: 543px; left: 577px; top: 1953px; z-index: 8; opacity: 1;" sm-finished="true" smexecuted="1">
         <div class="yibuFrameContent con_31_22  area_Style1  " style="overflow:visible;;">
          <div class="w-container"> 
           <div class="smAreaC" id="smc_Area0" cid="con_31_22"> 
            <div id="smv_con_32_22" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_31_22" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 26px; width: 79px; left: 187px; top: 38px;z-index:3;">
             <div class="yibuFrameContent con_32_22  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_32_22" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_32_22" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#000000; font-family:Microsoft YaHei; font-size:18px">汤米 &nbsp;&nbsp;</span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_33_22" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item5" areaid="Area0" iscontainer="False" pvid="con_31_22" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 19px; width: 83px; left: 246px; top: 42px;z-index:4;">
             <div class="yibuFrameContent con_33_22  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_33_22" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_33_22" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#656d78; font-family:Microsoft YaHei; font-size:12px">研发工程师</span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_34_22" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item5" areaid="Area0" iscontainer="False" pvid="con_31_22" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 76px; width: 278px; left: 187px; top: 80px;z-index:5;">
             <div class="yibuFrameContent con_34_22  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_34_22" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_34_22" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#999999"><span style="line-height:1.5">拥有良好的代码习惯，要求代码结构清晰，命名规范，逻辑性较强，代码冗余率低。</span></span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_35_22" ctype="image" class="esmartMargin smartAbs " cpid="461159" cstyle="Style2" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_31_22" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 126px; width: 126px; left: 31px; top: 25px;z-index:1;">
             <div class="yibuFrameContent con_35_22  image_Style2  " style="overflow:visible;;"> 
              <div class="w-image-box" data-filltype="1" id="div_con_35_22" style="height: 126px;"> 
               <a target="_self"> <img src="{resource_url('img/tdkcImg/-12840.png')}" alt="-4512-1" title="-4512-1" id="img_smv_con_35_22" style="width: 126px; height: auto; margin-top: -8.59091px; margin-left: 0px;" /> </a> 
              </div> 
              <script type="text/javascript">
$(function () {
    InitImageSmv("con_35_22", "126", "126", "1");

});
</script> 
             </div>
            </div> 
           </div> 
          </div>
         </div>
        </div>
        <div id="smv_con_36_46" ctype="area" smanim="{ &quot;delay&quot;:0.95,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Up&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461159" cstyle="Style1" ccolor="Item3" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 178px; width: 543px; left: 0px; top: 2165px; z-index: 8; opacity: 1;" sm-finished="true" smexecuted="1">
         <div class="yibuFrameContent con_36_46  area_Style1  " style="overflow:visible;;">
          <div class="w-container"> 
           <div class="smAreaC" id="smc_Area0" cid="con_36_46"> 
            <div id="smv_con_37_46" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_36_46" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 26px; width: 79px; left: 186px; top: 38px;z-index:3;">
             <div class="yibuFrameContent con_37_46  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_37_46" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_37_46" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#000000; font-family:Microsoft YaHei; font-size:18px">托尼 &nbsp;&nbsp;</span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_38_46" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item5" areaid="Area0" iscontainer="False" pvid="con_36_46" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 19px; width: 83px; left: 241px; top: 42px;z-index:4;">
             <div class="yibuFrameContent con_38_46  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_38_46" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_38_46" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#656d78; font-family:Microsoft YaHei; font-size:12px">研发工程师</span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_39_46" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item5" areaid="Area0" iscontainer="False" pvid="con_36_46" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 76px; width: 278px; left: 187px; top: 80px;z-index:5;">
             <div class="yibuFrameContent con_39_46  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_39_46" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_39_46" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#999999"><span style="line-height:1.5">2年以上PHP开发经验，熟悉THINKPHP开源框架；熟悉JS开发，熟悉Json和xml数据交换格式。</span></span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_40_46" ctype="image" class="esmartMargin smartAbs " cpid="461159" cstyle="Style2" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_36_46" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 126px; width: 126px; left: 29px; top: 25px;z-index:1;">
             <div class="yibuFrameContent con_40_46  image_Style2  " style="overflow:visible;;"> 
              <div class="w-image-box" data-filltype="1" id="div_con_40_46" style="height: 126px;"> 
               <a target="_self"> <img src="{resource_url('img/tdkcImg/-12839.png')}" alt="-4514-1" title="-4514-1" id="img_smv_con_40_46" style="width: 126px; height: auto; margin-top: -8.59091px; margin-left: 0px;" /> </a> 
              </div> 
              <script type="text/javascript">
$(function () {
    InitImageSmv("con_40_46", "126", "126", "1");

});
</script> 
             </div>
            </div> 
           </div> 
          </div>
         </div>
        </div>
        <div id="smv_con_41_37" ctype="area" smanim="{ &quot;delay&quot;:1.5,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Up&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461159" cstyle="Style1" ccolor="Item3" areaid="Main" iscontainer="True" pvid="" tareaid="Main" re-direction="all" daxis="All" isdeletable="True" style="height: 178px; width: 543px; left: 577px; top: 2165px; z-index: 8; opacity: 1;" sm-finished="true" smexecuted="1">
         <div class="yibuFrameContent con_41_37  area_Style1  " style="overflow:visible;;">
          <div class="w-container"> 
           <div class="smAreaC" id="smc_Area0" cid="con_41_37"> 
            <div id="smv_con_42_37" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_41_37" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 26px; width: 79px; left: 185px; top: 40px;z-index:3;">
             <div class="yibuFrameContent con_42_37  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_42_37" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_42_37" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#000000; font-family:Microsoft YaHei; font-size:18px">戴维 &nbsp;&nbsp;</span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_43_37" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item5" areaid="Area0" iscontainer="False" pvid="con_41_37" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 19px; width: 83px; left: 241px; top: 42px;z-index:4;">
             <div class="yibuFrameContent con_43_37  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_43_37" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_43_37" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#656d78; font-family:Microsoft YaHei; font-size:12px">研发工程师</span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_44_37" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item5" areaid="Area0" iscontainer="False" pvid="con_41_37" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 76px; width: 278px; left: 186px; top: 82px;z-index:5;">
             <div class="yibuFrameContent con_44_37  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_44_37" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_44_37" style="height: 100%; word-wrap:break-word;"> 
                <p><span style="color:#999999"><span style="line-height:1.5">2年以上PHP开发经验，熟悉THINKPHP开源框架；熟悉JS开发，熟悉Json和xml数据交换格式。</span></span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_45_37" ctype="image" class="esmartMargin smartAbs " cpid="461159" cstyle="Style2" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_41_37" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 126px; width: 126px; left: 31px; top: 25px;z-index:1;">
             <div class="yibuFrameContent con_45_37  image_Style2  " style="overflow:visible;;"> 
              <div class="w-image-box" data-filltype="1" id="div_con_45_37" style="height: 126px;"> 
               <a target="_self"> <img src="{resource_url('img/tdkcImg/-12841.png')}" alt="-4513-1" title="-4513-1" id="img_smv_con_45_37" style="width: 126px; height: auto; margin-top: -8.59091px; margin-left: 0px;" /> </a> 
              </div> 
              <script type="text/javascript">
$(function () {
    InitImageSmv("con_45_37", "126", "126", "1");

});
</script> 
             </div>
            </div> 
           </div> 
          </div>
         </div>
        </div>
        <div id="smv_con_57_20" ctype="altas" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 322px; width: 1140px; left: 0px; top: 957px;z-index:1;">
         <div class="yibuFrameContent con_57_20  altas_Style1" style="overflow:visible;;">
          <div class="w-imglist-collections xn-resize"> 
           <div id="con_57_20_collections" class="w-imglist-list"> 
            <ul class="w-imglist-ul" id="ulList_con_57_20"> 
             <li class="w-imglist-item"> 
              <div class="w-imglist-in"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584544.jpg')}" alt="zuijuhuoliqiyezhengshu" title="荣誉证书" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">荣誉证书</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584544.jpg')}" alt="zuijuhuoliqiyezhengshu" title="荣誉证书" init="ok" style="width: auto; height: 336px; margin-left: -7.5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">荣誉证书</a> 
              </div></li> 
             <li class="w-imglist-item"> 
              <div class="w-imglist-in"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584538.jpg')}" alt="hedaozhilijinjiang" title="荣誉证书" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">荣誉证书</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584538.jpg')}" alt="hedaozhilijinjiang" title="荣誉证书" init="ok" style="width: auto; height: 336px; margin-left: -7.5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">荣誉证书</a> 
              </div></li> 
             <li class="w-imglist-item"> 
              <div class="w-imglist-in"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584542.jpg')}" alt="tianyuwantongjiang" title="荣誉证书" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">荣誉证书</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584542.jpg')}" alt="tianyuwantongjiang" title="荣誉证书" init="ok" style="width: auto; height: 336px; margin-left: -7.5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">荣誉证书</a> 
              </div></li> 
             <div style="clear:both;"></div> 
            </ul> 
           </div> 
          </div> 
          <!--//End w-imglist-collections--> 
          <script type="text/javascript">
    var con_57_20_navIndex = $('#smv_con_57_20').css("z-index");
    //图片横向竖向居中显示
    function  con_57_20_imgZoomInit() {
        $('#con_57_20_collections .w-imglist-item').append(function () {
            ht = $(this).find('.w-imglist-in').html();
            return "<div class='w-imglist-bigimg' style='z-index: 78'>" + ht + "</div>";
        });
        $("#con_57_20_collections .w-imglist-item .w-imglist-in img").cutFill("350", "240");
        $("#con_57_20_collections .w-imglist-item .w-imglist-bigimg img").cutFill("489.99999999999994", "336");
    }

    function  con_57_20_InitImg() {
        con_57_20_imgZoomInit();
        if ("on" == "on") {
            $('#con_57_20_collections .w-imglist-item').hover(function () {
                $('#smv_con_57_20').css("z-Index", "9999999");
                var img = $(this).find(".w-imglist-bigimg img");
                var realWidth;//原始宽度
                var realHeight;//原始高度
                var height = parseInt(240);
                var IntHeight = height;
                $(this).addClass('on');
                realWidth = img.width();
                realHeight = img.height();
                img.css("marginLeft", (-realWidth / 2) + "px").css("marginTop", (-realHeight / 2) + "px");
                if (realWidth < IntHeight) {
                    $(this).find('.w-imglist-in').css('left', '0')
                } else {
                    $(this).find('.w-imglist-in').css('left', -realWidth / 4)
                };
            }, function () {
                var img = $(this).find(".w-imglist-in img");
                $(this).animate({
                    height: 240 + "px"
                },
                100).removeClass('on');
                $('#smv_con_57_20').css("z-Index", con_57_20_navIndex);
                $(this).find('.w-imglist-in').css('left', '0')
            });
        }
    }
    function callback_con_57_20() {
        con_57_20_InitImg();
    }
    $(function () {
        if ("Prev" != "Design") { $(".w-imglist-collections").parent().removeClass("overflow_hidden"); }
        con_57_20_InitImg();
    });
</script>
         </div>
        </div>
        <div id="smv_con_48_54" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item3" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 24px; width: 87px; left: 497px; top: 88px;z-index:5;">
         <div class="yibuFrameContent con_48_54  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_48_54" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_48_54" style="height: 100%; word-wrap:break-word;"> 
            <p><span style="color:#888888"><span style="font-family:&quot;Microsoft YaHei&quot;; font-size:14px">Qualifications</span></span></p> 
           </div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_52_38" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 38px; width: 123px; left: 479px; top: 837px;z-index:10;">
         <div class="yibuFrameContent con_52_38  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_52_38" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_52_38" style="height: 100%; word-wrap:break-word;"> 
            <p><span style="color:#444444"><span style="font-size:30px"><span style="font-family:Tahoma,Geneva,sans-serif">荣誉</span></span></span><span style="color:#444444"><span style="font-size:30px"><span style="font-family:Tahoma,Geneva,sans-serif">证书</span></span></span></p> 
           </div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_53_48" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item3" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 24px; width: 53px; left: 516px; top: 887px;z-index:5;">
         <div class="yibuFrameContent con_53_48  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_53_48" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_53_48" style="height: 100%; word-wrap:break-word;"> 
            <p><span style="color:#888888"><span style="font-family:&quot;Microsoft YaHei&quot;; font-size:14px">Honor</span></span></p> 
           </div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_56_7" ctype="altas" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 513px; width: 1140px; left: -14px; top: 190px; z-index: 1;">
         <div class="yibuFrameContent con_56_7  altas_Style1" style="overflow:visible;;">
          <div class="w-imglist-collections xn-resize"> 
           <div id="con_56_7_collections" class="w-imglist-list"> 
            <ul class="w-imglist-ul" id="ulList_con_56_7"> 
             <li class="w-imglist-item"> 
              <div class="w-imglist-in"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584543.jpg')}" alt="yingyezhizhao" title="营业执照" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">营业执照</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584543.jpg')}" alt="yingyezhizhao" title="营业执照" init="ok" style="width: auto; height: 336px; margin-left: -7.5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">营业执照</a> 
              </div></li> 
             <li class="w-imglist-item"> 
              <div class="w-imglist-in"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584541.jpg')}" alt="linyezizhizhengshu" title="林业调查规划设计资质证书" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">林业调查规划设计资质证书</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584541.jpg')}" alt="linyezizhizhengshu" title="林业调查规划设计资质证书" init="ok" style="width: auto; height: 336px; margin-left: -7.5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">林业调查规划设计资质证书</a> 
              </div></li> 
             <li class="w-imglist-item" style="height: 240px;"> 
              <div class="w-imglist-in" style="left: 0px;"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584540.jpg')}" alt="kancejigou" title="土地勘测机构注册证书" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">土地勘测机构注册证书</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584540.jpg')}" alt="kancejigou" title="土地勘测机构注册证书" init="ok" style="width: auto; height: 336px; margin-left: -252px; margin-top: -168px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">土地勘测机构注册证书</a> 
              </div></li> 
             <li class="w-imglist-item"> 
              <div class="w-imglist-in"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584537.jpg')}" alt="dengjidaili" title="土地调查登记代理机构注册证书" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">土地调查登记代理机构注册证书</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584537.jpg')}" alt="dengjidaili" title="土地调查登记代理机构注册证书" init="ok" style="width: auto; height: 336px; margin-left: -7.5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">土地调查登记代理机构注册证书</a> 
              </div></li> 
             <li class="w-imglist-item"> 
              <div class="w-imglist-in"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584535.jpg')}" alt="budongchandiaochadengjidaili" title="不动产调查登记代理资质证书" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">不动产调查登记代理资质证书</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584535.jpg')}" alt="budongchandiaochadengjidaili" title="不动产调查登记代理资质证书" init="ok" style="width: auto; height: 336px; margin-left: -7.5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">不动产调查登记代理资质证书</a> 
              </div></li> 
             <li class="w-imglist-item" style="height: 240px;"> 
              <div class="w-imglist-in" style="left: 0px;"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584536.jpg')}" alt="cehuizizhi" title="测绘资质证书" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">测绘资质证书</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/7584536.jpg')}" alt="cehuizizhi" title="测绘资质证书" init="ok" style="width: auto; height: 336px; margin-left: -252px; margin-top: -168px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">测绘资质证书</a> 
              </div></li> 
             <div style="clear:both;"></div> 
            </ul> 
           </div> 
          </div> 
          <!--//End w-imglist-collections--> 
          <script type="text/javascript">
    var con_56_7_navIndex = $('#smv_con_56_7').css("z-index");
    //图片横向竖向居中显示
    function  con_56_7_imgZoomInit() {
        $('#con_56_7_collections .w-imglist-item').append(function () {
            ht = $(this).find('.w-imglist-in').html();
            return "<div class='w-imglist-bigimg' style='z-index: 78'>" + ht + "</div>";
        });
        $("#con_56_7_collections .w-imglist-item .w-imglist-in img").cutFill("350", "240");
        $("#con_56_7_collections .w-imglist-item .w-imglist-bigimg img").cutFill("489.99999999999994", "336");
    }

    function  con_56_7_InitImg() {
        con_56_7_imgZoomInit();
        if ("on" == "on") {
            $('#con_56_7_collections .w-imglist-item').hover(function () {
                $('#smv_con_56_7').css("z-Index", "9999999");
                var img = $(this).find(".w-imglist-bigimg img");
                var realWidth;//原始宽度
                var realHeight;//原始高度
                var height = parseInt(240);
                var IntHeight = height;
                $(this).addClass('on');
                realWidth = img.width();
                realHeight = img.height();
                img.css("marginLeft", (-realWidth / 2) + "px").css("marginTop", (-realHeight / 2) + "px");
                if (realWidth < IntHeight) {
                    $(this).find('.w-imglist-in').css('left', '0')
                } else {
                    $(this).find('.w-imglist-in').css('left', -realWidth / 4)
                };
            }, function () {
                var img = $(this).find(".w-imglist-in img");
                $(this).animate({
                    height: 240 + "px"
                },
                100).removeClass('on');
                $('#smv_con_56_7').css("z-Index", con_56_7_navIndex);
                $(this).find('.w-imglist-in').css('left', '0')
            });
        }
    }
    function callback_con_56_7() {
        con_56_7_InitImg();
    }
    $(function () {
        if ("Prev" != "Design") { $(".w-imglist-collections").parent().removeClass("overflow_hidden"); }
        con_56_7_InitImg();
    });
</script>
         </div>
        </div>
       </div>
      </div>
      <input type="hidden" name="__RequestVerificationToken" id="token__RequestVerificationToken" value="pmvrKKeGb1h_HEs_eZrS10JpTQt2qA961wFT_3-pqXuPqGVCCAtzC0Zl16iqykzbxjWdf8xnXpSqaTqw5HpHBfuDe0cLpIPWcjSRSv3KmezuUqjFrlgCQrH9Cypjf5Hu0" /> 
     </div> 
    </div> 
   </div> 

  </div>
 
  
  {include file="common/footer.tpl"}
	
