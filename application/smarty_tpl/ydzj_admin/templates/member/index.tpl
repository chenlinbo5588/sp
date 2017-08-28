{include file="common/main_header.tpl"}
{config_load file="member.conf"}
  {form_open(admin_site_url('member/index'),'class="formSearch" name="formSearch" id="formSearch"')}
    <input type="hidden" name="page" value=""/>
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
          <th>会员</th>
          <th>登陆账号</th>
          <th>手机号码【虚拟号码】</th>
          <th>邮箱</th>
          <th>QQ</th>
          <th>性别</th>
          <th>最后登录</th>
          <th>注册信息</th>
          <th class="align-center">登录</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      {foreach from=$list['data'] item=item key=key}
        <tr class="hover member">
          <td class="w24"></td>
          <td class="w120 picture">
            <div class=""><span class="thumb"><i></i><img src="{if $item['avatar_s']}{resource_url($item['avatar_s'])}{else}{resource_url($siteSetting['default_user_portrait'])}{/if}"  data-avatar="{$item['avatar_m']}" /></span></div>
          </td>
          <td>{$item['username']|escape}</td>
          <td>{$item['mobile']|escape}【{if $item['virtual_no']}{$item['virtual_no']}{else}无{/if}】</td>
          <td><div class="im"><span class="email">{if $item['email'] != ''}<a href="mailto:{$item['email']}" class="yes" title="电子邮箱:{$item['email']|escape}">{$item['email']}</a>{/if}</span></div><span>{$item['email']|escape}</span></td>
          <td><div class="im"><span class="qq">{if $item['qq'] != ''}<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={$item['qq']}&site=qq&menu=yes" class="yes" alt="点击这里给我发消息  QQ: {$item['qq']|escape}" title="点击这里给我发消息 QQ: {$item['qq']|escape}">{$item['qq']|escape}</a>{/if}</span></div><span>{$item['qq']|escape}</span></td>
          <td>{if $item['sex'] == 'M'}男{elseif $item['sex'] == 'F'}女{else}保密{/if}</td>
          <td>
            <p>{if $item['last_login']}{$item['last_login']|date_format:"%Y-%m-%d %H:%M:%S"}</p>
            <p>{$item['last_loginip']}{/if}</p>
          </td>
          <td>
            <div>{$item['reg_date']|date_format:"%Y-%m-%d %H:%M:%S"}</div>
            <div>{$item['reg_ip']}</div>
          </td>
          <td class="align-center">{if $item['freeze'] == 'Y'}<span class="tip_warning">禁止</span>{else}允许{/if}</td>
          <td class="align-center"><a href="{admin_site_url('member/edit')}?id={$item['uid']}">编辑</a> | <a href="{admin_site_url('notify/add?uid=')}{$item['uid']}">通知</a></td>
        </tr>
      {/foreach}
      </tbody>
    </table>
    {include file="common/pagination.tpl"}
{include file="common/main_footer.tpl"}