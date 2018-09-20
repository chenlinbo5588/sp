<!DOCTYPE html>
<head>
<title>{$SEO_title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="{$SEO_description}" />
<meta name="keywords" content="{$SEO_keywords}" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no,minimal-ui"/>
<link href="{resource_url('css/skin_0.css',true)}" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript" src="{resource_url('js/jquery.js')}"></script>
<script type="text/javascript" src="{resource_url('js/jquery-ui/i18n/zh-CN.js')}"></script>

<script>
var cookiedomain = "{config_item('cookie_domain')}",
    cookiepath = "{config_item('cookie_path')}",
    cookiepre = "{config_item('cookie_prefix')}",
    formhash = "{$formhash}",
    SITEURL = '{base_url()}',
    cityUrl = "{site_url('district/index/')}",
    commonUploadUrl = "{admin_site_url("common/pic_upload")}",
    uploadSizeLimit = "{$image_max_filesize}",
    LOADING_IMAGE = "{resource_url('img/loading/loading.gif')}";
    
</script>
{include file="common/jquery_ui.tpl"}
<link rel="stylesheet" href="{resource_url('js/toast/jquery.toast.min.css')}"/>
<script type="text/javascript" src="{resource_url('js/toast/jquery.toast.min.js')}"></script>
<script type="text/javascript" src="{resource_url('js/common.js',true)}"></script>
<script type="text/javascript" src="{resource_url('js/admincp.js',true)}"></script>
<script type="text/javascript" src="{resource_url('js/echarts.js',true)}"></script>
</head>
<body>
<a name="top"></a>
<div id="showDlg" style="display:none;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">