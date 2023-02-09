{include file="common/header.tpl"}
  <link href="{resource_url('css/tdkcCss/view.css')}" rel="stylesheet" type="text/css" /> 
  <link href="{resource_url('css/tdkcCss/460891_Pc_zh-CN.css')}" rel="stylesheet" />
  <script type='text/javascript' id='jssor-all' src="{resource_url('js/tdkcJs/jssor.slider-22.2.16-all.min.js')}" ></script>
  <script type='text/javascript' id='slideshown' src="{resource_url('js/tdkcJs/slideshow.js')}" ></script>
  <input type="hidden" id="pageinfo" value="460891" data-type="1" data-device="Pc" data-entityid="460891" /> 
  <input id="txtDeviceSwitchEnabled" value="show" type="hidden" /> 

  <input type="hidden" id="secUrl" />
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
      <div class="smvWrapper" style="min-width:1000px;  position: relative; background-color: transparent; background-image: none; background-repeat: repeat-y; background:-moz-linear-gradient(top, none, none);background:-webkit-gradient(linear, left top, left bottom, from(none), to(none));background:-o-linear-gradient(top, none, none);background:-ms-linear-gradient(top, none, none);background:linear-gradient(top, none, none);;background-position:50% 50%;background-size:auto;" bgscroll="none">
       <div class="smvContainer" id="smv_Main" cpid="460891" style="min-height:400px;width:1000px;height:1006px;  position: relative; ">
        <div id="smv_con_4_10" ctype="slideset" class="esmartMargin smartAbs " cpid="460891" cstyle="Style1" ccolor="Item0" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="y" daxis="Y" isdeletable="True" style="height: 220px; width: 1000px; left: 0px; top: 0px;z-index:0;">
         <div class="yibuFrameContent con_4_10  slideset_Style1  " style="overflow:visible;;"> 
          <!--w-slide--> 
          <div class="w-slide" id="slider_smv_con_4_10"> 
           <div class="w-slide-inner" data-u="slides"> 
            <div class="content-box" data-area="Area0"> 
             <div id="smc_Area0" cid="con_4_10" class="smAreaC slideset_AreaC"> 
              <div id="smv_con_11_33" ctype="text" class="esmartMargin smartAbs " cpid="460891" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_4_10" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 28px; width: 363px; left: 71px; top: 60px;z-index:1;">
               <div class="yibuFrameContent con_11_33  text_Style1  " style="overflow:hidden;;">
                <div id="txt_con_11_33" style="height: 100%;"> 
                 <div class="editableContent" id="txtc_con_11_33" style="height: 100%; word-wrap:break-word;"> 
                  <p><span style="color:#3498db"><span style="font-size:24px"><span style="font-family:&quot;Microsoft YaHei&quot;">领先的测绘设备与技术</span></span></span></p> 
                 </div> 
                </div> 
               </div>
              </div>
              <div id="smv_con_8_19" ctype="text" class="esmartMargin smartAbs " cpid="460891" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_4_10" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 24px; width: 292px; left: 71px; top: 101px;z-index:1;">
               <div class="yibuFrameContent con_8_19  text_Style1  " style="overflow:hidden;;">
                <div id="txt_con_8_19" style="height: 100%;"> 
                 <div class="editableContent" id="txtc_con_8_19" style="height: 100%; word-wrap:break-word;"> 
                  <p><span style="font-size:20px"><span style="color:#ffffff"><span style="font-family:&quot;Microsoft YaHei&quot;">26年测绘底蕴，不断吸收新技术</span></span></span></p> 
                 </div> 
                </div> 
               </div>
              </div>
              <div id="smv_con_9_19" ctype="text" class="esmartMargin smartAbs " cpid="460891" cstyle="Style1" ccolor="Item0" areaid="Area0" iscontainer="False" pvid="con_4_10" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 29px; width: 351px; left: 73px; top: -40px;z-index:1;">
               <div class="yibuFrameContent con_9_19  text_Style1  " style="overflow:hidden;;">
                <div id="txt_con_9_19" style="height: 100%;"> 
                 <div class="editableContent" id="txtc_con_9_19" style="height: 100%; word-wrap:break-word;"> 
                  <p><span style="font-size:26px"><span style="color:#0099ff"><span style="font-family:Microsoft YaHei">领先的云计算网站建设服务商</span></span></span></p> 
                 </div> 
                </div> 
               </div>
              </div> 
             </div> 
             <div class="content-box-inner" style="background-image:url({resource_url('img/tdkcImg/-973.png')});background-position:50% 50%;background-repeat:no-repeat;background-size: cover;background-color:"></div> 
            </div> 
           </div> 
           <!-- Bullet Navigator --> 
           <div data-u="navigator" class="w-slide-btn-box " data-autocenter="1"> 
            <!-- bullet navigator item prototype --> 
            <div class="w-slide-btn" data-u="prototype"></div> 
           </div> 
           <!-- 1Arrow Navigator --> 
           <span data-u="arrowleft" class="w-slide-arrowl  slideArrow  f-hide  " data-autocenter="2" id="left_con_4_10"> <i class="w-itemicon mw-iconfont">넳</i> </span> 
           <span data-u="arrowright" class="w-slide-arrowr slideArrow  f-hide " data-autocenter="2" id="right_con_4_10"> <i class="w-itemicon mw-iconfont">넲</i> </span> 
          </div> 
          <!--/w-slide--> 
          <script type="text/javascript">
    con_4_10_page = 1;
    con_4_10_sliderset3_init = function () {
        var jssor_1_options_con_4_10 = {
            $AutoPlay: "False"=="True"?false:"on" == "on",//自动播放
            $PlayOrientation: 1,//2为向上滑，1为向左滑
            $Loop: 1,//循环
            $Idle: parseInt("1500"),//切换间隔
            $SlideDuration: "800",//延时
            $SlideEasing: $Jease$.$OutQuint,
            
            $CaptionSliderOptions: {
                $Class: $JssorCaptionSlideo$,
                $Transitions: GetSlideAnimation("1", "800"),
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
        var slide = new $JssorSlider$("slider_smv_con_4_10", jssor_1_options_con_4_10);
        $('#smv_con_4_10').data('jssor_slide', slide);

        //resize游览器的时候触发自动缩放幻灯秀
        $(window).bind("resize", function (e) {
            if (e.target == this) {
                var $this = $('#slider_smv_con_4_10');
                var ww = $(window).width();
                var width = $this.parent().width();

                if (ww > width) {
                    var left = parseInt((ww - width) * 10 / 2) / 10;
                    $this.css({ 'left': -left });
                } else {
                    $this.css({ 'left': 0 });
                }
                slide.$ScaleWidth(ww);
            }
        });

        //幻灯栏目自动或手动切换时触发的事件
        slide.$On($JssorSlider$.$EVT_PARK, function (slideIndex, fromIndex) {
            var $slideWrapper = $("#slider_smv_con_4_10 .w-slide-inner:last");
            var $fromSlide = $slideWrapper.find(".content-box:eq(" + fromIndex + ")");
            var $curSlide = $slideWrapper.find(".content-box:eq(" + slideIndex + ")");
            var $nextSlide = $slideWrapper.find(".content-box:eq(" + (slideIndex+1) + ")");
            $("#smv_con_4_10").attr("selectArea", $curSlide.attr("data-area"));
            $fromSlide.find(".animated").smanimate("stop");
            $curSlide.find(".animated").smanimate("stop");
            $nextSlide.find(".animated").smanimate("stop");
            $("#switch_con_4_10 .page").html(slideIndex + 1);
            $curSlide.find(".animated").smanimate("replay");
            return false;
        });
        //切换栏点击事件
        $("#switch_con_4_10 .left").unbind("click").click(function () {
            if(con_4_10_page==1){
                con_4_10_page =1;
            } else {
                con_4_10_page = con_4_10_page - 1;
            }
            $("#switch_con_4_10 .page").html(con_4_10_page);
            slide.$Prev();
            return false;
        });
        $("#switch_con_4_10 .right").unbind("click").click(function () {
            if(con_4_10_page==1){
                con_4_10_page = 1;
        } else {
        con_4_10_page = con_4_10_page + 1;
    }
    $("#switch_con_4_10 .page").html(con_4_10_page);
    slide.$Next();
    return false;
    });
    };


    $(function () {
        //获取幻灯显示动画类型
        var $this = $('#slider_smv_con_4_10');
        var dh = $(document).height();
        var wh = $(window).height();
        var ww = $(window).width();
        var width = 1000;
        //区分页头、页尾、内容区宽度
        if ($this.parents(".header").length > 0 ) {
            width = $this.parents(".header").width();
        } else if ($this.parents(".footer").length > 0 ){
            width = $this.parents(".footer").width();
        } else {
            width = $this.parents(".smvContainer").width();
        }

        if (ww > width) {
            var left = parseInt((ww - width) * 10 / 2) / 10;
            $this.css({ 'left': -left, 'width': ww });
        } else {
            $this.css({ 'left': 0, 'width': ww });
        }

        //解决手机端预览PC端幻灯秀时不通栏问题
        if (VisitFromMobile()) {
            $this.css("min-width", width);
            setTimeout(function () {
                var boxleft = (width - 330) / 2;
                $this.find(".w-slide-btn-box").css("left", boxleft + "px");
            }, 300);
        }
        $this.children().not(".slideArrow").css({ "width": $this.width() });

        con_4_10_sliderset3_init();

        var areaId = $("#smv_con_4_10").attr("tareaid");
        if(areaId==""){
            var mainWidth = $("#smv_Main").width();
            $("#smv_con_4_10 .slideset_AreaC").css({ "width":mainWidth+"px","position":"relative","margin":"0 auto" });
        }else{
            var controlWidth = $("#smv_con_4_10").width();
            $("#smv_con_4_10 .slideset_AreaC").css({ "width":controlWidth+"px","position":"relative","margin":"0 auto" });
        }
        $("#smv_con_4_10").attr("selectArea", "Area0");

        var arrowHeight = $('#slider_smv_con_4_10 .w-slide-arrowl').eq(-1).outerHeight();
        var arrowTop = (18 - arrowHeight) / 2;
        $('#slider_smv_con_4_10 .w-slide-arrowl').eq(-1).css('top', arrowTop);
        $('#slider_smv_con_4_10 .w-slide-arrowr').eq(-1).css('top', arrowTop);
    });
</script> 
         </div>
        </div>
        <div id="smv_con_15_44" ctype="tab" class="esmartMargin smartAbs " cpid="460891" cstyle="Style2" ccolor="Item0" areaid="" iscontainer="True" pvid="" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 592px; width: 998px; left: 1px; top: 290px;z-index:0;">
         <div class="yibuFrameContent con_15_44  tab_Style2  " style="overflow:visible;;"> 
          <div class="w-label" id="tab_con_15_44"> 
           <ul class="w-label-tips"> 
            <li class="w-label-tips-line w-label-tips-line-left"><span></span></li> 
            <li class="w-label-tips-line current"><span></span></li> 
            <li class="w-label-tips-item current" data-area="tabArea0"> <a href="" target="_blank">公司简介</a> <span class="mask" style=""></span> </li> 
            <li class="w-label-tips-line current"><span></span></li> 
            <li class="w-label-tips-line w-label-tips-line-right"><span></span></li> 
           </ul> 
           <ul class="w-label-content"> 
            <li class="w-label-content-item current" data-area="tabArea0"> 
             <div class="smAreaC" id="smc_tabArea0" cid="con_15_44" style="height: 529px;"> 
              <div id="smv_con_19_48" ctype="text" smanim="{ &quot;delay&quot;:0.75,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Up&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="460891" cstyle="Style1" ccolor="Item4" areaid="tabArea0" iscontainer="False" pvid="con_15_44" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 92px; width: 186px; left: 56px; top: 25px;z-index:4;">
               <div class="yibuFrameContent con_19_48  text_Style1  " style="overflow:hidden;;">
                <div id="txt_con_19_48" style="height: 100%;"> 
                 <div class="editableContent" id="txtc_con_19_48" style="height: 100%; word-wrap:break-word;"> 
                  <p style="text-align:center"><span style="color:#3498db"><span style="font-size:48px"><strong>1993</strong></span></span></p> 
                  <p style="text-align:center"><span style="color:#555555"><span style="font-size:14px">正式成立</span></span></p> 
                 </div> 
                </div> 
               </div>
              </div>
              <div id="smv_con_20_32" ctype="text" smanim="{ &quot;delay&quot;:0.75,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Up&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="460891" cstyle="Style1" ccolor="Item4" areaid="tabArea0" iscontainer="False" pvid="con_15_44" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 92px; width: 186px; left: 291px; top: 25px;z-index:4;">
               <div class="yibuFrameContent con_20_32  text_Style1  " style="overflow:hidden;;">
                <div id="txt_con_20_32" style="height: 100%;"> 
                 <div class="editableContent" id="txtc_con_20_32" style="height: 100%; word-wrap:break-word;"> 
                  <p style="text-align:center"><span style="color:#3498db"><span style="font-size:48px"><strong>70+</strong></span></span></p> 
                  <p style="text-align:center"><span style="color:#555555"><span style="font-size:14px">技术人员</span></span></p> 
                 </div> 
                </div> 
               </div>
              </div>
              <div id="smv_con_21_3" ctype="text" smanim="{ &quot;delay&quot;:0.75,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Up&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="460891" cstyle="Style1" ccolor="Item4" areaid="tabArea0" iscontainer="False" pvid="con_15_44" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 92px; width: 186px; left: 524px; top: 25px;z-index:4;">
               <div class="yibuFrameContent con_21_3  text_Style1  " style="overflow:hidden;;">
                <div id="txt_con_21_3" style="height: 100%;"> 
                 <div class="editableContent" id="txtc_con_21_3" style="height: 100%; word-wrap:break-word;"> 
                  <p style="text-align:center"><span style="color:#3498db"><span style="font-size:48px"><strong>60+</strong></span></span></p> 
                  <p style="text-align:center"><span style="color:#555555"><span style="font-size:14px">精尖设备</span></span></p> 
                 </div> 
                </div> 
               </div>
              </div>
              <div id="smv_con_22_18" ctype="text" smanim="{ &quot;delay&quot;:0.75,&quot;duration&quot;:0.75,&quot;direction&quot;:&quot;Up&quot;,&quot;animationName&quot;:&quot;slideIn&quot;,&quot;infinite&quot;:&quot;1&quot; }" class="esmartMargin smartAbs animated" cpid="460891" cstyle="Style1" ccolor="Item4" areaid="tabArea0" iscontainer="False" pvid="con_15_44" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 92px; width: 186px; left: 754px; top: 25px;z-index:4;">
               <div class="yibuFrameContent con_22_18  text_Style1  " style="overflow:hidden;;">
                <div id="txt_con_22_18" style="height: 100%;"> 
                 <div class="editableContent" id="txtc_con_22_18" style="height: 100%; word-wrap:break-word;"> 
                  <p style="text-align:center"><span style="font-size:48px"><span style="color:#3498db"><strong>10+</strong></span></span></p> 
                  <p style="text-align:center"><span style="color:#555555"><span style="font-size:14px">合作高校、单位</span></span></p> 
                 </div> 
                </div> 
               </div>
              </div>
              <div id="smv_con_29_43" ctype="text" class="esmartMargin smartAbs " cpid="460891" cstyle="Style1" ccolor="Item3" areaid="tabArea0" iscontainer="False" pvid="con_15_44" tareaid="" re-direction="all" daxis="All" isdeletable="True" style="height: 416px; width: 880px; left: 57px; top: 165px;z-index:6;">
               <div class="yibuFrameContent con_29_43  text_Style1  " style="overflow:hidden;;">
                <div id="txt_con_29_43" style="height: 100%;"> 
                 <div class="editableContent" id="txtc_con_29_43" style="height: 100%; word-wrap:break-word;"> 
                  <p><span style="font-size:14px"><span style="line-height:1.75">&nbsp; &nbsp; &nbsp; &nbsp;慈溪市土地勘测规划设计院有限公司是慈溪市国土资源局所属的原国有企业（慈溪市土地勘测规划设计院）改制后的股份制民营企业。公司创建于1993年。是以测量为主业，集土地、房屋测量，土地规划设计，地理信息服务，矿业权核查，房地产登记代理于一体，经营多元、结构合理的现代企业。现有员工70余人，其中中高级职称的技术人员21名，初级职称的技术人员36名，具有全国级、省级土地登记代理人20余名。公司置有天宝GPS（全球卫星定位系统）、拓普康免棱镜测距1200米的全站仪、惠普5100大型彩色绘图仪、数据交换服务器等设备60多台（套）。与江西省地矿测绘院、武汉大学资源与环境科学学院、丽水职业技术学院、省土地规划设计院等省内外多家单位进行了业务、技术交流与合作关系。通过了ISO9000及GB/T19001-2008标准的质量管理体系认证。持有国家测绘甲级资质、土地规划乙级资质、土地调查登记代理机构注册证书等。为宁波市房产测绘评审专家委员会成员，浙江省测绘与地理信息行业协会副会长单位。<br /> &nbsp; &nbsp; &nbsp; &nbsp;20年来公司在创新转型、开放合作中得到了壮大和发展。业务范围覆盖国土、规划、交通、水利、农业、城建等诸多行业，工作业绩遍及杭州、嘉兴、绍兴、温州、衢州、宁波等全省各地。在慈溪市行政审批服务中心及各行政分中心、慈东滨海区管委会行政服务中心设立窗口。承担过的主要工程项目有国土资源部及省国土资源厅的土地整理、农村综合整治、开发造地等示范工程项目的测量及可行性报告，慈溪市多个镇的土地利用总体规划编制、城镇数字地籍测量，规划指标复核，温州市平阳县30个乡镇的总体规划修编，宁波军用机场规划新址、杭甬高铁慈溪段、镇海长石至慈溪邱王一级公路改造、景观大道、文化商务区等拆迁工程的测量，中国保利，上海复地，乐城，浙江绿城、梵石，慈溪恒元等房地产公司在慈开发小区的房产测量、日照分析、规划指标复核、竣工房地产测量，市国土资源局地籍数据库建设，慈溪市横河自来水厂管道信息系统等。创建的慈溪市数字城市公共服务平台——慈溪之窗（www.cxmap.cn）已经上线运行。<br /> &nbsp; &nbsp; &nbsp; &nbsp;今天，公司正紧随着“打造中国经济升级版”的时代步伐，以国家大力推动的数字城市、“天地图”和地理信息三大工程为契机，深入实施“人才强测”、“科技强院”战略，以“诚信立业、求精创新、真情服务、合作共赢”为宗旨，视质量信誉为生命，随时为社会、为企业、为群众、为您提供优质、高效、专业、称心的服务。为服务社会经济发展作出积极的贡献！</span></span></p> 
                 </div> 
                </div> 
               </div>
              </div> 
             </div> </li> 
           </ul> 
          </div> 
          <script type="text/javascript">
    $(function () {
        var event = "click";
        $("#tab_con_15_44 > .w-label-tips >.w-label-tips-item").on(event, function () {
            $(this).siblings().removeClass("current");
            $(this).addClass("current");
            $(this).prev(".w-label-tips-line").addClass("current");
            $(this).next(".w-label-tips-line").addClass("current");
            var $content = $("#tab_con_15_44 >.w-label-content > .w-label-content-item[data-area='"+$(this).attr("data-area")+"']");
            $content.addClass("current").siblings().removeClass("current");
            var tabContentH = $content.children().outerHeight() + $("#tab_con_15_44 > .w-label-tips").outerHeight() + 1;
            $('#smv_con_15_44').smrecompute("recomputeTo", tabContentH);
            if (!$content.children().hasClass('expandFlag')) {
                $content.find('.smartRecpt').smrecompute();
            }
            $("#smv_con_15_44").attr("selectArea",$content.attr("data-area"));
            $content.find("img").cutFillAuto();
        });
        $("#smv_con_15_44").attr("selectArea","tabArea0");
    });
</script> 
         </div>
        </div>
       </div>
      </div>
      <input type="hidden" name="__RequestVerificationToken" id="token__RequestVerificationToken" value="r2r4zyo3iKWApE_sT6yR8_XBHA8Jlxxa7PtRMZWBapabKoU_-OdfZT7sLPyUMCOmbbIFQ54BSxsGkpwGUb5IBurBrJfMXjct8a9BL-Q35nDxT8FM2kXBMb2hsITOJti-0" /> 
     </div> 
    </div> 
   </div> 
 
  
  {include file="common/footer.tpl"}
	
