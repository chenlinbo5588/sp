var regMobile = /^(\+?86)?1[0-9][0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/;
//地区缓存
var districtCache = [];

function drop_confirm(msg, url){
    if(confirm(msg)){
        window.location = url;
    }
}

function go(url){
    window.location = url;
}

/* 格式化金额 */
function price_format(price){
    if(typeof(PRICE_FORMAT) == 'undefined'){
        PRICE_FORMAT = '&yen;%s';
    }
    price = number_format(price, 2);

    return PRICE_FORMAT.replace('%s', price);
}

function number_format(num, ext){
    if(ext < 0){
        return num;
    }
    num = Number(num);
    if(isNaN(num)){
        num = 0;
    }
    var _str = num.toString();
    var _arr = _str.split('.');
    var _int = _arr[0];
    var _flt = _arr[1];
    if(_str.indexOf('.') == -1){
        /* 找不到小数点，则添加 */
        if(ext == 0){
            return _str;
        }
        var _tmp = '';
        for(var i = 0; i < ext; i++){
            _tmp += '0';
        }
        _str = _str + '.' + _tmp;
    }else{
        if(_flt.length == ext){
            return _str;
        }
        /* 找得到小数点，则截取 */
        if(_flt.length > ext){
            _str = _str.substr(0, _str.length - (_flt.length - ext));
            if(ext == 0){
                _str = _int;
            }
        }else{
            for(var i = 0; i < ext - _flt.length; i++){
                _str += '0';
            }
        }
    }

    return _str;
}

/* 转化JS跳转中的 ＆ */
function transform_char(str)
{
    if(str.indexOf('&'))
    {
        str = str.replace(/&/g, "%26");
    }
    return str;
}

function trim(str) {
    return (str + '').replace(/(\s+)$/g, '').replace(/^\s+/g, '');
}


function FormErrorHtml(msg){
	return '<div class="form_error">' + msg + '</div>';
}

function refreshFormHash(data){
	if(typeof(data.formhash) != "undefined"){
		formhash = data.formhash;
		$("input[name=formhash]").val(formhash);
	}
}


function setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
	if(cookieValue == '' || seconds < 0) {
		cookieValue = '';
		seconds = -2592000;
	}
	if(seconds) {
		var expires = new Date();
		expires.setTime(expires.getTime() + seconds * 1000);
	}
	domain = !domain ? cookiedomain : domain;
	path = !path ? cookiepath : path;
	document.cookie = escape(cookiepre + cookieName) + '=' + escape(cookieValue)
		+ (expires ? '; expires=' + expires.toGMTString() : '')
		+ (path ? '; path=' + path : '/')
		+ (domain ? '; domain=' + domain : '')
		+ (secure ? '; secure' : '');
}

function getcookie(name, nounescape) {
	name = cookiepre + name;
	var cookie_start = document.cookie.indexOf(name);
	var cookie_end = document.cookie.indexOf(";", cookie_start);
	if(cookie_start == -1) {
		return '';
	} else {
		var v = document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length));
		return !nounescape ? unescape(v) : v;
	}
}

function set_pagesize(obj){
	setcookie('page_size',obj.options[obj.selectedIndex].value);
}
/**
 * 分页
 * @param page
 * @returns
 */
function search_page(page,formid){
	var form = $(formid);
    $("input[name=page]",form).val(page);
    form.submit();
}


/* 显示Ajax表单 */
function ajax_form(id, title, url, width, model)
{
    if (!width)	width = 480;
    if (!model) model = 1;
    var d = DialogManager.create(id);
    d.setTitle(title);
    d.setContents('ajax', url);
    d.setWidth(width);
    d.show('center',model);
    return d;
}

//显示一个内容为自定义HTML内容的消息
function html_form(id, title, _html, width, model) {
    if (!width)	width = 480;
    if (!model) model = 0;
    var d = DialogManager.create(id);
    d.setTitle(title);
    d.setContents(_html);
    d.setWidth(width);
    d.show('center',model);
    return d;
}


//加载谈层
$.loadingbar = function(settings) {
    var defaults = {
        autoHide:true,
        replaceText:"正在刷新,请稍后...",
        container: 'body',
        showClose: false,
        wrapperClass:'',
        text:'正在操作，请稍候…',
        template:''
    };
    var xhr;
    var cfg = $.extend({},defaults,settings);
    var postext;

    if(cfg.container==='body'){
        postext = 'fixed';
    }else{
        postext = 'absolute';
        $(cfg.container).css({position:'relative'});
    }


    var spin_wrap,content_tpl;

    if(cfg.template && $(cfg.template).length){
    	content_tpl = $(cfg.template).html();
    }else{
        content_tpl = '<div class="loading_box '+cfg.wrapperClass+'"><div class="lightbox-content">\
                          <span class="loading_close">×</span>\
                          <i class="loading_icon">&nbsp;</i><span class="loading_text">'+cfg.text+'</span>\
                          </div></div>';
    }

    spin_wrap  = $('<div class="lightbox" style="display:none;position:'+postext+'">\
        <table cellspacing="0" class="ct"><tbody><tr><td class="ct_content"></td></tr></tbody></table>\
        </div>');

    spin_wrap.find(".ct_content").html(content_tpl);

    if(!cfg.showClose){
        spin_wrap.find(".loading_close").hide();
    }

    if(0 == $(cfg.container).find("> .lightbox").length){
        $(cfg.container).append(spin_wrap);
    }else{
        spin_wrap = $("> .lightbox",$(cfg.container));
    }

    $(document).ajaxSend(function(event, jqxhr, settings) {
        var surl = settings.url;
        var state = false;
        if(typeof cfg.urls != 'undefined'){
            $.each(cfg.urls,function(i,item){
                if($.type(item) === 'regexp'){
                    if(item.exec(surl)) {
                        state = true;
                        return false;
                    }
                }else if($.type(item) === 'string'){
                    if(item === surl) {
                        state = true;
                        return false;
                    }
                }else{
                    throw new Error('[urls] type error,string or regexp required');
                }
            });
        } else {
            spin_wrap.show();
        }

        if(state){
            spin_wrap.show();
        }

        if(cfg.showClose){
            $('.loading_close').on('click',function(e){
                jqxhr.abort();
                $.active = 0;
                spin_wrap.hide();
                $(this).off('click');
            });
        }
    });

    $(document).ajaxStop(function(e) {
        if(cfg.autoHide){
            spin_wrap.hide();
        }else{
            spin_wrap.find(".loading_text").html(cfg.replaceText);
        }
    });

    return spin_wrap;
};

/** 悬浮条 */
$.fn.fixedBar = function(settings){
    var defaults = {
        inverse:false,
        fixed:false,  //是否始终固定位置
        css:"position:fixed;top:0;",
        endAt:0,
        offsetTop:0,
        createShadow:'fixBarShadow'
    };

    var callback,opts;

    if(this.length==0) return;

    if( Object.prototype.toString.call(settings) == "[object Function]" ){
        callback = settings;
    }

    if(settings && settings.inverse){
        defaults.css = 'position:fixed;bottom:0;';
    }

    opts = $.extend(defaults, settings);

    if (window.ActiveXObject) {
        window.isIE = window[window.XMLHttpRequest ? 'isIE7' : 'isIE6'] = true;
    }

    if (window.isIE6) try {document.execCommand("BackgroundImageCache", false, true);} catch(e){}

    var ele = $(this).length > 1 ? $(this).eq(0) : $(this);
    var shadow;

    function init(){
        var eleOffsetTop = ele.offset().top;
        var elePositionTop = ele.position().top;
        var endPos;

        if(opts.endAt){
            if(typeof opts.endAt === 'number'){
                endPos = opts.endAt;
            }else{
                endPos = $(opts.endAt).offset().top + $(opts.endAt).height();
            }
        }

        if(opts.createShadow){
            if(typeof opts.createShadow === 'string'){
                shadow = $(opts.createShadow).length ? $(opts.createShadow) : $('<div class="'+opts.createShadow+'" />').css({
                    display:'none',
                    height:ele.outerHeight(true)+'px'
                });
            }
            ele.before(shadow);
        }

        if(opts.fixed){
            eleOffsetTop = -1;
            if(!ele.hasClass("fixedBar")) ele.addClass("fixedBar").attr("style",opts.css);
            if(window.isIE6) ele.css({"position":"absolute"});
        }

        $(window).bind("scroll.fixedBar",function(e){
            if(ele.is(':hidden')){
                return;
            }

            var that = $(this);
            var scrollTop = that.scrollTop() + opts.offsetTop;

            var changeBar = function(){
                if(!ele.hasClass("fixedBar")){
                    opts.createShadow && shadow.show();
                    ele.addClass("fixedBar").attr("style",opts.css);
                    if(opts.offsetTop!==0){
                       ele.css('top',opts.offsetTop);
                    }
                }
                // todo ie6
                if(window.isIE6) ele.css({"top":scrollTop - eleOffsetTop + elePositionTop + "px","position":"absolute"});
            };

            var resetBar = function(){
                opts.createShadow && shadow.hide();
                ele.removeClass("fixedBar").removeAttr("style");
            };

            if(ele.data('disabled') !== true){
                if(!opts.inverse){
                    if(scrollTop > eleOffsetTop){
                        changeBar();
                    }else{
                        resetBar();
                    }
                }else{
                    var winHeight = $(window).innerHeight();
                    if(scrollTop + winHeight - ele.outerHeight() < eleOffsetTop){
                        changeBar();
                    }else{
                        resetBar();
                    }
                }
            }

            if(callback) callback.call(ele,scrollTop);

            if(opts.endAt){
                if(scrollTop >= endPos){
                    shadow.hide();
                    ele.removeClass("fixedBar").removeAttr("style").data('disabled',true);
                }else{
                    ele.removeData('disabled');
                }
            }
        });

        if(opts.inverse){
            $(function(){
                $(window).trigger('scroll.fixedBar');
            })
        }

        if(window.isIE){
            $(document).on('click.fixedBar',function(){
                if(ele.hasClass("fixedBar")){
                    $(window).trigger('scroll.fixedBar');
                }
            });
        }

    }

    init();

    var api = {
        reset:function(){
            ele.removeClass("fixedBar").removeAttr("style");
            $(window).unbind("scroll.fixedBar");
            opts.createShadow && shadow.remove();
            return this;
        },
        init:function(){
            init();
            return this;
        }
    };

    ele.data('fixedBar',api);

    return this;
};
/**
 * 绑定地区下拉联动
 * @param isBind
 * @returns
 */
function districtSelect(isBind){
	if(isBind){
		$(".cityGroupWrap .citySelect").bind("change.districtSelect",function(e){
			var name = $(this).attr("name"),
				index = parseInt(name.replace('d','')),
				wrap = $(this).closest(".cityGroupWrap"),
				targetSel,
				i,
				upid = $(this).val();
			
			if(index < 4){
				targetSel = $("select[name=d" + (index + 1) + "]",wrap);
				
				for(i = index; i < 4; i++){
					$("select[name=d" + (i + 1) + "]",wrap).html('').append('<option value="">请选择</option>');
				}
				
				if(upid != ""){
					if(districtCache[upid]){
						showCity(districtCache[upid]);
						return ;
					}
					
					$.getJSON(cityUrl + "/upid/" + upid,function(resp){
						showCity(resp.data);
						districtCache[upid] = resp.data;
					});
				}
			}
			
			function showCity(cityList){
				for(i = 0; i < cityList.length; i++){
					targetSel.append('<option value="' + cityList[i].id + '">' + cityList[i].name + '</option>');
				}
				
				targetSel.focus();
			}
		});
	}else{
		$(".cityGroupWrap .citySelect").unbind("change.districtSelect");
	}
	
}



/**
 * 浮动DIV定时显示提示信息,如操作成功, 失败等
 * @param string tips (提示的内容)
 * @param int height 显示的信息距离浏览器顶部的高度
 * @param int time 显示的时间(按秒算), time > 0
 * @sample <a href="javascript:void(0);" onclick="showTips( '操作成功', 100, 3 );">点击</a>
 * @sample 上面代码表示点击后显示操作成功3秒钟, 距离顶部100px
 * @copyright ZhouHr 2010-08-27
 */
function showTips( tips, height, time ){
    var windowWidth = document.documentElement.clientWidth;
    var tipsDiv = '<div class="tipsClass">' + tips + '</div>';

    $( 'body' ).append( tipsDiv );
    $( 'div.tipsClass' ).css({
        'top' : 200 + 'px',
        'left' : ( windowWidth / 2 ) - ( tips.length * 13 / 2 ) + 'px',
        'position' : 'fixed',
        'padding' : '20px 50px',
        'background': '#EAF2FB',
        'font-size' : 14 + 'px',
        'margin' : '0 auto',
        'text-align': 'center',
        'width' : 'auto',
        'color' : '#333',
        'border' : 'solid 1px #A8CAED',
        'opacity' : '0.90',
        'z-index' : '9999'
    }).show();
    setTimeout( function(){$( 'div.tipsClass' ).fadeOut().remove();}, ( time * 1000 ) );
}

/*
 * 弹出窗口
 */
(function($) {
    $.fn.nc_show_dialog = function(options) {

        var that = $(this);
        var settings = $.extend({}, {width: 480, title: ''}, options);

        var init_dialog = function(title) {
            var _div = that;
            that.addClass("dialog_wrapper");
            that.wrapInner(function(){
                return '<div class="dialog_content">';
            });
            that.wrapInner(function(){
                return '<div class="dialog_body" style="position: relative;">';
            });
            that.find('.dialog_body').prepend('<h3 class="dialog_head" style="cursor: move;"><span class="dialog_title"><span class="dialog_title_icon">'+settings.title+'</span></span><span class="dialog_close_button">X</span></h3>');
            that.append('<div style="clear:both;"></div>');

            $(".dialog_close_button").click(function(){
                _div.hide();
            });

            that.draggable();
        };

        if(!$(this).hasClass("dialog_wrapper")) {
            init_dialog(settings.title);
        }
        settings.left = $(window).scrollLeft() + ($(window).width() - settings.width) / 2;
        settings.top  = ($(window).height() - $(this).height()) / 2;
        $(this).attr("style","display:none; z-index: 1100; position: fixed; width: "+settings.width+"px; left: "+settings.left+"px; top: "+settings.top+"px;");
        $(this).show();

    };
})(jQuery);



(function($) {
    $.fn.nc_region = function(options) {
        var $region = $(this);
        var settings = $.extend({}, {area_id: 0, region_span_class: "_region_value"}, options);

        return this.each(function() {
            var $inputArea = $(this);
            if($inputArea.val() === '') {
                initArea($inputArea);
            } else {
                var $region_span = $('<span class="' + settings.region_span_class + '">' + $inputArea.val() + '</span>');
                var $region_btn = $('<input type="button" value="编辑" />');
                $inputArea.after($region_span);
                $region_span.after($region_btn);
                $region_btn.on("click", function() {
                    $region_span.hide();
                    $region_btn.hide();
                    initArea($inputArea);
                });
            }
        });

        function initArea($inputArea) {
            settings.$area = $('<select></select>');
            $inputArea.after(settings.$area);
            loadAreaArray(function() {
                loadArea(settings.$area, settings.area_id);
            });
        }

        function loadArea($area, area_id){
            if($area && nc_a[area_id].length > 0){
                var areas = [];
                areas = nc_a[area_id];
                $area.append("<option>-请选择-</option>");
                for (i = 0; i <areas.length; i++){
                    $area.append("<option value='" + areas[i][0] + "'>" + areas[i][1] + "</option>");
                }
            }

            $area.on('change', function() {
                $(this).nextAll("select").remove();

                var region_value = '';
                $region.nextAll("select").each(function() {
                    region_value += $(this).find("option:selected").text() + ' ';
                });
                $region.val(region_value);

                var area_id = $(this).val();
                if(area_id > 0) {
                    if(nc_a[area_id] && nc_a[area_id].length > 0) {
                        var $newArea = $('<select></select>');
                        $(this).after($newArea);
                        loadArea($newArea, area_id);
                    }
                }
            });
        }

        function loadAreaArray(callback) {
            if(typeof nc_a === 'undefined') {
                //取JS目录的地址
                var area_scripts_src = '';
                area_scripts_src = $("script[src*='jquery.js']").attr("src");
                area_scripts_src = area_scripts_src.replace('jquery.js', 'area_array.js');
                $.ajax({
                    url: area_scripts_src,
                    async: false,
                    dataType: "script"
                }).done(function(){
                    callback();
                });
            } else {
                callback();
            }
        }
    };
})(jQuery);

$(function(){
	
	$("input[name=jumpPage]").keydown(function(event){
　　　　 // 注意此处不要用keypress方法，否则不能禁用　Ctrl+V 与　Ctrl+V,具体原因请自行查找keyPress与keyDown区分，十分重要，请细查
        if (/MSIE/.test(navigator.userAgent)) {  // 判断浏览器
            if ( ((event.keyCode > 47) && (event.keyCode < 58)) || ((event.keyCode >= 96) && (event.keyCode <= 105)) || (event.keyCode == 8) ) { 　// 判断键值  
                return true;  
            } else { 
                return false;  
            }
        } else {  
            if ( ((event.which > 47) && (event.which < 58)) || ((event.keyCode >= 96) && (event.keyCode <= 105)) || (event.which == 8) || (event.keyCode == 17) ) {  
                    return true;  
            } else {  
                    return false;  
            }  
        }}).focus(function() {
        
        
        this.style.imeMode='disabled';   // 禁用输入法,禁止输入中文字符
    });
    
    $("input.jumpBtn").bind("click",function(e){
    	var btn = $(e.target);
    	var pagerDiv = btn.closest('.pagination');
    	var formid = pagerDiv.attr('data-formid');
    	
    	$(formid).find("input[name=page]").val(btn.closest("strong").find("input[name=jumpPage]").val());
    	
    	$(formid).submit();
    });
});


