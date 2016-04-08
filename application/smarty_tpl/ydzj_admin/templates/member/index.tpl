{include file="common/main_header.tpl"}
{config_load file="member.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
        <li><a href="javascript:void(0);" class="current"><span>{#manage#}</span></a></li>
        <li><a href="{admin_site_url('member/add')}" ><span>{#add#}</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch" action="{admin_site_url('member')}">
    <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
            <td colspan="5">
                <div class="cityGroupWrap">
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
          <td><select name="search_field_name" >
              {foreach from=$search_map['search_field'] key=key item=item}
              <option value="{$key}" {if $smarty.get['search_field_name'] == $key}selected{/if}>{$item}</option>
              {/foreach}
            </select>
          </td>
          <td><input type="text" value="{$smarty.get['search_field_value']}" name="search_field_value" class="txt"></td>
          
          <td><select name="register_channel">
              <option  value="">{#register_channel#}</option>
              {foreach from=$search_map['register_channel'] key=key item=item}
              <option  value="{$key}" {if $smarty.get['register_channel'] == $key}selected{/if}>{$item}</option>
              {/foreach}
            </select>
         </td>
         
         <td><select name="register_sort">
              <option  value="">{#register_time#}</option>
              {foreach from=$search_map['register_sort'] key=key item=item}
              <option  value="{$key}" {if $smarty.get['register_sort'] == $key}selected{/if}>{$item}</option>
              {/foreach}
            </select>
         </td>
         <td><select name="member_state" >
              <option  value="">{#member_state#}</option>
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
   
   <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5>操作提示</h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li>通过会员管理，你可以进行查看、编辑会员资料以及删除会员等操作</li>
            <li>你可以根据条件搜索会员，然后选择相应的操作</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
   <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>&nbsp;</th>
          <th colspan="2">会员</th>
          <th>最后登录</th>
          <th class="align-center">邀请人</th>
          <th>地区</th>
          <th>积分</th>
          <th>状态</th>
          <th class="align-center">发表言论</th>
          <th class="align-center">登录</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      {foreach from=$list['data'] item=item key=key}
        <tr class="hover member">
          <td class="w24"></td>
          <td class="w120 picture">
            <div class=""><span class="thumb"><i></i><img src="{base_url($item['avatar_small'])}"  data-avatar="{$item['avatar_middle']}" /></span></div></td>
          <td>
            <p class="name"><strong>账号:&nbsp;{$item['mobile']|escape}</strong></p>
            <p><strong>昵称:&nbsp;</strong><span>{$item['nickname']|escape}</span></p>
            <p><strong>性别:&nbsp;</strong><span>{if $item['sex'] == 'M'}男{elseif $item['sex'] == 'F'}女{else}保密{/if}</span><span>(真实姓名: {$item['username']|escape})</span></p>
            <p class="smallfont">注册时间:&nbsp;{$item['reg_date']|date_format:"%Y-%m-%d %H:%M:%S"}</p>
            <p class="smallfont">注册IP:&nbsp;{$item['reg_ip']}</p>
            <div class="im">
                {if $item['email'] != ''}
                <span class="email">
                    <a href="mailto:{$item['email']}" class="yes" title="电子邮箱:{$item['email']|escape}">{$item['email']}</a>
                </span>
                {/if}
                {if $item['qq'] != ''}
                <span class="qq">
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={$item['qq']}&site=qq&menu=yes" class="yes"  title="QQ: {$item['qq']|escape}">&nbsp;</a>
                </span>
                {/if}
                {if $item['weixin'] != ''}
                <span class="weixin"><a href="javascript:void(0)" title="{$item['weixin']|escape}">{$item['weixin']|escape}</a></span>
                {/if}
            </div>
          </td>
          <td>
            <p>{if $item['last_login']}{$item['last_login']|date_format:"%Y-%m-%d %H:%M:%S"}</p>
            <p>{$item['last_loginip']}{/if}</p>
          </td>
          <td class="align-center w120">
            {if $item['inviter']}
            <div class=""><a href="{admin_site_url('member/edit')}/{$item['inviter']}"><img src="{base_url($inviterInfo[$item['inviter']]['avatar_small'])}" /></a></div>
            <p>{$inviterInfo[$item['inviter']]['mobile']}</p>
            {/if}
          </td>
          <td>
           {$memberDs[$item['d1']]['name']}{$memberDs[$item['d2']]['name']}{$memberDs[$item['d3']]['name']}{$memberDs[$item['d4']]['name']}
          </td>
          <td>{$item['credits']}</td>
          <td>{$item['status']}</td>
          <td class="align-center">{if $item['allowtalk'] == 'N'}禁止{else}允许{/if}</td>
          <td class="align-center">{if $item['freeze'] == 'Y'}禁止{else}允许{/if}</td>
          <td class="align-center"><a href="{admin_site_url('member/edit')}/{$item['uid']}">编辑</a> | <a href="index.php?act=notice&op=notice&member_name=c2hvcG5j">通知</a></td>
        </tr>
      {/foreach}
      </tbody>
      <tfoot class="tfoot">
        <tr>
          <td colspan="11">
            {include file="common/pagination.tpl"}
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