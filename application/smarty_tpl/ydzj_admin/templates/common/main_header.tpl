<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$SEO_title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="{$SEO_description}" />
<meta name="keywords" content="{$SEO_keywords}" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no,minimal-ui"/>
<link href="{resource_url('css/skin_0.css')}" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript" src="{resource_url('js/jquery.js')}" ></script>
<script type="text/javascript" src="{resource_url('js/jquery.validation.min.js')}"></script>
<script type="text/javascript" src="{resource_url('js/jquery.cookie.js')}"></script>
<script>
var cookiedomain = "",
    cookiepath = "{config_item('cookie_path')}",
    cookiepre = "{config_item('cookie_prefix')}",
    formhash = "{$formhash}",
    cookie_skin = $.cookie("MyCssSkin"),
    SITEURL = '{base_url()}',
    cityUrl = "{site_url('district/index/')}",
    LOADING_IMAGE = "{resource_url('img/loading/loading.gif')}";
    
if (cookie_skin) {
    $('#cssfile2').attr("href","{resource_url('css')}/"+ cookie_skin +".css");
} 
</script>
<script type="text/javascript" src="{resource_url('js/common.js')}"></script>
<script type="text/javascript" src="{resource_url('js/admincp.js')}"></script>
</head>
<body>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">