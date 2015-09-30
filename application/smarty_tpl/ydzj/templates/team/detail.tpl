{include file="common/header.tpl"}
{$feedback}
{if $inManageMode}
{form_open(site_url($formTarget),'id="teamManageForm"')}
{/if}
<div id="teamDetail">
    <div class="teamCoverImg"><img src="{base_url($team['basic']['avatar_big'])}" data-largeurl="{$team['basic']['avatar_large']}" alt="{$team['basic']['title']}"></div>
    <div class="row bordered pd5">
        {if $canManager}
        <a class="link_btn grayed" href="{site_url('team/set_teamavatar/'|cat:$team['basic']['id'])}">修改球队合影</a>
        {/if}
        <div class="row"><label class="side_lb">球队类型：</label><span>{$team['basic']['category_name']}队</span></div>
    	{form_error('notice_board')}
        <div class="row">
            <label class="side_lb">队长留言：</label>
            {if $inManageMode}
            <input type="text" class="at_txt" name="notice_board" value="{$team['basic']['notice_board']}" placeholder="请输入留言"/>
            {else}
            <span>{$team['basic']['notice_board']|escape}</span>
            {/if}
        </div>
        {form_error('slogan')}
        <div class="row">
            <label class="side_lb">球队口号：</label>
            {if $inManageMode}
            <input type="text" class="at_txt" name="slogan" value="{$team['basic']['slogan']}" placeholder="请输入队伍口号"/>
            {else}
            <span>{$team['basic']['slogan']|escape}</span>
            {/if}
        </div>
        {form_error('base_area')}
        <div class="row">
            <label class="side_lb">主场场地：</label>
            {if $inManageMode}
            <input type="text" class="at_txt" name="base_area" value="{$team['basic']['base_area']}" placeholder="请输入主场地址:如傅家路文化广场"/>
            {else}
            <span>{$team['basic']['base_area']|escape}</span>
            {/if}
        </div>
        <div class="row"><label class="side_lb">地址：</label><span>{$team['basic']['dname1']}{$team['basic']['dname2']}{$team['basic']['dname3']}{$team['basic']['dname4']}</span><a class="fr" href="{site_url('team/placeto/team/'|cat:$team['basic']['id'])}">地图</a></div>
        <div class="row"><label class="side_lb">总比赛数：</label><span>{$team['basic']['games']}</span></div>
        <div class="row"><label class="side_lb">胜/负/胜率：</label><span>{$team['basic']['victory_game']}/{$team['basic']['fail_game']}/{$team['basic']['victory_rate']}</span></div>
    </div>
    <div class="row pd5">
        <h3 class="subTitle">成员({$team['basic']['current_num']})人</h3>
        <ul class="teamMembers clearfix">
        {if $inManageMode}
            {foreach from=$team['members'] item=item}
            <li class="member manage clearfix">
            	<div class="delmask" {if set_value('kick['|cat:$item['id']|cat:']')}style="width:100%;height:100%;display:block;"{/if}>已标记删除,轻触取消</div>
                <div class="headerImg pd5">
                   <a href="{site_url('user/info/')}/{$item['uid']}" title="{$item['nickname']|escape}"><img src="{base_url($item['avatar_middle'])}"/></a>
                   <strong>昵称:{$item['nickname']|escape}</strong>
                   <p>真名:{$item['username']|escape}</p>
                   <p>位置:{$item['position']|escape}</p>
                   <p>职务:{$item['rolename']|escape}</p>
                </div>
                <div class="row pd5">
                    {form_error('username['|cat:$item['id']|cat:']')}
                    {form_error('num['|cat:$item['id']|cat:']')}
                	<div class="row">
                		{if $profile['basic']['uid'] !=  $item['uid']}
	                    <input type="hidden" name="kick[{$item['id']}]" id="kick_{$item['id']}" value="{set_value('kick['|cat:$item['id']|cat:']')}"/>
	                    <input type="button" class="at_txt kickoffBtn" class="slaveBtn" name="kickoff" value="踢掉" data-id="kick_{$item['id']}"/>
	                    {/if}
	                    <label>真名:</label><input type="text" class="at_txt edit_username" name="username[{$item['id']}]" value="{if $ispost}{set_value('username['|cat:$item['id']|cat:']')}{else}{$item['username']|escape}{/if}" placeholder="真实名称,仅队内可见"/>
	                    <label>球衣号码:</label><input type="text" class="at_txt edit_num" name="num[{$item['id']}]" value="{if $ispost}{set_value('num['|cat:$item['id']|cat:']')}{else}{$item['num']|escape}{/if}" placeholder="请输入球衣号码"/>
                	</div>
	                <div class="positionSet">
	                	<h3>位置</h3>
	                    {foreach from=$positionList item=pitem}
	                    <label><input type="radio" name="position[{$item['id']}]" value="{$pitem['name']}" {if $ispost}{set_radio('position['|cat:$item['id']|cat:']',$pitem['name'],true)}{else}{if $pitem['name'] == $item['position']}checked{/if}{/if}/>{$pitem['name']}</label>
	                    {/foreach}
	                </div>
	                <div class="positionSet">
	                	<h3>职位(仅队伍内部可见)</h3>
	                    {foreach from=$roleList item=pitem}
	                    <label><input type="radio" name="rolename[{$item['id']}]" value="{$pitem['name']}" {if $ispost}{set_radio('rolename['|cat:$item['id']|cat:']',$pitem['name'],true)}{else}{if $pitem['name'] == $item['rolename']}checked{/if}{/if}/>{$pitem['name']}</label>
	                    {/foreach}
	                </div>
                </div>
            </li>
            {/foreach}
       {else}
           {foreach from=$team['members'] item=item}
            <li class="member">
                <a href="{site_url('user/info/')}/{$item['uid']}" title="{$item['nickname']|escape}"><img src="{base_url($item['avatar_middle'])}"/></a>
                <div>[{if empty($item['position'])}未知{else}{$item['position']}{/if}]</div>
                <div>{$item['nickname']|escape}</div>
            </li>
            {/foreach}
       {/if}
        </ul>
    </div>
    
    {* 最近比赛情况 *}
    <div class="row">
        <h3 class="subTitle">最近战绩</h3>
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
        <input class="master_btn" type="button" name="joinApply" value="申请加入"/>
        {/if}
        
        {if $canManager}
        <div class="row">
            <input type="text" class="at_txt" name="inviteUrl" value="{$inviteUrl}"/>
            <p class="muted">链接24小时内有效，超过时间请刷新页面重新复制</p>
        </div>
        <input class="master_btn grayed" type="button" name="inviteUrl" value="全选复制邀请链接并发送给您的朋友"/>
        {/if}
    </div>
</div>

{if $inManageMode}
<div class="row" id="submitFixedWrap" >
    <div class="row fl col2">
        <input class="master_btn fr " type="submit" name="submit" value="保存"/>
    </div>
    <div class="row fl col2">
        <a class="link_btn grayed" href="{$editUrl}"}>{$mangeText}</a>
    </div>
</div>
</form>
<script>
var errorInputKey = [];
{foreach from=$errorMsg key=ek item=et}
errorInputKey.push("{$ek}");
{/foreach}
</script>
<script src="{base_url('js/team/team_manage.js')}" type="text/javascript"></script>
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