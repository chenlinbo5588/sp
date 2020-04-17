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
      <div class="smvWrapper" style="min-width:1120px;  position: relative; background-color: rgb(250, 250, 250); background-image: none; background-repeat: no-repeat; background:-moz-linear-gradient(top, none, none);background:-webkit-gradient(linear, left top, left bottom, from(none), to(none));background:-o-linear-gradient(top, none, none);background:-ms-linear-gradient(top, none, none);background:linear-gradient(top, none, none);;background-position:0 0;background-size:auto;" bgscroll="none">
       <div class="smvContainer" id="smv_Main" cpid="461159" style="min-height:400px;width:1120px;height:1300px;  position: relative; ">
        
        <div id="smv_con_47_46" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 38px; width: 123px; left: 479px; top: 46px;z-index:10;">
         <div class="yibuFrameContent con_47_46  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_47_46" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_47_46" style="height: 100%; word-wrap:break-word;"> 
            <p><span style="color:#444444"><span style="font-size:30px"><span style="font-family:Tahoma,Geneva,sans-serif">资质证书</span></span></span></p> 
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
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/2018.jpg')}" alt="zuijuhuoliqiyezhengshu" title="荣誉证书" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">荣誉证书</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/2018.jpg')}" alt="zuijuhuoliqiyezhengshu" title="荣誉证书" init="ok" style="width: auto; height: 336px; margin-left: -7.5px;" /> </a> 
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
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/2019ryzs.jpg')}" alt="tianyuwantongjiang" title="荣誉证书" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">荣誉证书</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/2019ryzs.jpg')}" alt="tianyuwantongjiang" title="荣誉证书" init="ok" style="width: auto; height: 336px; margin-left: -7.5px;" /> </a> 
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
        <div id="smv_con_48_54" ctype="text" class="esmartMargin smartAbs " cpid="461159" cstyle="Style1" ccolor="Item3" areaid="" iscontainer="False" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 24px; width: 100px; left: 497px; top: 88px;z-index:5;">
         <div class="yibuFrameContent con_48_54  text_Style1  " style="overflow:hidden;;">
          <div id="txt_con_48_54" style="height: 100%;"> 
           <div class="editableContent" id="txtc_con_48_54" style="height: 100%; word-wrap:break-word; "> 
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
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/yyzz.jpg')}" alt="yingyezhizhao" title="营业执照" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">营业执照</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/yyzz.jpg')}" alt="yingyezhizhao" title="营业执照" init="ok" style="width: auto; height: 336px; margin-left: -7.5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">营业执照</a> 
              </div></li> 
              <li class="w-imglist-item" style="height: 240px;"> 
              <div class="w-imglist-in" style="left: 0px;"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/chzz.jpg')}" alt="cehuizizhi" title="测绘资质证书" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">测绘资质证书</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/chzz.jpg')}" alt="cehuizizhi" title="测绘资质证书" init="ok" style="width: auto; height: 336px; margin-left: -252px; margin-top: -168px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">测绘资质证书</a> 
              </div></li> 
              <li class="w-imglist-item" style="height: 240px;"> 
              <div class="w-imglist-in" style="left: 0px;"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/tdkc.jpg')}" alt="kancejigou" title="土地勘测机构注册证书" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">土地勘测机构注册证书</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/tdkc.jpg')}" alt="kancejigou" title="土地勘测机构注册证书" init="ok" style="width: auto; height: 336px; margin-left: -252px; margin-top: -168px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">土地勘测机构注册证书</a> 
              </div></li>
              <li class="w-imglist-item"> 
              <div class="w-imglist-in"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/bdcdczs.jpg')}" alt="budongchandiaochadengjidaili" title="不动产调查登记代理资质证书" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">不动产调查登记代理资质证书</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/bdcdczs.jpg')}" alt="budongchandiaochadengjidaili" title="不动产调查登记代理资质证书" init="ok" style="width: auto; height: 336px; margin-left: -7.5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">不动产调查登记代理资质证书</a> 
              </div></li> 
              <li class="w-imglist-item"> 
              <div class="w-imglist-in"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/tdgh.jpg')}" alt="dengjidaili" title="土地调查登记代理机构注册证书" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">土地规划机构等级证书</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/tdgh.jpg')}" alt="dengjidaili" title="土地调查登记代理机构注册证书" init="ok" style="width: auto; height: 336px; margin-left: -7.5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">土地规划机构等级证书</a> 
              </div></li>
             <li class="w-imglist-item"> 
              <div class="w-imglist-in"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/lyzz.jpg')}" alt="linyezizhizhengshu" title="林业调查规划设计资质证书" init="ok" style="width: auto; height: 240px; margin-left: -5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">林业调查规划设计资质证书</a> 
              </div> 
              <div class="w-imglist-bigimg" style="z-index: 78"> 
               <a target="_self" class="w-imglist-img"> <img class="lazyload CutFill" src="{resource_url('img/tdkcImg/lyzz.jpg')}" alt="linyezizhizhengshu" title="林业调查规划设计资质证书" init="ok" style="width: auto; height: 336px; margin-left: -7.5px;" /> </a> 
               <div class="w-imglist-title-bg"></div> 
               <a class="w-imglist-title">林业调查规划设计资质证书</a> 
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
	
