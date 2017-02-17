{include file="common/main_header.tpl"}
{config_load file="member.conf"}
  <form class="formSearch" method="get" name="formSearch" id="formSearch" action="{admin_site_url('member')}">
    <input type="hidden" name="page" value=""/>
    <table class="tb-type1 noborder search">
      <tbody>
        {*
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
        *}
        <tr>
          <td><select name="search_field_name" >
              {foreach from=$search_map['search_field'] key=key item=item}
              <option value="{$key}" {if $smarty.get['search_field_name'] == $key}selected{/if}>{$item}</option>
              {/foreach}
            </select>
          </td>
          <td><input type="text" value="{$smarty.get['search_field_value']}" name="search_field_value" class="txt"></td>
          {*
          <td><select name="register_channel">
              <option  value="">{#register_channel#}</option>
              {foreach from=$search_map['register_channel'] key=key item=item}
              <option  value="{$key}" {if $smarty.get['register_channel'] == $key}selected{/if}>{$item}</option>
              {/foreach}
            </select>
         </td>
         *}
          <td>
            <input type="submit" class="msbtn" name="tijiao" value="查询"/>
          </td>
        </tr>
        <tr>
            <td><label>{#register_time#}</label></td>
            <td><select name="reg_time">
              {foreach from=$mtime key=key item=item}
              <option  value="{$key}" {if $smarty.get['reg_time'] == $key}selected{/if}>{$key}</option>
              {/foreach}
            </select>
            </td>
            <td><label>{#avatar_state#}</label></td>
	          <td>
	            <label><input type="radio" name="avatar_status" {if $smarty.get.avatar_status ==''}checked{/if}  value="">不限</label>
	            <label><input type="radio" name="avatar_status" {if $smarty.get.avatar_status === 0}checked{/if} value="0">待审核</label>
	            <label><input type="radio" name="avatar_status" {if $smarty.get.avatar_status === 1}checked{/if}  value="1">已审核</label>
	          </td>
            
        </tr>
      </tbody>
    </table>
   </form>
   {include file="common/pagination.tpl"}
   <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>&nbsp;</th>
          <th>会员</th>
          <th>登陆账号</th>
          <th>手机号码</th>
          <th>邮箱</th>
          <th>QQ</th>
          <th>性别</th>
          <th class="align-center">邀请人</th>
          <th>最后登录</th>
          <th>注册信息</th>
          <th>积分</th>
          <th>用户组</th>
          <th class="align-center">登录</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      {foreach from=$list['data'] item=item key=key}
        <tr class="hover member">
          <td class="w24"></td>
          <td class="w120 picture">
            <div class=""><span class="thumb"><i></i><img src="{resource_url($item['avatar_s'])}"  data-avatar="{$item['avatar_m']}" /></span></div>
          </td>
          <td>{$item['username']|escape}</td>
          <td>{$item['mobile']|escape}</td>
          <td><div class="im"><span class="email">{if $item['email'] != ''}<a href="mailto:{$item['email']}" class="yes" title="电子邮箱:{$item['email']|escape}">{$item['email']}</a>{/if}</span></div><span>{$item['email']|escape}</span></td>
          <td><div class="im"><span class="qq">{if $item['qq'] != ''}<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={$item['qq']}&site=qq&menu=yes" class="yes" alt="点击这里给我发消息  QQ: {$item['qq']|escape}" title="点击这里给我发消息 QQ: {$item['qq']|escape}">{$item['qq']|escape}</a>{/if}</span></div><span>{$item['qq']|escape}</span></td>
          <td>{if $item['sex'] == 'M'}男{elseif $item['sex'] == 'F'}女{else}保密{/if}</td>
          <td class="align-center w120">
            {if $item['inviter']}
            <div class=""><a href="{admin_site_url('member/edit')}?id={$item['inviter']}"><img src="{resource_url($inviterInfo[$item['inviter']]['avatar_s'])}" /></a></div>
            <p>{$inviterInfo[$item['inviter']]['mobile']}</p>
            {/if}
          </td>
          <td>
            <p>{if $item['last_login']}{$item['last_login']|date_format:"%Y-%m-%d %H:%M:%S"}</p>
            <p>{$item['last_loginip']}{/if}</p>
          </td>
          <td>
            <div>{$item['reg_date']|date_format:"%Y-%m-%d %H:%M:%S"}</div>
            <div>{$item['reg_ip']}</div>
          </td>
          <td>{$item['credits']}</td>
          <td>{if $item['group_id'] != 2}认证{else}未认证{/if}会员</td>
          <td class="align-center">{if $item['freeze'] == 'Y'}<span class="tip_warning">禁止</span>{else}允许{/if}</td>
          <td class="align-center"><a href="{admin_site_url('member/edit')}?id={$item['uid']}">编辑</a> | <a href="{admin_site_url('notify/add?uid=')}{$item['uid']}">通知</a></td>
        </tr>
      {/foreach}
      </tbody>
    </table>
    {include file="common/pagination.tpl"}
{include file="common/main_footer.tpl"}