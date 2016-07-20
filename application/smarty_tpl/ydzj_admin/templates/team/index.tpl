{include file="common/main_header.tpl"}
{config_load file="team.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
        <li><a href="javascript:void(0);" class="current"><span>{#manage#}</span></a></li>
        <li><a href="{admin_site_url('team/add')}" ><span>{#add#}</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch" action="{admin_site_url('team')}">
    <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
            <td colspan="5">
                <div class="cityGroupWrap">
                	<label>选择地区</label>
                    <select name="d1" class="citySelect">
                        <option value="">{#choose#}</option>
                        {foreach from=$ds['d1'] item=item}
                        <option value="{$item['id']}" {if $smarty.get['d1'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                        {/foreach}
                    </select>
                    <select name="d2" class="citySelect">
                        <option value="">{#choose#}</option>
                        {foreach from=$ds['d2'] item=item}
                        <option value="{$item['id']}" {if $smarty.get['d2'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                        {/foreach}
                    </select>
                    <select name="d3" class="citySelect">
                        <option value="">{#choose#}</option>
                        {foreach from=$ds['d3'] item=item}
                        <option value="{$item['id']}" {if $smarty.get['d3'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                        {/foreach}
                    </select>
                    <select name="d4" class="citySelect">
                        <option value="">{#choose#}</option>
                        {foreach from=$ds['d4'] item=item}
                        <option value="{$item['id']}" {if $smarty.get['d4'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                        {/foreach}
                    </select>
                </div>
            </td>
        </tr>
        <tr>
          <td colspan="2"><label>{#team_name#}<input type="text" value="{$smarty.get['team_name']}" name="team_name" class="txt"></label></td>
         <td><select name="create_time">
              <option  value="">{#create_time#}</option>
              {foreach from=$search_map['create_time'] key=key item=item}
              <option  value="{$key}" {if $smarty.get['create_time'] == $key}selected{/if}>{$item}</option>
              {/foreach}
            </select>
         </td>
         <td><select name="team_state" >
              <option  value="">{#team_state#}</option>
              {foreach from=$search_map['member_state'] key=key item=item}
              <option  value="{$key}" {if $smarty.get['member_state'] == $key}selected{/if}>{$item}</option>
              {/foreach}
            </select>
          </td>
          <td>
            <input type="submit" class="msbtn" name="tijiao" value="查询"/>
          </td>
        </tr>
      </tbody>
    </table>
   </form>
   <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>&nbsp;</th>
          <th colspan="2">{#team#}</th>
          <th class="align-center">{#team_leader#}</th>
          <th class="align-center">{#team_owner#}</th>
          <th>{#team_city#}</th>
          <th>{#team_game_stat#}</th>
          <th class="align-center">{#join_type#}</th>
          <th class="align-center">{#accept_game#}</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      {foreach from=$list['data'] item=item key=key}
        <tr class="hover member">
          <td class="w24"><input name="id[]" group="chkVal" type="checkbox" value="{$item['id']}"></td>
          <td class="w120 picture">
            <div class=""><span class="thumb"><i></i><img src="{resource_url($item['avatar_m'])}"  data-avatar="{$item['avatar_b']}" /></span></div></td>
          <td>
            <p class="name"><strong>{#team_name#}:&nbsp;{$item['title']|escape}</strong></p>
            <p><strong>{#team_category#}:&nbsp;</strong><span>{$item['category_name']|escape}</span></p>
            <p class="smallfont">{#create_time#}}:&nbsp;{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</p>
            <div class="im">
            {if $item['leader_uid']}
                {if $userInfo[$item['leader_uid']]['email'] != ''}
                <span class="email">
                    <a href="mailto:{$userInfo[$item['leader_uid']]['email']}" class="yes" title="电子邮箱:{$userInfo[$item['leader_uid']]['email']|escape}">{$userInfo[$item['leader_uid']]['email']}</a>
                </span>
                {/if}
                {if $userInfo[$item['leader_uid']]['qq'] != ''}
                <span class="qq">
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={$userInfo[$item['leader_uid']]['qq']}&site=qq&menu=yes" class="yes"  title="QQ: {$userInfo[$item['leader_uid']]['qq']|escape}">&nbsp;</a>
                </span>
                {/if}
                {if $userInfo[$item['leader_uid']]['weixin'] != ''}
                <span class="weixin"><a href="javascript:void(0)" title="{$userInfo[$item['leader_uid']]['weixin']|escape}">{$userInfo[$item['leader_uid']]['weixin']|escape}</a></span>
                {/if}
            {/if}
            </div>
          </td>
          <td class="align-center w120">
          	
            {if $item['leader_uid']}
            <p><strong></strong><span>{$userInfo[$item['leader_uid']]['nickname']|escape}</span></p>
            <div class=""><a href="{admin_site_url('member/edit')}?id={$item['leader_uid']}"><img src="{resource_url($userInfo[$item['leader_uid']]['avatar_s'])}" /></a></div>
            <p>{$userInfo[$item['leader_uid']]['mobile']}</p>
            {else}
            未设置队长
            {/if}
          </td>
          <td class="align-center w120">
          	{if $item['channel'] != 1}
            <p><strong>创建人:&nbsp;</strong><span>{$userInfo[$item['add_uid']]['nickname']|escape}</span></p>
            <div class=""><a href="{admin_site_url('member/edit')}?id={$item['add_uid']}"><img src="{resource_url($userInfo[$item['add_uid']]['avatar_s'])}" /></a></div>
            <p>{$userInfo[$item['add_uid']]['mobile']}</p>
            {else}
            后台创建
            {/if}
          </td>
          <td>{$item['dname1']}{$item['dname2']}{$item['dname3']}{$item['dname4']}</td>
          <td>{$item['games']}/{$item['victory_game']}/{$item['fail_game']}/{$item['draw_game']}</td>
          <td class="align-center">{if $item['joined_type'] == 1}{#join_type1#}{else}{#join_type2#}{/if}</td>
          <td class="align-center">{if $item['accept_game'] == 1}是{else}否{/if}</td>
          <td class="align-center"><a href="{admin_site_url('team/edit')}?id={$item['id']}">编辑</a> | <a href="{admin_site_url('notice/member')}?id={$item['id']}">通知</a></td>
        </tr>
      {/foreach}
      </tbody>
      <tfoot class="tfoot">
        <tr>
          <td colspan="11">
            {include file="common/pagination.tpl"}
            <label><input type="checkbox" class="checkall" name="chkVal">全选</label>
            <input type="button" value="审核通过" class="btn" name="verfiy"/>
            <input type="button" value="审核失败" class="btn"  name="verfiy"/>
        </tr>
      </tfoot>
    </table>
<script>
$(function(){
    $("input[type=submit]").click(function(){
        $("#formSearch input[name=page]").val(1);
    });
});
</script>
{include file="common/main_footer.tpl"}