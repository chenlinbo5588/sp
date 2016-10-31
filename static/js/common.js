var regMobile = /^(\+?86)?1[0-9][0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/,
	regEmail = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/,
	regPsw = /^[a-zA-Z0-9~!@#$%^&*()\\\|\\\\-_=+{}\[\];:"\'<,.>?\/]{6,12}$/;

//地区缓存
var districtCache = [];
var commonDialog;
var formLock = [];

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


$.fn.imageCode = function(setting){
	var defaultSetting = {
		'callbackFn' : function() {}	
	};
	
	setting = $.extend({},setting);
	
	var wrap = $(setting.wrapId);
	
	
	var isRequesting = false;
	var _refreshImg = function(){
		if(isRequesting == true){
			return ;
		}
		
		wrap.html("正在刷新....");
		isRequesting = true;
		
		$.ajax({
			type:'GET',
			url:setting.captchaUrl,
			data : { t: Math.random() },
			success: function( json){
				isRequesting = false;
				if(typeof(json.img) != "undefined"){
					wrap.html(json.img);
				}else{
					wrap.html("点击重新刷新");
				}
				
				if(setting.callbackFn){
					setting.callbackFn(json);
				}
			},
			error: function(XMLHttpRequest, textStatus, thrownError){
				isRequesting = false;
			}
		});
    };
	
    wrap.bind("click.imageCode",_refreshImg);
    
	//setTimeout(_refreshImg,500);
	
	this.refreshImg = _refreshImg;
	return this;
};

function showToast(icon,message){
	$.toast({
		position:'top-center',
	    text: message,
	    icon: icon,
	    loader:false
	});
}

/*
 * ajax
 */
function doAjaxPost(postdata,url,datatype,successFn,errorFn){
	$.ajax({
		type:"POST",
		url:url,
		dataType:datatype,
		data: postdata,
		success:successFn,
		error:errorFn
	});
}

/**
 * 确认对话框
 * @param ids
 * @param url
 * @param title
 * @param dataType
 * @param successFn
 * @param errorFn
 */
function ui_confirm(trigger,ids,url,title,dataType,successFn,errorFn){
	var lock = false;
	
	commonDialog = $("#showDlg" ).dialog({
		title: "提示",
		autoOpen: false,
		width: 280,
		position: trigger ? { my: "center", at: "center", of: trigger } : null,
		modal: true,
	      buttons: {
	        "确定": function(){
	        	/* 防止联系点击确定  */
	        	if(lock == true){
	        		return;
	        	}
	        	lock = true;
	        	
	        	commonDialog.html('<div class="loading_bg">操作进行中,请稍候...</div>');
	        	doAjaxPost({ "formhash" : formhash ,"id": ids }, url, dataType, function(json){
	        		lock = false;
	        		commonDialog.dialog( "close" );
	        		if(typeof(successFn) != 'undefined'){
	        			successFn(ids,json);
	        		}
	        	},function(XMLHttpRequest, textStatus, thrownError){
	        		lock = false;
	        		commonDialog.dialog( "close" );
	        		if(typeof(errorFn) != 'undefined'){
	        			errorFn(XMLHttpRequest, textStatus, thrownError);
	        		}
	        	});
	        },
	        "关闭": function() {
	        	commonDialog.dialog( "close" );
	        }
	   },
	   open:function(){
		  
	   }
	}).html('<span class="ui-icon ui-icon-alert fl"></span><strong>' + title + '</strong>').dialog("open");
	
}

function check_success(message){
	var reg = new RegExp(/成功/);
	return reg.test(message);
}


/**
 * 通用ajax更新
 */
function bindOpEvent(selector,customSuccessFn,customErrorFn){
	var successCallback = function(ids,json){
		if(check_success(json.message)){
			showToast('success',json.message);
			
			if(typeof(ids) != 'undefined'){
				for(var i = 0; i < ids.length; i++){
					$("#row" + ids[i]).remove();
				}
			}
		}else{
			showToast('error',json.message);
		}
	}
	
	var errorCallback = function(){
		showToast('error',"执行出错,服务器异常，请稍后再次尝试");
	}
	
	$(selector).bind("click",function(){
		var triggerObj = $(this);
		var ids = getIDS(triggerObj);
		var url = triggerObj.attr("data-url");
  		var title = triggerObj.attr('data-title');
  		
  		if(ids.length == 0){
  			showToast('error','请选勾选.');
  		}else{
  			ui_confirm(triggerObj,ids,url,'你确定要' + title + '吗？','json', customSuccessFn ? customSuccessFn : successCallback,customErrorFn ? customErrorFn : errorCallback );
  		}
  	});
	
}

function getIDS(obj){
	var ids = [];
	
	var checkboxName = obj.attr('data-checkbox');
	var dataId = obj.attr('data-id');
	
	if(typeof(checkboxName) != 'undefined'){
		$("input[name='" + checkboxName + "']:checked").each(function(){
			ids.push($(this).val());
		});
	}else if(typeof(dataId) != 'undefined'){
		ids.push(obj.attr('data-id'));
	}
	
	return ids;
}

/**
 * ajax 提交
 * @param classname
 */
function bindAjaxSubmit(classname){
	
	var lockFn = function(btn,name,lock){
		btn.attr('disabled',lock);
		formLock[name] = lock;
		
		if(lock == true){
			btn.addClass("disabled");
		}else{
			btn.removeClass("disabled");
		}
	}
	
	
	$(classname).submit(function(){
		var name=$(this).prop("name");
		var submitBtn = $("input[type=submit]",$(this));
		
		if(formLock[name]){
			return false;
		}
		
		lockFn(submitBtn,name,true);
		
		$.ajax({
			type:'POST',
			url: $(this).prop("action"),
			dataType:'json',
			data:$(this).serialize(),
			success:function(resp){
				lockFn(submitBtn,name,false);
				refreshFormHash(resp.data);
				
				if(!/成功/.test(resp.message)){
					
					showToast('error',resp.message);
					
					var errors = resp.data.errors;
					var first = null;
					
					$("label.errtip").hide();
					
					for(var f in errors){
						if(first == null){
							first = f;
						}
						$("#error_" + f).html(errors[f]).addClass("error").show();
					}
					
					if($("input[name=" + first + "]").size()){
						$("input[name=" + first + "]").focus();
					}else if($("select[name=" + first + "]").size()){
						$("select[name=" + first + "]").focus();
					}
					
					
				}else{
					showToast('success',resp.message);
					
					$("label.errtip").hide();
					if(typeof(resp.data.redirectUrl) != "undefined"){
						
						setTimeout(function(){
							location.href = resp.data.redirectUrl;
						},500);
					}
				}
			
			},
			error:function(xhr, textStatus, errorThrown){
				lockFn(submitBtn,name,false);
				
				alert("服务器发送错误,将重新刷新页面");
			}
		});
		return false;
	});
}



/**
 * 
 * @param sucFn 成功事件回调
 * @param errorFn 失败事件回调
 */
function bindDeleteEvent(customSuccessFn,customErrorFn){
	var successCallback = function(ids,json){
		if(check_success(json.message)){
			showToast('success',json.message);
			
			for(var i = 0; i < ids.length; i++){
				$("#row" + ids[i]).remove();
			}
		}else{
			showToast('error',json.message);
		}
	}
	
	var errorCallback = function(){
		showToast('error',"删除出错，服务器异常，请稍后再次尝试");
	}
	
	$("a.delete").bind("click",function(){
		var triggerObj = $(this);
		
		var title = triggerObj.attr('data-title');
		if(typeof(title) == 'undefined'){
			title = '';
		}
		
		ui_confirm(triggerObj,[$(this).attr("data-id")],$(this).attr("data-url"),'你确定要删除<span class="hightlight">' + title + '</span>吗？','json',customSuccessFn ? customSuccessFn : successCallback,customErrorFn ? customErrorFn: errorCallback);
  	});
  	
  	$(".deleteBtn").bind("click",function(){
  		var triggerObj = $(this);
  		var ids = getIDS(triggerObj);
  		var url = triggerObj.attr("data-url");
  		var title = triggerObj.attr('data-title');
		if(typeof(title) == 'undefined'){
			title = '';
		}
		
		
  		if(ids.length == 0){
  			showToast('error','请选勾选.');
  		}else{
  			ui_confirm(triggerObj,ids,url,'你确定要删除<span class="hightlight">' + title + '</span>吗？','json',customSuccessFn ? customSuccessFn : successCallback,customErrorFn ? customErrorFn: errorCallback);
  		}
  	});
}



$(function(){
	// 全选 start
	$("body").delegate('.checkall','click',function(){
		var group = $(this).prop("name");
		$("input[group="+group+"]").prop("checked" , $(this).prop("checked") );
	});
	
	
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
    
    $("#repub .detail").bind('click',function(){
    	var repubUrl = $(this).attr('data-url');
    	
    	$.get(repubUrl, { },function(resp){
    		$("#showDlg" ).html(resp).dialog({
        		title: "重新发布列表",
        		autoOpen: false,
        		width: '50%',
        		height:300,
        		position: { my: "center", at: "center", of: window },
        		modal: true
        	}).dialog('open');
    		
    	} ,'html');
    	
    });
    
    
    $("body").delegate("input[name=repubcancel]","click",function(){
        var selected = $("input[name='goods_id[]']:checked").size();
        if(selected == 0){
            showToast('error', '请先勾选');
            return ;
        }
        
        var ids = [];
        
        $("input[name='goods_id[]']").not("input:checked").each(function(){
        	ids.push($(this).val());
        });
        
        $("input[name='goods_id[]']:checked").each(function(){
        	$("#gid" + $(this).val()).remove();
        });
        
        if(ids.length){
        	$("#repub .title em").html(ids.length);
        	$("#repub").show();
        }else{
        	$("#repub").hide();
        }
        
        setcookie('repub',ids.join('|'),86400);
    });
    
    
});