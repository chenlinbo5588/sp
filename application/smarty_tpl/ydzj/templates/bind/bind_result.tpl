<!DOCTYPE html>
<html lang="en">
<head>
    {include file="common/wxheader.tpl"}
</head>
<body>
    <h1 class="title">{$siteSetting['site_name']|escape}公众号绑定流程</h1>
    {if $result == 'success'}
    <div><img src="{resource_url('img/succeed.png')}"/> 恭喜，您已经成功绑定手机号码。</div>
    {else}
    <div><img src="{resource_url('img/wrong.png')}"/> 对不起，绑定失败。</div>
    {/if}
    {include file="common/wxfooter.tpl"}
</body>
</html>