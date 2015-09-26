<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>{$SEO_title}</title>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="description" content="{$SEO_description}" />
	<meta name="keywords" content="{$SEO_keywords}" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no,minimal-ui">
    <link rel="stylesheet" href="{base_url('css/base.css')}"/>
    <link rel="stylesheet" href="{base_url('css/site.css')}"/>
    <link rel="stylesheet" href="{base_url('font-awesome/css/font-awesome.min.css')}"/>
	<script src="{js_url('js/jquery-1.11.3.min.js')}" type="text/javascript"></script>
	<script src="{js_url('js/common.js')}" type="text/javascript"></script>
</head>
<body>
   <div id="wrap">
       <header>
           <div id="topBar" class="clearfix">
               {$LEFT_BUTTON}
               <h1>{if $TOP_NAV_TITLE}{$TOP_NAV_TITLE}{else}{$SEO_title|escape}{/if}</h1>
               {$RIGHT_BUTTON}
           </div>
       </header>
