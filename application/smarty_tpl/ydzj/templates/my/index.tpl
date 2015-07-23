{include file="common/header.tpl"}

<div id="my" class="pd5">
    <div class="row">{$profile['memberinfo']['nickname']}</div>
    <div class="row">邮箱:{$profile['memberinfo']['email']} 未验证</div>
    <div class="row">手机:{$profile['memberinfo']['mobile']}</div>
    <div class="row">我的积分:{$profile['memberinfo']['credits']}</div>
    <div class="row">我的队伍:{$profile['memberinfo']['credits']}</div>
    <div class="row">参加队伍:{$profile['memberinfo']['credits']}</div>
    
    {* 链接, 进去再细分 各类运动，参加的比赛参数 和比赛数据 *}
    <div class="row">职业生涯</div>
    <div class="row"><a id="logout_link" href="javascript:void(0)" data-href="{site_url('member/logout')}">退出</a>
</div>
<script src="{base_url('js/my.js')}" type="text/javascript"></script>


{include file="common/footer.tpl"}