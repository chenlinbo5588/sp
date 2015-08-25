{include file="common/header.tpl"}
{$feedback}
<div id="stadiumDetail">
    <div class="teamCoverImg"><img src="{base_url($stadium['basic']['avatar_big'])}" data-largeurl="{$stadium['basic']['avatar_large']}" alt="{$stadium['basic']['title']}"></div>
    <div class="row pd5">
        <a class="link_btn grayed" href="#">浏览大图</a>
    </div>
    <div class="row bordered pd5">
        <div class="row"><label class="side_lb">名称：</label><span>{$stadium['basic']['title']|escape}</span></div>
        <div class="row"><label class="side_lb">场馆分类：</label><span>{$stadium['basic']['category_name']}</span></div>
        <div class="row"><label class="side_lb">地址：</label><span>{$stadium['basic']['dname1']}{$stadium['basic']['dname2']}{$stadium['basic']['dname3']}{$stadium['basic']['dname4']}</span><a class="fr" href="{site_url('stadium/map/'|cat:$stadium['basic']['id'])}">地图</a></div>
        <div class="row">
            <label class="side_lb">联系人：</label>
            <span>{$stadium['basic']['contact']|escape}</span>
        </div>
        <div class="row">
            <label class="side_lb">联系号码：</label>
            <span>{$stadium['basic']['mobile']|escape}</span>
        </div>
        <div class="row"><label class="side_lb">场地类型：</label><span>{$stadium['basic']['stadium_type']}</span></div>
        <div class="row"><label class="side_lb">收费类型：</label><span>{$stadium['basic']['ground_type']}</span></div>
        <div class="row"><label class="side_lb">地面材质：</label><span>{$stadium['basic']['charge_type']}</span></div>
    </div>
    
    
    <div class="row">
        <h3 class="subTitle">最近进行的比赛</h3>
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
                    <th>A队</th>
                    <th>B队</th>
                    <th>比分</th>
                    <th>胜负</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>15/6/27</td>
                    <td><a href="javascript:void(0);">野狼部落</a></td>
                    <td><a href="javascript:void(0);">野狼部落</a></td>
                    <td>98:87</td>
                    <td>胜</td>
                </tr>
                <tr>
                    <td>15/6/27</td>
                    <td><a href="javascript:void(0);">野狼部落</a></td>
                    <td><a href="javascript:void(0);">野狼部落</a></td>
                    <td>98:87</td>
                    <td>胜</td>
                </tr>
                <tr>
                    <td>15/6/27</td>
                    <td><a href="javascript:void(0);">野狼部落</a></td>
                    <td><a href="javascript:void(0);">野狼部落</a></td>
                    <td>98:87</td>
                    <td>胜</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{include file="common/footer.tpl"}