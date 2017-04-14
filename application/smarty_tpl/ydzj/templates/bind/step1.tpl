<!DOCTYPE html>
<html lang="en">
<head>
    {include file="common/wxheader.tpl"}
</head>
<body>
    <h1 class="title">{$siteSetting['site_name']|escape}绑定流程</h1>
    <form action="{site_url('bind/step2')}" method="post">
        <input type="hidden" name="openid" value="{$openid}"/>
        <p>当您绑定手机时，表示您已经同意遵守本规章。 </p>
        <p>欢迎您关注本公众平台为您提供更好的服务。</p>
        <p>我们特此申明我们将竭力保护你的隐私信息。</p>
        <p>我们不会将你的个人手机号码用于商业用途。</p>
        <p>为维护网上公共秩序和社会稳定，请您自觉遵守以下条款： </p>
        <p> 一、不得利用本站危害国家安全、泄露国家秘密，不得侵犯国家社会集体的和公民的合法权益，不得利用本站制作、复制和传播下列信息：　 </p>
        <p>（一）煽动抗拒、破坏宪法和法律、行政法规实施的；　</p>
        <p>（二）煽动颠覆国家政权，推翻社会主义制度的；　</p>
        <p>（三）煽动分裂国家、破坏国家统一的；　</p>
        <p>（四）煽动民族仇恨、民族歧视，破坏民族团结的；　</p>
        <p>（五）捏造或者歪曲事实，散布谣言，扰乱社会秩序的；</p>
        <p>（六）宣扬封建迷信、淫秽、色情、赌博、暴力、凶杀、恐怖、教唆犯罪的；　</p>
        <p>（七）公然侮辱他人或者捏造事实诽谤他人的，或者进行其他恶意攻击的；　</p>
        <p>（八）损害国家机关信誉的；　</p>
        <p>（九）其他违反宪法和法律行政法规的；　</p>
        <p>（十）进行商业广告行为的。</p>
        <p>二、禁止以任何方式对本站进行各种破坏行为。</p>
        <p>三、禁止您有违反国家相关法律法规的行为。</p>

        <div style="text-align:center;"><input type="submit" name="submit" class="btn_blue" style="font-size:16px;" value="同意"/></div>
    </form>
    
    {include file="common/wxfooter.tpl"}
</body>
</html>