{include file="common/main_header_navs.tpl"}
{config_load file="member.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
    <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
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
          *}
          <td>
            <input type="submit" class="msbtn" name="tijiao" value="查询"/>
          </td>
        </tr>
      </tbody>
    </table>
   <table class="table tb-type2 nobdb mgbottom">
      <thead>
        <tr class="thead">
          <th>&nbsp;</th>
          <th>{#avatar#}</th>
          <th>{#mobile#}</th>
          <th>{#nickname#}</th>
          <th>{#username#}</th>
          <th>{#sex#}</th>
          <th>{#register_time#}</th>
          <th>{#register_ip#}</th>
          <th>{#last_login#}</th>
          <th>{#last_loginip#}</th>
          <th>{#email#}</th>
          <th>{#qq#}</th>
          <th class="align-center">邀请人</th>
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
            <div class=""><span class="thumb"><i></i>{if $item['avatar_small']}<img src="{base_url($item['avatar_small'])}"  data-avatar="{$item['avatar_middle']}" />{else}暂无头像{/if}</span></div>
          </td>
          <td>{$item['mobile']|escape}</td>
          <td>{$item['nickname']|escape}</td>
          <td>{$item['username']|escape}</td>
          <td>{if 0 == $item['sex']}不祥{elseif 1 == $item['sex']}男{elseif 2 == $item['sex']}女{/if}</td>
          <td>{$item['reg_date']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td>{$item['reg_ip']}</td>
          <td>{if $item['last_login']}{$item['last_login']|date_format:"%Y-%m-%d %H:%M:%S"}{/if}</td>
          <td>{$item['last_loginip']}</td>
          <td>
          	{if $item['email'] != ''}
            <span class="email">
                <a href="mailto:{$item['email']}" class="yes" title="电子邮箱:{$item['email']|escape}">{$item['email']}</a>
            </span>
            {/if}
          </td>
          <td>
         	{if $item['qq'] != ''}
            <span class="qq">
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={$item['qq']}&site=qq&menu=yes" class="yes"  title="QQ: {$item['qq']|escape}">&nbsp;</a>
            </span>
            {/if}
          </td>
          <td class="align-center w120">
            {if $item['inviter']}
            <div class=""><a href="{admin_site_url('member/edit?id=')}{$item['inviter']}"><img src="{resource_url($inviterInfo[$item['inviter']]['avatar_small'])}" /></a></div>
            <p>{$inviterInfo[$item['inviter']]['mobile']}</p>
            {/if}
          </td>
          <td>{$item['credits']}</td>
          <td>{if 0 == $item['status']}正常{/if}</td>
          <td class="align-center">{if $item['allowtalk'] == 'N'}禁止{else}允许{/if}</td>
          <td class="align-center">{if $item['freeze'] == 'Y'}禁止{else}允许{/if}</td>
          <td class="align-center"><a href="{admin_site_url('member/edit?id=')}{$item['uid']}">编辑</a> | <a href="{admin_site_url('notify/add?uid=')}{$item['uid']}">通知</a></td>
        </tr>
      {/foreach}
      </tbody>
    </table>
    <div class="fixedOpBar">
    	{include file="common/pagination.tpl"}
    </div>
    </form>
{include file="common/main_footer.tpl"}