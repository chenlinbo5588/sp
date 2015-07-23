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
        
        <script src="{js_url('js/jquery-1.11.3.js')}" type="text/javascript"></script>
    </head>
    <body>
	   <div id="mainWrap">
            <div data-role="header" data-theme="a">
                <h1>{$SEO_title}</h1>
            </div>
	        <div data-role="navbar" data-grid="c" data-position="fixed">
                <ul>
                    <li><a href="{admin_site_url('member')}">用户</a></li>
                    <li><a href="{admin_site_url('stadium')}" class="ui-btn-active">场馆</a></li>
                    <li><a href="{admin_site_url('product')}">道具</a></li>
                    <li><a href="{admin_site_url('my')}">设置</a></li>
                    <li><a href="{admin_site_url('my/logout')}">退出</a></li>
                </ul>
            </div>
            

            