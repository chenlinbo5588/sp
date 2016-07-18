<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>{$SEO_title}</title>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="{$SEO_description}" />
<meta name="keywords" content="{$SEO_keywords}" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no,minimal-ui">
<link rel="stylesheet" href="{resource_url('css/site.css')}"/>
<script type="text/javascript" src="{resource_url('js/jquery.js')}"></script>
<script>
var cookiedomain = "",
    cookiepath = "{config_item('cookie_path')}",
    cookiepre = "{config_item('cookie_prefix')}",
    formhash = "{$formhash}",
    SITEURL = '{base_url()}',
    cityUrl = "{site_url('district/index/')}",
    LOADING_IMAGE = "{resource_url('img/loading/loading.gif')}";
</script>
<script type="text/javascript" src="{resource_url('js/common.js')}"></script>
<!--[if lte IE 9]>
<script type="text/javascript" src="{resource_url('js/html5shiv.js')}"></script>
<![endif]-->
<script type="text/javascript" src="{resource_url('js/respond.min.js')}"></script>
</head>
<body {if $bodyClass}class="{$bodyClass}"{/if}>
   <div id="wrap">
   		<header>
            <div id="topBar" class="clearfix">
                {$LEFT_BUTTON}
                <h1>{if $TOP_NAV_TITLE}{$TOP_NAV_TITLE}{else}{$SEO_title|escape}{/if}</h1>
                {$RIGHT_BUTTON}
            </div>
        </header>
   
