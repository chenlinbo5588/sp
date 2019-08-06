{include file="common/header.tpl"}
  <link href="{resource_url('css/tdkcCss/461093_Pc_zh-CN.css')}" rel="stylesheet">
  <link href="{resource_url('css/tdkcCss/view.css')}" rel="stylesheet" type="text/css" /> 

  <input type="hidden" id="pageinfo" value="461093" data-type="1" data-device="Pc" data-entityid="461093" /> 
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
      <div class="smvWrapper" style="min-width:1000px;  position: relative; background-color: rgb(250, 250, 250); background-image: none; background-repeat: no-repeat; background:-moz-linear-gradient(top, none, none);background:-webkit-gradient(linear, left top, left bottom, from(none), to(none));background:-o-linear-gradient(top, none, none);background:-ms-linear-gradient(top, none, none);background:linear-gradient(top, none, none);;background-position:0 0;background-size:auto;" bgscroll="none">
       <div class="smvContainer" id="smv_Main" cpid="461093" style="min-height:400px;width:1000px;height:1032px;  position: relative; ">
        <div id="smv_con_1_49" ctype="text" smanim="{ &quot;delay&quot;:0.4,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Left&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461093" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 41px; width: 306px; left: 347px; top: 130px;z-index:3;">
         <div class="yibuFrameContent con_1_49  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_1_49" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_1_49" style="height: 100%; word-wrap:break-word;"> 
            <p style="text-align:center"><span style="color:#444444"><span style="font-family:Arial,Helvetica,sans-serif"><span style="font-size:30px"><strong>SERVICE</strong></span></span></span></p> 
           </div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_2_32" ctype="image" smanim="{ &quot;delay&quot;:0.2,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Down&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461093" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 27px; width: 31px; left: 486px; top: 73px;z-index:4;">
         <div class="yibuFrameContent con_2_32  image_Style1  " style="overflow:visible;;"> 
          <div class="w-image-box" data-filltype="0" id="div_con_2_32"> 
           <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/-27205.png')}" alt="服务" title="服务" id="img_smv_con_2_32" style="width: 31px; height:27px;" /> </a> 
          </div> 
          <script type="text/javascript">
    $(function () {
        InitImageSmv("con_2_32", "31", "27", "0");
    });
</script> 
         </div>
        </div>
        <div id="smv_con_3_26" ctype="line" smanim="{ &quot;delay&quot;:0.0,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Left&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461093" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="x" daxis="All" isdeletable="True" style="height: 20px; width: 461px; left: 1px; top: 77px;z-index:5;">
         <div class="yibuFrameContent con_3_26  line_Style1  " style="overflow:visible;;">
          <!-- w-line --> 
          <div style="position:relative; height:100%"> 
           <div class="w-line" style="position:absolute;top:50%;" linetype="horizontal"></div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_4_52" ctype="line" smanim="{ &quot;delay&quot;:0.0,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Right&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461093" cstyle="Style1" ccolor="Item0" areaid="Main" iscontainer="False" pvid="" tareaid="Main" re-direction="x" daxis="All" isdeletable="True" style="height: 20px; width: 461px; left: 539px; top: 77px;z-index:5;">
         <div class="yibuFrameContent con_4_52  line_Style1  " style="overflow:visible;;">
          <!-- w-line --> 
          <div style="position:relative; height:100%"> 
           <div class="w-line" style="position:absolute;top:50%;" linetype="horizontal"></div> 
          </div> 
         </div>
        </div>
        <div id="smv_con_5_24" ctype="area" smanim="{ &quot;delay&quot;:0.5,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Left&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461093" cstyle="Style2" ccolor="Item0" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 344px; width: 310px; left: 1px; top: 214px;z-index:6;">
         <div class="yibuFrameContent con_5_24  area_Style2  " style="overflow:visible;;">
          <div class="w-container"> 
           <div class="smAreaC" id="smc_Area0" cid="con_5_24"> 
            <div id="smv_con_6_17" ctype="image" class="esmartMargin smartAbs " cpid="461093" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_5_24" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 110px; width: 109px; left: 100px; top: 40px;z-index:2;">
             <div class="yibuFrameContent con_6_17  image_Style1  " style="overflow:visible;;"> 
              <div class="w-image-box" data-filltype="0" id="div_con_6_17"> 
               <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/6109483.png')}" alt="3" title="3" id="img_smv_con_6_17" style="width: 109px; height:110px;" /> </a> 
              </div> 
              <script type="text/javascript">
    $(function () {
        InitImageSmv("con_6_17", "109", "110", "0");
    });
</script> 
             </div>
            </div>
            <div id="smv_con_7_39" ctype="text" class="esmartMargin smartAbs " cpid="461093" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_5_24" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 140px; width: 279px; left: 16px; top: 185px;z-index:3;">
             <div class="yibuFrameContent con_7_39  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_7_39" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_7_39" style="height: 100%; word-wrap:break-word;"> 
                <p style="text-align:center"><strong><span style="line-height:1.75"><span style="color:#555555"><span style="font-family:Microsoft JhengHei"><span style="font-size:18px">工程测量</span></span></span></span></strong></p> 
                <p style="text-align:center"><span style="color:#888888"><span style="line-height:1.75"><span style="font-size:14px">控制测量、地形测量、</span></span></span><span style="color:#888888"><span style="line-height:1.75"><span style="font-size:14px">城乡规划定线测量、</span></span></span><span style="color:#888888"><span style="font-size:14px"><span style="line-height:1.75">城乡用地测量、</span></span></span><span style="color:#888888"><span style="font-size:14px"><span style="line-height:1.75">建筑工程测量、日照测量、</span></span></span><span style="color:#888888"><span style="font-size:14px"><span style="line-height:1.75">道路桥梁测量、线路工程测量、隧道测量、竣工测量</span></span></span></p> 
                <p style="text-align:center">&nbsp;</p> 
               </div> 
              </div> 
             </div>
            </div> 
           </div> 
          </div>
         </div>
        </div>
        <div id="smv_con_43_59" ctype="area" smanim="{ &quot;delay&quot;:0.9,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Left&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461093" cstyle="Style2" ccolor="Item0" areaid="Main" iscontainer="True" pvid="" tareaid="Main" re-direction="all" daxis="All" isdeletable="True" style="height: 344px; width: 310px; left: 690px; top: 214px;z-index:6;">
         <div class="yibuFrameContent con_43_59  area_Style2  " style="overflow:visible;;">
          <div class="w-container"> 
           <div class="smAreaC" id="smc_Area0" cid="con_43_59"> 
            <div id="smv_con_45_59" ctype="text" class="esmartMargin smartAbs " cpid="461093" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_43_59" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 120px; width: 278px; left: 16px; top: 178px;z-index:3;">
             <div class="yibuFrameContent con_45_59  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_45_59" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_45_59" style="height: 100%; word-wrap:break-word;"> 
                <p style="text-align:center"><strong><span style="line-height:1.75"><span style="color:#555555"><span style="font-family:Microsoft JhengHei"><span style="font-size:18px">房产测绘</span></span></span></span></strong></p> 
                <p style="text-align:center"><span style="line-height:1.75"><span style="color:#888888"><span style="font-size:14px">平面控制测量、房产面积预测算、房产面积测算、房产调查与测量、房产变更调查与测量、房产图测绘</span></span></span></p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_44_59" ctype="image" class="esmartMargin smartAbs " cpid="461093" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_43_59" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 110px; width: 109px; left: 100px; top: 40px;z-index:2;">
             <div class="yibuFrameContent con_44_59  image_Style1  " style="overflow:visible;;"> 
              <div class="w-image-box" data-filltype="0" id="div_con_44_59"> 
               <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/6109480.png')}" alt="6084528" title="6084528" id="img_smv_con_44_59" style="width: 109px; height:110px;" /> </a> 
              </div> 
              <script type="text/javascript">
    $(function () {
        InitImageSmv("con_44_59", "109", "110", "0");
    });
</script> 
             </div>
            </div> 
           </div> 
          </div>
         </div>
        </div>
        <div id="smv_con_26_54" ctype="area" smanim="{ &quot;delay&quot;:0.7,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Left&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461093" cstyle="Style2" ccolor="Item0" areaid="Main" iscontainer="True" pvid="" tareaid="Main" re-direction="all" daxis="All" isdeletable="True" style="height: 344px; width: 310px; left: 345px; top: 214px;z-index:6;">
         <div class="yibuFrameContent con_26_54  area_Style2  " style="overflow:visible;;">
          <div class="w-container"> 
           <div class="smAreaC" id="smc_Area0" cid="con_26_54"> 
            <div id="smv_con_27_54" ctype="image" class="esmartMargin smartAbs " cpid="461093" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_26_54" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 109px; width: 89px; left: 109px; top: 39px;z-index:2;">
             <div class="yibuFrameContent con_27_54  image_Style1  " style="overflow:visible;;"> 
              <div class="w-image-box" data-filltype="0" id="div_con_27_54"> 
               <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/6109481.png')}" alt="地籍图标" title="地籍图标" id="img_smv_con_27_54" style="width: 89px; height:109px;" /> </a> 
              </div> 
              <script type="text/javascript">
    $(function () {
        InitImageSmv("con_27_54", "89", "109", "0");
    });
</script> 
             </div>
            </div>
            <div id="smv_con_28_54" ctype="text" class="esmartMargin smartAbs " cpid="461093" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_26_54" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 120px; width: 278px; left: 17px; top: 182px;z-index:3;">
             <div class="yibuFrameContent con_28_54  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_28_54" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_28_54" style="height: 100%; word-wrap:break-word;"> 
                <p style="text-align:center"><strong><span style="line-height:1.75"><span style="color:#555555"><span style="font-family:Microsoft JhengHei"><span style="font-size:18px">地籍测绘</span></span></span></span></strong></p> 
                <p style="text-align:center"><span style="color:#888888"><span style="line-height:1.75"><span style="font-size:14px">控制测量、界址测量、地籍调查、面积量算</span></span></span></p> 
               </div> 
              </div> 
             </div>
            </div> 
           </div> 
          </div>
         </div>
        </div>
        <div id="smv_con_33_56" ctype="area" smanim="{ &quot;delay&quot;:0.75,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Left&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461093" cstyle="Style2" ccolor="Item0" areaid="Main" iscontainer="True" pvid="" tareaid="Main" re-direction="all" daxis="All" isdeletable="True" style="height: 344px; width: 310px; left: 1px; top: 597px;z-index:6;">
         <div class="yibuFrameContent con_33_56  area_Style2  " style="overflow:visible;;">
          <div class="w-container"> 
           <div class="smAreaC" id="smc_Area0" cid="con_33_56"> 
            <div id="smv_con_34_56" ctype="image" class="esmartMargin smartAbs " cpid="461093" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_33_56" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 110px; width: 109px; left: 100px; top: 40px;z-index:2;">
             <div class="yibuFrameContent con_34_56  image_Style1  " style="overflow:visible;;"> 
              <div class="w-image-box" data-filltype="0" id="div_con_34_56"> 
               <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/6109478.png')}" alt="1" title="1" id="img_smv_con_34_56" style="width: 109px; height:110px;" /> </a> 
              </div> 
              <script type="text/javascript">
    $(function () {
        InitImageSmv("con_34_56", "109", "110", "0");
    });
</script> 
             </div>
            </div>
            <div id="smv_con_35_56" ctype="text" class="esmartMargin smartAbs " cpid="461093" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_33_56" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 120px; width: 278px; left: 16px; top: 178px;z-index:3;">
             <div class="yibuFrameContent con_35_56  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_35_56" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_35_56" style="height: 100%; word-wrap:break-word;"> 
                <p style="text-align:center"><strong><span style="line-height:1.75"><span style="color:#555555"><span style="font-family:Microsoft JhengHei"><span style="font-size:18px">行政区域界线测绘</span></span></span></span></strong></p> 
                <p style="text-align:center"><span style="font-size:14px"><span style="line-height:1.75"><span style="color:#888888">界桩埋设、边界点测定、边界线及相关地形要素调绘、边界协议书附图标绘</span></span></span></p> 
               </div> 
              </div> 
             </div>
            </div> 
           </div> 
          </div>
         </div>
        </div>
        <div id="smv_con_36_56" ctype="area" smanim="{ &quot;delay&quot;:0.9,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Left&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461093" cstyle="Style2" ccolor="Item0" areaid="Main" iscontainer="True" pvid="" tareaid="Main" re-direction="all" daxis="All" isdeletable="True" style="height: 344px; width: 310px; left: 345px; top: 597px;z-index:6;">
         <div class="yibuFrameContent con_36_56  area_Style2  " style="overflow:visible;;">
          <div class="w-container"> 
           <div class="smAreaC" id="smc_Area0" cid="con_36_56"> 
            <div id="smv_con_37_56" ctype="image" class="esmartMargin smartAbs " cpid="461093" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_36_56" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 110px; width: 109px; left: 100px; top: 40px;z-index:2;">
             <div class="yibuFrameContent con_37_56  image_Style1  " style="overflow:visible;;"> 
              <div class="w-image-box" data-filltype="0" id="div_con_37_56"> 
               <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/6109479.png')}" alt="2" title="2" id="img_smv_con_37_56" style="width: 109px; height:110px;" /> </a> 
              </div> 
              <script type="text/javascript">
    $(function () {
        InitImageSmv("con_37_56", "109", "110", "0");
    });
</script> 
             </div>
            </div>
            <div id="smv_con_38_56" ctype="text" class="esmartMargin smartAbs " cpid="461093" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_36_56" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 120px; width: 278px; left: 16px; top: 178px;z-index:3;">
             <div class="yibuFrameContent con_38_56  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_38_56" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_38_56" style="height: 100%; word-wrap:break-word;"> 
                <p style="text-align:center"><strong><span style="line-height:1.75"><span style="color:#555555"><span style="font-family:Microsoft JhengHei"><span style="font-size:18px">地理信息系统工程土地规划</span></span></span></span></strong></p> 
                <p style="text-align:center"><span style="font-size:14px"><span style="line-height:1.75"><span style="color:#888888">土地利用总体规划、土地开发整理复垦</span></span></span></p> 
                <p style="text-align:center">&nbsp;</p> 
               </div> 
              </div> 
             </div>
            </div> 
           </div> 
          </div>
         </div>
        </div>
        <div id="smv_con_39_56" ctype="area" smanim="{ &quot;delay&quot;:1.0,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Left&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="461093" cstyle="Style2" ccolor="Item0" areaid="Main" iscontainer="True" pvid="" tareaid="Main" re-direction="all" daxis="All" isdeletable="True" style="height: 344px; width: 310px; left: 690px; top: 597px;z-index:6;">
         <div class="yibuFrameContent con_39_56  area_Style2  " style="overflow:visible;;">
          <div class="w-container"> 
           <div class="smAreaC" id="smc_Area0" cid="con_39_56"> 
            <div id="smv_con_40_56" ctype="image" class="esmartMargin smartAbs " cpid="461093" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_39_56" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 90px; width: 90px; left: 105px; top: 50px;z-index:2;">
             <div class="yibuFrameContent con_40_56  image_Style1  " style="overflow:visible;;"> 
              <div class="w-image-box" data-filltype="0" id="div_con_40_56"> 
               <a target="_self" href=""> <img src="{resource_url('img/tdkcImg/6109482.png')}" alt="更多图标" title="更多图标" id="img_smv_con_40_56" style="width: 90px; height:90px;" /> </a> 
              </div> 
              <script type="text/javascript">
    $(function () {
        InitImageSmv("con_40_56", "90", "90", "0");
    });
</script> 
             </div>
            </div>
            <div id="smv_con_41_56" ctype="text" class="esmartMargin smartAbs " cpid="461093" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_39_56" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 30px; width: 278px; left: 16px; top: 176px;z-index:3;">
             <div class="yibuFrameContent con_41_56  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_41_56" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_41_56" style="height: 100%; word-wrap:break-word;"> 
                <p style="text-align:center"><strong><span style="line-height:1.75"><span style="color:#555555"><span style="font-family:Microsoft JhengHei"><span style="font-size:18px">其他业务</span></span></span></span></strong></p> 
                <p style="text-align:center">&nbsp;</p> 
                <p style="text-align:center">&nbsp;</p> 
               </div> 
              </div> 
             </div>
            </div>
            <div id="smv_con_46_57" ctype="text" class="esmartMargin smartAbs " cpid="461093" cstyle="Style1" ccolor="Item2" areaid="Area0" iscontainer="False" pvid="con_39_56" tareaid="Main" re-direction="all" daxis="All" isdeletable="True" style="height: 120px; width: 278px; left: 18px; top: 210px;z-index:3;">
             <div class="yibuFrameContent con_46_57  text_Style1  " style="overflow:hidden;;">
              <div id="txt_con_46_57" style="height: 100%;"> 
               <div class="editableContent" id="txtc_con_46_57" style="height: 100%; word-wrap:break-word;"> 
                <p style="text-align:center"><span style="line-height:1.75"><span style="color:#888888"><span style="font-size:14px">矿业权核查、日照分析、规划指标复核、测绘航空摄影、工程土地规划、摄影测量与遥感、土地利用总体规划修编、林业调查规划设计、不动产权藉调查</span></span></span></p> 
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
      <input type="hidden" name="__RequestVerificationToken" id="token__RequestVerificationToken" value="jh40T3rk_k7vf-5b8eYRZuadMYPRBNU6G9z2vqP03tmO5R4Vxsx2i3NnwEtOFxwPUn8IhA_lcQpFjp8nQe17rainN8H0b_J4a8VUEUgDUpOclWlJBEyBnnm42l7p1rNZ0" /> 
     </div> 
    </div> 
   </div> 

  </div>
  
  {include file="common/footer.tpl"}
	
