{include file="common/header.tpl"}

<div id="my" class="pd5 bordered">
    <div class="row first">
        <a class="avator" href="{site_url('set_avator')}" style="display:block;background:url({base_url($profile['memberinfo']['avatar'])}) no-repeat 50% 50%;">{$profile['memberinfo']['nickname']}</a>
    </div>
    <div class="row"><span class="icon ico_email">昵称:</span><span>{$profile['memberinfo']['nickname']}</span><a class="side_link" href="javascript:void(0)">去修改</a></div>
    <div class="row"><span class="icon ico_email">邮箱:</span><span>{$profile['memberinfo']['email']}</span><a class="side_link" href="javascript:void(0)">去验证邮箱</a></div>
    <div class="row"><span class="icon ico_mobile">手机:</span><span>{$profile['memberinfo']['mobile']}</span><a class="side_link" href="javascript:void(0)">去绑定手机</a></div>
    <div class="row"><span class="icon ico_mobile">地区:</span><span>{if $profile['memberinfo']['district_bind'] == 0}未设置{else}{$profile['memberinfo']['d1']}{$profile['memberinfo']['d2']}{$profile['memberinfo']['d3']}{$profile['memberinfo']['d4']}{/if}</span><a class="side_link" href="{site_url('my/set_city')}">{if $profile['memberinfo']['district_bind'] == 1}更新{else}设置{/if}</a></div>
    <div class="row"><span class="icon ico_mobile">我的积分:</span><span>{$profile['memberinfo']['credits']}</span><a class="side_link" href="{site_url('credits/details')}">积分明细</a></div>
    <div class="row"><span class="icon ico_mobile">我的队伍:</span><span>0</span><a class="side_link" href="{site_url('team/create_team')}">去创建队伍</a></div>
    <div class="row"><span class="icon ico_mobile">参加的队伍:</span><span>0</span><a class="side_link" href="{site_url('team')}">去加入队伍</a></div>
    
    {* 链接, 进去再细分 各类运动，参加的比赛参数 和比赛数据 *}
    <div class="row">职业生涯</div>
    <div class="row last"><a id="logout_link" class="link_btn" href="javascript:void(0)" data-href="{site_url('member/logout')}">退出</a>
</div>
<script src="{base_url('js/my.js')}" type="text/javascript"></script>


{include file="common/footer.tpl"}