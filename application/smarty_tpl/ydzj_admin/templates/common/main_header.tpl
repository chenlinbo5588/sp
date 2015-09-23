<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="{$SEO_description}" />
<meta name="keywords" content="{$SEO_keywords}" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no,minimal-ui"/>
<title>{$SEO_title}</title>
<script type="text/javascript" src="{js_url('js/jquery-1.11.3.min.js')}" ></script>
<script type="text/javascript" src="{js_url('js/jquery.validation.min.js')}"></script>
<script type="text/javascript" src="{js_url('js/jquery.cookie.js')}"></script>
<link href="{base_url('css/skin_0.css')}" rel="stylesheet" type="text/css" id="cssfile2" />
<script>
var cookiedomain = "{config_item('cookie_domain')}",
    cookiepath = "{config_item('cookie_path')}",
    cookiepre = "{config_item('cookie_prefix')}",
    LOADING_IMAGE = "{base_url('img/loading/loading.gif')}",
    cookie_skin = $.cookie("MyCssSkin");
    
if (cookie_skin) {
    $('#cssfile2').attr("href","{base_url('css')}/"+ cookie_skin +".css");
} 
</script>
</head>
<body>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>