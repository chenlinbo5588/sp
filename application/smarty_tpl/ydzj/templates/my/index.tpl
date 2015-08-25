{include file="common/header.tpl"}

<div id="my" class="bordered">
    <div class="row avatar">
        <img src="{base_url($profile['memberinfo']['avatar_big'])}" alt="{base_url($profile['memberinfo']['nickname'])}"/>
        <div class="pd5"><a class="link_btn grayed" href="{site_url('my/set_avatar?inviteFrom=my')}">修改头像</a></div>
    </div>
    <div class="pd5">
        <div class="row first"><span class="icon ico_username">真实名称:</span><span>{$profile['memberinfo']['username']}</span><a class="side_link" href="{site_url('my/set_username')}">修改</a></div>
	    <div class="row"><span class="icon ico_nickname">昵称:</span><span>{$profile['memberinfo']['nickname']} 在线离线</span><a class="side_link" href="javascript:void(0)">修改</a></div>
	    <div class="row"><span class="icon ico_email">邮箱:</span><span>{$profile['memberinfo']['email']}</span><a class="side_link" href="javascript:void(0)">验证邮箱</a></div>
	    <div class="row"><span class="icon ico_mobile">手机:</span><span>{$profile['memberinfo']['mobile']}</span><a class="side_link" href="javascript:void(0)">绑定手机</a></div>
	    <div class="row"><span class="icon ico_message">消息通知:</span><span>{$profile['memberinfo']['newpm']}</span><a class="side_link" href="javascript:void(0)">详情</a></div>
	    <div class="row"><span class="icon ico_district">所在地区:</span><span>{if $profile['memberinfo']['district_bind'] == 0}未设置{else}{$userDs[$profile['memberinfo']['d1']]['name']}{$userDs[$profile['memberinfo']['d2']]['name']}{$userDs[$profile['memberinfo']['d3']]['name']}{$userDs[$profile['memberinfo']['d4']]['name']}{/if}</span><a class="side_link" href="{site_url('my/set_city')}">{if $profile['memberinfo']['district_bind'] == 1}修改{else}设置{/if}</a></div>
	    <div class="row"><span class="icon ico_credits">我的积分:</span><span>{$profile['memberinfo']['credits']}</span><a class="side_link" href="{site_url('credits/details')}">积分明细</a></div>
	    <div class="row"><span class="icon ico_team">我的队伍:</span><span>0</span><a class="side_link" href="{site_url('team/create_team')}">创建队伍</a></div>
	    
	    <div class="row"><span class="icon ico_join">参加的队伍:</span><span>0</span><a class="side_link" href="{site_url('team')}">加入队伍</a></div>
	    <div class="row">
	        <span class="icon ico_invite">推广链接:</span><span><input style="width:72%" type="text" name="inviteUrl" value="{$inviteUrl}"/></span>
	    </div>
	    <div class="row">
            <input type="button" class="primaryBtn grayed" name="inviteUrl" value="复制链接发送给好友"/>
        </div>
	    
	    {* 链接, 进去再细分 各类运动，参加的比赛参数 和比赛数据 暂时留空
	    <div class="row">职业生涯</div> *}
	    
	    <div class="row last"><a id="logout_link" class="link_btn" href="javascript:void(0)" data-href="{site_url('member/logout')}">退出</a>
	    
    </div>
    
</div>
<script src="{base_url('js/my.js')}" type="text/javascript"></script>


{include file="common/footer.tpl"}