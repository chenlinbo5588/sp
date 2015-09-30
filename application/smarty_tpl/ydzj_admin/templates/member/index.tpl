{include file="common/main_header.tpl"}
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>会员管理</h3>
      <ul class="tab-base">
        <li><a href="javascript:void(0);" class="current"><span>管理</span></a></li>
        <li><a href="{admin_site_url('member/add')}" ><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch" action="{admin_site_url('member')}">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td><select name="search_field_name" >
              <option  value="member_name">会员</option>
              <option  value="member_email">电子邮箱</option>
              <option  value="member_truename">真实姓名</option>
            </select></td>
          <td><input type="text" value="" name="search_field_value" class="txt"></td>
          <td><select name="search_sort" >
              <option value="">排序</option>
              <option  value="member_login_time desc">最后登录</option>
              <option  value="member_login_num desc">登录次数</option>
            </select></td>
          <td><select name="search_state" >
              <option selected='selected' value="">会员状态</option>
              <option  value="no_informallow">禁止举报</option>
              <option  value="no_isbuy">禁止购买</option>
              <option  value="no_isallowtalk">禁止发表言论</option>
              <option  value="no_memberstate">禁止登录</option>
            </select></td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="查询">&nbsp;</a>
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
  <form method="post" id="form_member">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>&nbsp;</th>
          <th colspan="2">会员</th>
          <th class="align-center"><span fieldname="logins" nc_type="order_by">登录次数</span></th>
          <th class="align-center"><span fieldname="last_login" nc_type="order_by">最后登录</span></th>
          <th class="align-center">地区</th>
          <th class="align-center">积分</th>
          <th class="align-center">登录</th>
          <th class="align-center">操作</th>
        </tr>
      <tbody>
      {foreach from=$list['data'] item=item key=key}
        <tr class="hover member">
          <td class="w24"></td>
          <td class="w120 picture">
            <div class=""><span class="thumb"><i></i><img src="{base_url($item['avatar_small'])}"  data-avatar="{$item['avatar_middle']}" /></span></div></td>
          <td>
            <p class="name"><strong>{$item['nickname']|escape}</strong>(真实姓名: {$item['username']|escape})</p>
            <p class="smallfont">注册时间:&nbsp;{$item['regdate']|date_format:"%Y-%m-%d %H:%M:%S"}</p>
            <p class="smallfont">注册IP:&nbsp;{$item['regip']}</p>
            <div class="im">
                {if $item['email'] != ''}
                <span class="email">
                    <a href="mailto:{$item['email']}" class="yes" title="电子邮箱:{$item['email']}">{$item['email']}</a>
                </span>
                {/if}
                
                {if $item['qq'] != ''}
                <span class="qq">
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={$item['qq']}&site=qq&menu=yes" class=""  title="QQ: {$item['qq']}"><img border="0" src="http://wpa.qq.com/pa?p=2:{$item['qq']}:52"/></a>
                </span>
                {/if}
                
                {if $item['weixin'] != ''}
                <span class="weixin">
                    
                </span>
                {/if}
            </div>
            
          </td>
          <td class="align-center">{$item['login_times']}</td>
          <td class="w150 align-center">
            <p>{$item['last_login']}</p>
            <p>{$item['last_loginip']}</p>
          </td>
          <td class="align-center">
            {*
            <p>可用:&nbsp;<strong class="red">0.00</strong>&nbsp;元</p>
            <p>冻结:&nbsp;<strong class="red">0.00</strong>&nbsp;元</p>
            *}
          </td>
          <td class="align-center">{$item['credits']}</td>
          
          <td class="align-center">允许</td>
          <td class="align-center"><a href="index.php?act=member&op=member_edit&member_id=1">编辑</a> | <a href="index.php?act=notice&op=notice&member_name=c2hvcG5j">通知</a></td>
        </tr>
      {/foreach}
      </tbody>
      <tfoot class="tfoot">
        <tr>
          <td colspan="16">
            <div class="pagination"> <ul><li><span>首页</span></li><li><span>上一页</span></li><li><span class="currentpage">1</span></li><li><span>下一页</span></li><li><span>末页</span></li></ul> </div></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(function(){
    $('#ncsubmit').click(function(){
        $('input[name="op"]').val('member');
        $('#formSearch').submit();
    }); 
});
</script>
{include file="common/main_footer.tpl"}