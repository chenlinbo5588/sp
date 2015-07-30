{include file="common/header.tpl"}


<div id="teamDetail" class="row">
    <div class="row teamCoverImg" style="background:url({base_url($teamInfo['basic']['logo_url'])}) no-repeat 50% 50%;"></div>
    {form_open(site_url('team/join_apply'))}
    <div class="row bordered pd5">
        <div class="row"><label class="side_lb">留言：</label><span>队长没有留下留言</span></div>
        <div class="row"><label class="side_lb">地址：</label><span>浙江省慈溪市崇寿镇</span><a class="fr" href="javascript:void(0);">导航到这里</a></div>
        <div class="row"><label class="side_lb">总比赛数：</label><span>{$teamInfo['basic']['games']}</span></div>
        <div class="row"><label class="side_lb">胜/负/胜率：</label><span>{$teamInfo['basic']['victory_game']}/{$teamInfo['basic']['fail_game']}/{$teamInfo['basic']['victory_rate']}</span></div>
    </div>
    <div class="row">
        <h3 class="subTitle">成员列表</h3>
        <ul id="teamMembers" class="clearfix">
            {foreach from=$teamInfo['members'] item=item}
            <li>
                <div class="member">
                    <a href="{site_url('user/info/')}/{$item['uid']}" title="{$item['nickname']|escape}"><img src="{base_url($item['avatar'])}"/></a>
                    <span>[{if empty($item['position'])}未知{else}{$item['position']}{/if}]</span>
                    <span>{$item['nickname']|escape}</span>
                </div>
            </li>
            {/foreach}
        </ul>
    </div>
    
    {* 最近比赛情况 *}
    <div class="row">
        <h3 class="subTitle">最近比赛</h3>
        <table class="fulltable">
            <colgroup>
                <col style="witdh:20%;"/>
                <col style="witdh:40%;"/>
                <col style="witdh:10%;"/>
                <col style="witdh:20%;"/>
                <col style="witdh:10%;"/>
            </colgroup>
            <thead>
                <tr>
	                <th>日期</th>
	                <th>对手</th>
	                <th>主/客</th>
	                <th>比分</th>
	                <th>胜负</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>15/6/27</td>
                    <td><a href="javascript:void(0);">野狼部落</a></td>
                    <td>主</td>
                    <td>98:87</td>
                    <td>胜</td>
                </tr>
                <tr>
                    <td>15/6/27</td>
                    <td><a href="javascript:void(0);">野狼部落</a></td>
                    <td>主</td>
                    <td>98:87</td>
                    <td>胜</td>
                </tr>
                <tr>
                    <td>15/6/27</td>
                    <td><a href="javascript:void(0);">野狼部落</a></td>
                    <td>主</td>
                    <td>98:87</td>
                    <td>胜</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="row pd5">
        {if $teamInfo['basic']['joined_type'] == 1}
        <input class="primaryBtn" type="button" name="joinApply" value="申请加入"/>
        {else}
        
        {if !empty($profile['memberinfo']['uid'])}
        <div class="row">
            <input type="text" class="at_txt" name="inviteUrl" value="{$inviteUrl}"/>
            <p class="warning">链接48小时内有效，超过时间请刷新页面重新复制</p>
        </div>
        <input class="primaryBtn" type="button" name="inviteUrl" value="全选复制邀请链接并发送给您的朋友"/>
        {/if}
        {/if}
    </div>
    </form>
</div>

{*
<!-- JiaThis Button BEGIN -->
<div class="jiathis_style_m"></div>
<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_m.js" charset="utf-8"></script>
<!-- JiaThis Button END -->
<!-- UY BEGIN -->
<div id="uyan_frame"></div>
<script type="text/javascript" src="http://v2.uyan.cc/code/uyan.js"></script>
<!-- UY END -->
*}
{include file="common/footer.tpl"}