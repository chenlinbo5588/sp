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
       <div class="smvContainer" id="smv_Main" cpid="461385" style="min-height:350px;width:1000px; position: relative; ">
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
   </div>
   <style>
   		.box{
			display:flex; 
			flex-direction: column;
		}
		.boxh{
			display:flex; 
		}
		.imgbox{
			width:35%;
			height:250px;
			padding:10px 10px;
		}
		.czjz{
			justify-content:center;
			align-items:center;
		}
		.jz{
			align-items:center;
		}

		.line{
			height:200px;
			border-left:1px solid #030000;
		}
		.duan{
			text-indent:15px;
		}
	
		
		.search{
			color:#424242;
			font-weight:bold;
			font-size:22px;
			margin-right:30px;
		}
		.searchbox{
			height:200px;
			margin-top:60px;
		
		}
		.quding{
			font-size:14px;
			background-color:#00479d;
			color:#e9ecf3;
			border:none;
			width:65px;
			height:30px;
			margin-left:30px;
		}
		.inputbox{
			width:270px;
			height:25px;
			font-size:14px;
			color:#000;
			margin-top:15px;
			margin-bottom:15px;
			padding-left:5px;
		}
		.lineh{
			width:100%;
			margin-top:50px;
			border:1px solid #e5e5e5;
			
		}
		.lineh2{
			width:60%;
			
			border:1px solid #e5e5e5;
		}
		
		.imgbox2{
			width:270px;
			height:180px;
		}
		
		
		
		.title{
			font-size:20px;
			font-weight:bold;
			height:60%;
			align-items:center;
			display:flex;
			line-height:35px;
		}
		.content{
			font-size:14px;
			color:#a3a3a3;
			width:80%;
			line-height:25px;
			white-space:nowrap;
			overflow:hidden;
			text-overflow:ellipsis;
			height:30%;
		}
		.time{
			color:#a3a3a3;
			font-size:12px;
			height:10%;
			align-items:flex-end;
			display:flex;
			justify-content:flex-end;
		}
		
		.morebox{
			width:180px;
			height:50px;
			border:1px solid #191616;
			margin-top:50px;
			border-radius:50px;
		}
		.dianji{
			cursor:pointer;
		}
		.count{
			font-size:14px;
			color:#a3a3a3;
			
		}
		
		
   </style>
   <script>
		
	   	function selectMore() {
	   			$("div[id=news]").each(function(){     
					$(this).css("display","flex");  
				});
				$("div[id=more]").css("display","none");  
				
	   	}
   </script>
   	<div class="box czjz" style="width:100%">
   	<form action="{base_url('index.php/news/index')}" id="formSearch" method="get" accept-charset="utf-8" class="boxh jz" style="width:60%;height:50px;">
	   	 <div class="boxh jz" >
			 	<div class="search">新闻搜索</div>
			 	<input class="inputbox" type="text" value="{$search}" name="search">
			 	<input class="quding"  type="submit" value="搜索"></input>
	   	 </div>
   	 </form>
   	 <div class="boxh jz" style="margin-left:30px;  width:60%;height:50px;" >
   	 		<div class="boxh jz" style=" width:100%; {if $newCount==""}display:none;{/if}">
	   	 		<div class="count" >当前新闻列表搜索关键字"</div>
	   	 		<div class="count" style="color:red">{$search}</div>   	 		
	   	 		<div class="count" style="">"的相关结果为"</div>   	 		
	   	 		<div class="count" style="color:red">{$newCount}</div> 
	   	 		<div class="count" style="{if $newCount ==0}display:none;{/if}">"条记录数。</div>  
	   	 		<div class="count" style="{if $newCount !=0}display:none;{/if}">"条记录数,已为您推荐其他新闻</div>   
   			</div>
   	 </div>
   	<div class="lineh2"></div>
   
   	<div class="box czjz dianji" style="width:60%;">
   	  	{foreach from=$info key=key item=item}
	   	 	<div class="box czjz display dianji" style="width:100%; margin-top:30px; {if $item['isNotDisplay']}display:none{/if} " id="news" onclick="window.open('{base_url('index.php/news/detail?id=')}{$item['id']}','_self')">
			   		<div class="boxh " style="width:100%;">
					 	<image src="{base_url($item['image_url'])}" class="imgbox"></image>
					 	<div class="box " style="padding-left:30px; width:60%;">
					 		<div class="title">{$item['article_title']}</div>
					 		<div class="duan content">{$item['digest']}</div>
					 		<div class="time">{$item['time']}</div>
					 	</div>
					</div>
					<div class="lineh"></div>
			</div> 
		{/foreach}	 	 
   	  	  	 	
   	</div>
   	<div class="box morebox czjz" style="cursor:pointer; {if $isNotDisplay}display:none{/if} " onclick="selectMore();" id="more">
   		<div class="" style="font-size:16px;color:#424242"  onselectstart="return false">查看更多</div>
   	</div>
   		
   
  
  {include file="common/footer.tpl"}
	
