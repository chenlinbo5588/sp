{include file="common/header.tpl"}

<div id="my" class="pd5 bordered">
    <div class="row">
        <img src="{base_url($user['basic']['avatar_big'])}" alt="{base_url($user['basic']['nickname'])}"/>
    </div>
    <div class="row"><span class="icon ico_username">真实名称:</span><span>{$user['basic']['username']}</span></div>
    <div class="row"><span class="icon ico_nickname">昵称:</span><span>{$user['basic']['nickname']} 在线离线</span></div>
    <div class="row"><span class="icon ico_district">所在地区:</span><span></span></div>
    <div class="row">职业生涯</div>
</div>


{include file="common/footer.tpl"}