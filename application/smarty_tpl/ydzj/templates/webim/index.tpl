<!doctype html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0" />
    <title>WebIM-DEMO</title>
    <!--sdk-->
    <script src='sdk/dist/strophe.js'></script>
    <script src='sdk/dist/websdk-1.1.2.js'></script>

    <!--config-->
    <script src="demo/javascript/dist/webim.config.js"></script>

    <!--[if lte IE 9]>
    <script src="demo/javascript/dist/swfupload/swfupload.min.js"></script>
    <![endif]-->
    <base href="{base_url('static/web-im-1.1.2/')}" />
</head>
<body>
    <section id='main' class='w100'>
        <article id='demo'></article>
        <article id='components'></article>
    </section>

    <!--demo javascript-->
	<script src="demo/javascript/dist/demo.js"></script>
</body>
</html>