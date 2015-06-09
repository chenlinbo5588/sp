<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>{$SEO_title}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="description" content="{$SEO_description}" />
        <meta name="keywords" content="{$SEO_keywords}" />
        <meta name="viewport" content="width=device-width,
                                   initial-scale=1.0,
                                   maximum-scale=1.0,
                                   user-scalable=no,
                                   minimal-ui">
        
        <link href="{css_url('css/jquery.mobile-1.4.5.css')}" rel="stylesheet" type="text/css" />
        <script src="{js_url('js/jquery-1.11.3.js')}" type="text/javascript"></script>
        <script src="{js_url('js/jquery.mobile-1.4.5.js')}" type="text/javascript"></script>
    </head>
    <body>
	   <div id="mainWrap">
	        <div class="site_title"><h1>{$SEO_title}</h1></div>
	        <div data-role="navbar" data-grid="c" data-position="fixed">
                <ul>
                    <li><a href="{admin_site_url('stadium')}" class="ui-btn-active">体育场馆</a></li>
                    <li><a href="{admin_site_url('product')}">周边产品</a></li>
                    <li><a href="{admin_site_url('my')}">我</a></li>
                    <li><a href="#">Three</a></li>
                </ul>
            </div><!-- /navbar -->
            

            