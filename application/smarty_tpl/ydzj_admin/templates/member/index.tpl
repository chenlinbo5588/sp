{include file="common/main_header.tpl"}
{config_load file="member.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}{#manage#}</h3>
      <ul class="tab-base">
        <li><a href="javascript:void(0);" class="current"><span>{#manage#}</span></a></li>
        {*<li><a href="{admin_site_url('member/add')}" ><span>{#add#}</span></a></li>*}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form class="formSearch" method="get" name="formSearch" id="formSearch" action="{admin_site_url('member')}">
    <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td><label>用户名:</label></td>
          <td><input type="text" value="{$smarty.get['username']|escape}" name="username" class="txt"></td>
          <td><label>手机号:</label></td>
          <td><input type="text" value="{$smarty.get['mobile']|escape}" name="mobile" class="txt"></td>
          <td><label>注册来源域名:</label></td>
          <td><input type="text" value="{$smarty.get['reg_domain']|escape}" name="reg_domain" class="txt"></td>
          <td>
            <input type="submit" class="msbtn" name="tijiao" value="查询"/>
          </td>
        </tr>
        <tr>
          <td><label>点击渠道代码:</label></td>
          <td><input type="text" value="{$smarty.get['channel_code']|escape}" name="channel_code" class="txt"></td>
          <td><label>对应关键词:</label></td>
          <td><input type="text" value="{$smarty.get['channel_name']|escape}" name="channel_name" class="txt"></td>
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
            <li>通过{#title#}{#manage#}，你可以进行查看、编辑会员资料以及删除{#title#}等操作</li>
            <li>你可以根据条件搜索{#title#}，然后选择相应的操作</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
   <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>序号</th>
          <th>用户名称</th>
          <th>手机号码</th>
          <th>注册来源域名</th>
          <th>点击来源地址</th>
          <th>点击渠道代码</th>
          <th>对应关键词</th>
          <th>注册时间</th>
          <th>注册IP</th>
          <th>状态</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      {foreach from=$list['data'] item=item key=key}
        <tr class="hover member">
          <td>{$item['uid']}</td>
          <td>{if $item['username']}{$item['username']}{else}未填写{/if}</td>
          <td><strong>{$item['mobile']|escape}</strong></td>
          <td>{$item['reg_domain']|escape}</td>
          <td>{$item['reg_orig']|escape}</td>
          <td><strong>{$item['channel_code']|escape}</strong></td>
          <td>{$item['channel_name']|escape}</td>
          <td>{$item['reg_date']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td>{$item['reg_ip']}</td>
          <td>{if $item['status'] == 0}注册完成{else}注册未提交{/if}</td>
          <td class="align-center">
          	<a href="{admin_site_url('member/edit')}?uid={$item['uid']}">编辑</a> | 
          	<a href="{admin_site_url('member/detail')}?uid={$item['uid']}">详情</a> 
          	{*<a class="delete" href="javascript:void(0);" data-id="{$item['uid']}" data-url="{admin_site_url('member/delete')}?uid={$item['uid']}">删除</a>*}</td>
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
    
    
{include file="common/main_footer.tpl"}