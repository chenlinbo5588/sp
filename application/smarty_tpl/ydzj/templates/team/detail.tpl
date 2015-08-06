{include file="common/header.tpl"}

{if $inManageMode}
{form_open(site_url($formTarget),'id="teamManageForm"')}
{/if}
<div id="teamDetail" class="row{if $inManageMode} {/if}">
    <div class="row teamCoverImg" style="background:url({base_url($team['basic']['logo_url'])}) no-repeat 50% 50%;"></div>
    <div class="row bordered pd5">
        <div class="row">
            <label class="side_lb">留言：</label>
            {if $inManageMode}
            <input type="text" style="width:80%;"; name="notice_board" value="{$team['basic']['notice_board']}" placeholder="请输入留言"/>
            {else}
            <span>{$team['basic']['notice_board']}</span>
            {/if}
        </div>
        <div class="row"><label class="side_lb">地址：</label><span>浙江省慈溪市崇寿镇</span><a class="fr" href="{site_url('team/placeto')}/{$team['basic']['id']}">地图</a></div>
        <div class="row"><label class="side_lb">总比赛数：</label><span>{$team['basic']['games']}</span></div>
        <div class="row"><label class="side_lb">胜/负/胜率：</label><span>{$team['basic']['victory_game']}/{$team['basic']['fail_game']}/{$team['basic']['victory_rate']}</span></div>
    </div>
    <div class="row pd5">
        <h3 class="subTitle">成员({$team['basic']['current_num']})人</h3>
        <ul id="teamMembers" class="clearfix">
        {if $inManageMode}
            {foreach from=$team['members'] item=item}
            <li class="member manage clearfix">
                <div>
	                <div class="headerImg fl">
	                   <a href="{site_url('user/info/')}/{$item['uid']}" title="{$item['nickname']|escape}"><img src="{base_url($item['avatar'])}"/></a>
	                   <div>[{if empty($item['position'])}未知{else}{$item['position']}{/if}]</div>
	                </div>
	                <div class="positionSet fl">
	                    {if $profile['memberinfo']['uid'] !=  $item['uid']}
	                    <input type="hidden" name="kick[{$item['uid']}]" id="kick_{$item['uid']}" value=""/>
                        <input type="button" class="at_txt kickoffBtn" class="slaveBtn" name="kickoff" value="踢掉" data-id="kick_{$item['uid']}"/>
                        {/if}
	                    <input type="text" class="at_txt" name="nickname[{$item['uid']}]" value="{$item['nickname']|escape}"/>
	                    {foreach from=$positionList item=pitem}
	                    <label><input type="radio" name="position[{$item['uid']}]" value="$pitem['name']" {if $pitem['name'] == $item['position']}checked{/if}/>{$pitem['name']}</label>
	                    {/foreach}
	                    
	                </div>
	                
                </div>
            </li>
            {/foreach}
       {else}
           {foreach from=$team['members'] item=item}
            <li class="member">
                <a href="{site_url('user/info/')}/{$item['uid']}" title="{$item['nickname']|escape}"><img src="{base_url($item['avatar'])}"/></a>
                <div>[{if empty($item['position'])}未知{else}{$item['position']}{/if}]</div>
                <div>{$item['nickname']|escape}</div>
            </li>
            {/foreach}
       {/if}
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
        {* 二期完善 *}
        {if $team['basic']['joined_type'] == 2}
        <input class="primaryBtn" type="button" name="joinApply" value="申请加入"/>
        {/if}
        
        {if $canManager}
        <div class="row">
            <input type="text" class="at_txt" name="inviteUrl" value="{$inviteUrl}"/>
            <p class="muted">链接24小时内有效，超过时间请刷新页面重新复制</p>
        </div>
        <input class="primaryBtn grayed" type="button" name="inviteUrl" value="全选复制邀请链接并发送给您的朋友"/>
        {/if}
    </div>
</div>

{if $inManageMode}
<div class="row" id="submitFixedWrap" >
    <div class="row fl col2">
        <input class="primaryBtn fr " type="submit" name="submit" value="保存"/>
    </div>
    <div class="row fl col2">
        <a class="link_btn grayed" href="{$editUrl}"}>{$mangeText}</a>
    </div>
</div>
</form>
<script src="{base_url('js/team_manage.js')}" type="text/javascript"></script>
{else}
<div class="row" id="submitFixedWrap" >
    {if $canManager}
    <div class="row col">
        <a class="link_btn" href="{$editUrl}"}>{$mangeText}</a>
    </div>
    {else}
    <div class="row col">
        <a class="link_btn" href="javascript:void(0)"}>预约比赛</a>
    </div>
    {/if}
</div>
{/if}
{include file="common/footer.tpl"}