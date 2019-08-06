{include file="common/header.tpl"}
  <link href="{resource_url('css/tdkcCss/view.css')}" rel="stylesheet" type="text/css" /> 
  <link href="{resource_url('css/tdkcCss/461397_Pc_zh-CN.css')}" rel="stylesheet" />
  <script type="text/javascript" id="jplaceholder" src="{resource_url('js/tdkcJs/jplaceholder.js')}"></script>
  <input type="hidden" id="pageinfo" value="461397" data-type="1" data-device="Pc" data-entityid="461397" /> 
  <input id="txtDeviceSwitchEnabled" value="show" type="hidden" /> 
  <input type="hidden" id="secUrl" data-host="c1797065108ffy.scd.wezhan.cn" data-pathname="/lxfstk" data-search="" data-hash="" /> 
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
       <div class="smvContainer" id="smv_Main" cpid="461397" style="min-height:400px;width:1000px;height:872px;  position: relative; ">
        <div id="smv_con_2_19" ctype="text" smanim="{ &quot;delay&quot;:0.75,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Down&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461397" cstyle="Style1" ccolor="Item2" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 27px; width: 94px; left: 456px; top: 69px; z-index: 3; opacity: 1;" sm-finished="true" smexecuted="1">
         <div class="yibuFrameContent con_2_19  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_2_19" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_2_19" style="height: 100%; word-wrap:break-word;"> 
            <p style="text-align:center"><span style="color:#ffffff"><span style="font-size:20px">企业自简</span></span></p> 
           </div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_58_50" ctype="line" class="esmartMargin smartAbs " cpid="461397" cstyle="Style1" ccolor="Item0" areaid="Main" iscontainer="False" pvid="" tareaid="Main" re-direction="x" daxis="All" isdeletable="True" style="height: 5px; width: 45px; left: 410px; top: 378px;z-index:10005;">
         <div class="yibuFrameContent con_58_50  line_Style1  " style="overflow:visible;;">
          <!-- w-line --> 
          <div style="position:relative; height:100%"> 
           <div class="w-line" style="position:absolute;top:50%;" linetype="horizontal"></div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_60_50" ctype="line" class="esmartMargin smartAbs " cpid="461397" cstyle="Style1" ccolor="Item0" areaid="Main" iscontainer="False" pvid="" tareaid="Main" re-direction="x" daxis="All" isdeletable="True" style="height: 20px; width: 45px; left: 548px; top: 371px;z-index:10005;">
         <div class="yibuFrameContent con_60_50  line_Style1  " style="overflow:visible;;">
          <!-- w-line --> 
          <div style="position:relative; height:100%"> 
           <div class="w-line" style="position:absolute;top:50%;" linetype="horizontal"></div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_7_29" ctype="text" smanim="{ &quot;delay&quot;:1.0,&quot;duration&quot;:1.0,&quot;direction&quot;:&quot;Down&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461397" cstyle="Style1" ccolor="Item2" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 67px; width: 700px; left: 150px; top: 138px; z-index: 3; opacity: 1;" sm-finished="true" smexecuted="1">
         <div class="yibuFrameContent con_7_29  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_7_29" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_7_29" style="height: 100%; word-wrap:break-word;"> 
            <p style="text-align:center"><span style="line-height:1.5"><span style="color:#ffffff"><span style="font-size:14px">我司是国家乙级测绘企业，</span></span></span></p> 
            <p style="text-align:center"><span style="line-height:1.5"><span style="color:#ffffff"><span style="font-size:14px">拥有多名中高级技术人员以及完整的服务团队和高端的测量仪器，</span></span></span></p> 
            <p style="text-align:center"><span style="line-height:1.5"><span style="color:#ffffff"><span style="font-size:14px">具有完成多种高难度的工程的能力</span></span></span></p> 
           </div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_11_24" ctype="banner" class="esmartMargin smartAbs " cpid="461397" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="y" daxis="Y" isdeletable="True" style="height: 18px; width: 100%; left: 0px; top: 734px;z-index:10001;">
         <div class="yibuFrameContent con_11_24  banner_Style1  " style="overflow:visible;;">
          <div class="fullcolumn-inner smAreaC" id="smc_Area0" cid="con_11_24" style="width:1000px"> 
          </div> 
          <div id="bannerWrap_con_11_24" class="fullcolumn-outer" style="position: absolute; top: 0px; bottom: 0px; left: -456px; width: 1912px;"> 
          </div> 
          <script type="text/javascript">

    $(function () {
        var resize = function () {
            $("#smv_con_11_24 >.yibuFrameContent>.fullcolumn-inner").width($("#smv_con_11_24").parent().width());
            $('#bannerWrap_con_11_24').fullScreen(function (t) {
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
        <div id="smv_con_12_7" ctype="banner" class="esmartMargin smartAbs " cpid="461397" cstyle="Style1" ccolor="Item0" areaid="Main" iscontainer="True" pvid="" tareaid="Main" re-direction="y" daxis="Y" isdeletable="True" style="height: 98px; width: 100%; left: 0px; top: 751px;z-index:10001;">
         <div class="yibuFrameContent con_12_7  banner_Style1  " style="overflow:visible;;">
          <div class="fullcolumn-inner smAreaC" id="smc_Area0" cid="con_12_7" style="width:1000px"> 
           <div id="smv_con_16_1" ctype="image" class="esmartMargin smartAbs " cpid="461397" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_12_7" tareaid="Main" re-direction="all" daxis="All" isdeletable="True" style="height: 30px; width: 30px; left: 319px; top: 36px;z-index:2;">
            <div class="yibuFrameContent con_16_1  image_Style1  " style="overflow:visible;;"> 
             <div class="w-image-box" data-filltype="2" id="div_con_16_1" style="width: 30px; height: 30px;"> 
              <a target="_self" style="width: 100%; height: 100%;"> <img src="../../../../../static/img/tdkcImg/-2613.png" alt="wb" title="wb" id="img_smv_con_16_1" style="width: 30px; height:30px;" /> </a> 
             </div> 
             <script type="text/javascript">
    $(function () {
        InitImageSmv("con_16_1", "30", "30", "2");
    });
</script> 
            </div>
           </div>
           <div id="smv_con_17_6" ctype="image" class="esmartMargin smartAbs " cpid="461397" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_12_7" tareaid="Main" re-direction="all" daxis="All" isdeletable="True" style="height: 32px; width: 32px; left: 458px; top: 35px;z-index:2;">
            <div class="yibuFrameContent con_17_6  image_Style1  " style="overflow:visible;;"> 
             <div class="w-image-box" data-filltype="2" id="div_con_17_6" style="width: 32px; height: 32px;"> 
              <a target="_self" style="width: 100%; height: 100%;"> <img src="../../../../../static/img/tdkcImg/-2612.png" alt="QQ" title="QQ" id="img_smv_con_17_6" style="width: 32px; height:32px;" /> </a> 
             </div> 
             <script type="text/javascript">
    $(function () {
        InitImageSmv("con_17_6", "32", "32", "2");
    });
</script> 
            </div>
           </div>
           <div id="smv_con_18_10" ctype="image" class="esmartMargin smartAbs " cpid="461397" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_12_7" tareaid="Main" re-direction="all" daxis="All" isdeletable="True" style="height: 31px; width: 33px; left: 593px; top: 36px;z-index:2;">
            <div class="yibuFrameContent con_18_10  image_Style1  " style="overflow:visible;;"> 
             <div class="w-image-box" data-filltype="2" id="div_con_18_10" style="width: 33px; height: 31px;"> 
              <a target="_self" style="width: 100%; height: 100%;"> <img src="../../../../../static/img/tdkcImg/-2611.png" alt="wx" title="wx" id="img_smv_con_18_10" style="width: 33px; height:31px;" /> </a> 
             </div> 
             <script type="text/javascript">
    $(function () {
        InitImageSmv("con_18_10", "33", "31", "2");
    });
</script> 
            </div>
           </div>
          </div> 
          <div id="bannerWrap_con_12_7" class="fullcolumn-outer" style="position: absolute; top: 0px; bottom: 0px; left: -456px; width: 1912px;"> 
          </div> 
          <script type="text/javascript">

    $(function () {
        var resize = function () {
            $("#smv_con_12_7 >.yibuFrameContent>.fullcolumn-inner").width($("#smv_con_12_7").parent().width());
            $('#bannerWrap_con_12_7').fullScreen(function (t) {
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
        <div id="smv_con_24_1" ctype="image" class="esmartMargin smartAbs " cpid="461397" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 20px; width: 20px; left: 490px; top: 274px;z-index:10002;">
         <div class="yibuFrameContent con_24_1  image_Style1  " style="overflow:visible;;"> 
          <div class="w-image-box" data-filltype="2" id="div_con_24_1" style="width: 20px; height: 20px;"> 
           <a target="_self" style="width: 100%; height: 100%;"> <img src="../../../../../static/img/tdkcImg/-2615.png" alt="Rectangle-20" title="Rectangle-20" id="img_smv_con_24_1" style="width: 20px; height:20px;" /> </a> 
          </div> 
          <script type="text/javascript">
    $(function () {
        InitImageSmv("con_24_1", "20", "20", "2");
    });
</script> 
         </div>
        </div>
        <div id="smv_con_26_58" ctype="image" class="esmartMargin smartAbs " cpid="461397" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 20px; width: 20px; left: 444px; top: 274px;z-index:10003;">
         <div class="yibuFrameContent con_26_58  image_Style1  " style="overflow:visible;;"> 
          <div class="w-image-box" data-filltype="2" id="div_con_26_58" style="width: 20px; height: 20px;"> 
           <a target="_self" style="width: 100%; height: 100%;"> <img src="../../../../../static/img/tdkcImg/-2607.png" alt="Rectangle-24" title="Rectangle-24" id="img_smv_con_26_58" style="width: 20px; height:20px;" /> </a> 
          </div> 
          <script type="text/javascript">
    $(function () {
        InitImageSmv("con_26_58", "20", "20", "2");
    });
</script> 
         </div>
        </div>
        <div id="smv_con_28_4" ctype="image" class="esmartMargin smartAbs " cpid="461397" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 20px; width: 20px; left: 538px; top: 274px;z-index:10004;">
         <div class="yibuFrameContent con_28_4  image_Style1  " style="overflow:visible;;"> 
          <div class="w-image-box" data-filltype="2" id="div_con_28_4" style="width: 20px; height: 20px;"> 
           <a target="_self" style="width: 100%; height: 100%;"> <img src="../../../../../static/img/tdkcImg/-2607.png" alt="Rectangle-24" title="Rectangle-24" id="img_smv_con_28_4" style="width: 20px; height:20px;" /> </a> 
          </div> 
          <script type="text/javascript">
    $(function () {
        InitImageSmv("con_28_4", "20", "20", "2");
    });
</script> 
         </div>
        </div>
        <div id="smv_con_29_43" ctype="line" class="esmartMargin smartAbs " cpid="461397" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="x" daxis="All" isdeletable="True" style="height: 10px; width: 18px; left: 470px; top: 277px;z-index:10005;">
         <div class="yibuFrameContent con_29_43  line_Style1  " style="overflow:visible;;">
          <!-- w-line --> 
          <div style="position:relative; height:100%"> 
           <div class="w-line" style="position:absolute;top:50%;" linetype="horizontal"></div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_33_13" ctype="line" class="esmartMargin smartAbs " cpid="461397" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="x" daxis="All" isdeletable="True" style="height: 10px; width: 29px; left: -853px; top: 22px;z-index:10005;">
         <div class="yibuFrameContent con_33_13  line_Style1  " style="overflow:visible;;">
          <!-- w-line --> 
          <div style="position:relative; height:100%"> 
           <div class="w-line" style="position:absolute;top:50%;" linetype="horizontal"></div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_32_20" ctype="line" class="esmartMargin smartAbs " cpid="461397" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="x" daxis="All" isdeletable="True" style="height: 10px; width: 29px; left: -859px; top: 20px;z-index:10005;">
         <div class="yibuFrameContent con_32_20  line_Style1  " style="overflow:visible;;">
          <!-- w-line --> 
          <div style="position:relative; height:100%"> 
           <div class="w-line" style="position:absolute;top:50%;" linetype="horizontal"></div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_34_12" ctype="line" class="esmartMargin smartAbs " cpid="461397" cstyle="Style1" ccolor="Item0" areaid="Main" iscontainer="False" pvid="" tareaid="Main" re-direction="x" daxis="All" isdeletable="True" style="height: 10px; width: 18px; left: 513px; top: 277px;z-index:10005;">
         <div class="yibuFrameContent con_34_12  line_Style1  " style="overflow:visible;;">
          <!-- w-line --> 
          <div style="position:relative; height:100%"> 
           <div class="w-line" style="position:absolute;top:50%;" linetype="horizontal"></div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_57_7" ctype="leaveword" smanim="{ &quot;delay&quot;:1.2,&quot;duration&quot;:1.8,&quot;direction&quot;:&quot;&quot;,&quot;animationName&quot;:&quot;fadeIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461397" cstyle="Style4" ccolor="Item0" areaid="Main" iscontainer="False" pvid="" tareaid="Main" re-direction="x" daxis="All" isdeletable="True" style="height: 238px; width: 480px; left: 273px; top: 431px; z-index: 10019; opacity: 1;" sm-finished="true" smexecuted="1">
         <div class="yibuFrameContent con_57_7  leaveword_Style4  " style="overflow:visible;;">
          <div class="w-guestbook f-clearfix"> 
           <div class="w-verify w-hide"> 
            <span class="w-verify-in"><i class="mw-iconfont">끸</i><span class="w-verify-text"></span></span> 
           </div> 
           <!--w-guestbook-container--> 
           <div class="w-guestbook-container"> 
            <!--w-guestbook-item  验证错误样式--> 
            <div class="w-guestbook-item w-placeholder"> 
             <input type="text" class="w-guestbook-input" placeholder="输入你的名字" autocomplete="off" name="Subject" /> 
            </div> 
            <!--/w-guestbook-item--> 
            <!--w-guestbook-item--> 
            <div class="w-guestbook-item w-placeholder"> 
             <input class="w-guestbook-input" placeholder="输入地址" autocomplete="off" name="Message" /> 
            </div> 
            <!--/w-guestbook-item--> 
            <!--w-guestbook-item--> 
            <div class="w-guestbook-item w-placeholder"> 
             <input type="text" class="w-guestbook-input" placeholder="项目名称" autocomplete="off" name="Email" /> 
            </div> 
            <!--/w-guestbook-item--> 
            <!--w-guestbook-item--> 
            <div class="w-guestbook-code f-clearfix"> 
             <div class="w-guestbook-item w-placeholder"> 
              <input type="text" class="w-guestbook-input" placeholder="验证码" autocomplete="off" name="Captcha" /> 
             </div> 
             <div class="w-guestbook-codeimg">
              <img src="js/GenerateLeavewordCaptcha" alt="验证码" />
             </div> 
            </div> 
            <!--/w-guestbook-item--> 
           </div> 
           <!--/w-guestbook-container--> 
           <!--w-guestbook-bottom--> 
           <div class="w-guestbook-bottom f-clearfix"> 
            <a href="javascript:void(0)" class="w-guestbook-btn" name="Submit">提交</a> 
           </div> 
           <!--/w-guestbook-bottom--> 
          </div> 
          <!--/w-guestbook--> 
          <script type="text/javascript">
    $(function () {
            
        $('#smv_con_57_7').smartNPLeavewordControl({ controlId: 'smv_con_57_7', emptyPrefix:'请输入', correctMailPrefix: '请输入正确的', contentMaxLength: '不能超过200个字符', postSucess: '提交成功', postFail: '提交失败', correctMailValidate:'请输入正确格式的邮箱！'});
            
    });
    (function () {
        JPlaceHolder(leavewordPlaceHolder);
        function leavewordPlaceHolder() {
            var placeHeight = $(".w-guestbook-item.w-placeholder .placeholder-text .placeholder-text-in").height();
            $(".w-guestbook-item.w-placeholder .placeholder-text .placeholder-text-in").css("lineHeight", placeHeight + "px");
        }

    })(jQuery);
</script>
         </div>
        </div>
        <div id="smv_con_45_25" ctype="image" class="esmartMargin smartAbs " cpid="461397" cstyle="Style1" ccolor="Item0" areaid="Main" iscontainer="False" pvid="" tareaid="Main" re-direction="all" daxis="All" isdeletable="True" style="height: 20px; width: 21px; left: 529px; top: 638px;z-index:10020; transform:rotate(3deg);-ms-transform:rotate(3deg);-webkit-transform:rotate(3deg);transform-origin: 50% 50%;-ms-transform-origin: 50% 50%;-webkit-transform-origin: 50% 50%;">
         <div class="yibuFrameContent con_45_25  image_Style1  " style="overflow:visible;;"> 
          <div class="w-image-box" data-filltype="1" id="div_con_45_25" style="height: 20px;"> 
           <a target="_self"> <img src="../../../../../static/img/tdkcImg/-2628.png" alt="sending-copy" title="sending-copy" id="img_smv_con_45_25" style="width: 21px; height: auto; margin-top: -0.5px; margin-left: 0px;" /> </a> 
          </div> 
          <script type="text/javascript">
    $(function () {
        InitImageSmv("con_45_25", "21", "20", "1");
    });
</script> 
         </div>
        </div>
        <div id="smv_con_46_21" ctype="text" smanim="{ &quot;delay&quot;:1.1,&quot;duration&quot;:1.1,&quot;direction&quot;:&quot;Up&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461397" cstyle="Style1" ccolor="Item2" areaid="Main" iscontainer="False" pvid="" tareaid="Main" re-direction="all" daxis="All" isdeletable="True" style="height: 28px; width: 120px; left: 444px; top: 371px; z-index: 3; opacity: 1;" sm-finished="true" smexecuted="1">
         <div class="yibuFrameContent con_46_21  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_46_21" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_46_21" style="height: 100%; word-wrap:break-word;"> 
            <p style="text-align:center"><span style="font-size:20px"><span style="color:#555555">联系我们</span></span></p> 
           </div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_61_58" ctype="banner" class="esmartMargin smartAbs " cpid="461397" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="y" daxis="Y" isdeletable="True" style="height: 320px; width: 100%; left: 0px; top: 0px;z-index:0;">
         <div class="yibuFrameContent con_61_58  banner_Style1  " style="overflow:visible;;">
          <div class="fullcolumn-inner smAreaC" id="smc_Area0" cid="con_61_58" style="width:1000px"> 
          </div> 
          <div id="bannerWrap_con_61_58" class="fullcolumn-outer" style="position: absolute; top: 0px; bottom: 0px; left: -456px; width: 1912px;"> 
          </div> 
          <script type="text/javascript">

    $(function () {
        var resize = function () {
            $("#smv_con_61_58 >.yibuFrameContent>.fullcolumn-inner").width($("#smv_con_61_58").parent().width());
            $('#bannerWrap_con_61_58').fullScreen(function (t) {
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
     </div>
    </div>
   </div>
  </div>
  {include file="common/footer.tpl"}
	
