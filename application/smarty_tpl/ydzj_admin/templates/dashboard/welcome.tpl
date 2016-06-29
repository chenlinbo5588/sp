{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>系统信息<!--上次登录的时间：2015-09-23 08:37:39--></h3>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="info-panel">
    <dl class="member">
      <dt>
        <div class="ico"><i></i><sub title="会员总数"><span><em id="statistics_member"></em></span></sub></div>
        <h3>会员</h3>
        <h5>新增会员</h5>
      </dt>
      <dd>
        <ul>
          <li class="w100pre normal"><a href="{admin_site_url('member/index')}">本周新增<sub><em id="statistics_week_add_member"></em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <dl class="system">
      <dt>
        <div class="ico"><i></i><a id="UPDATE" style="visibility:hidden;" title="" target="_blank" href="javascript:void(0);"><sub><span>new</em></span></sub></a></div>
        <h3></h3>
        <div id="system-info">
          <ul>
            <li>{config_item('site_name')} <span>201401162490</span></li>
            <li><span>{$smarty.now|date_format:"%Y-%m-%d"}</span></li>
          </ul>
        </div>
      </dt>
      <dd>
        <ul>
          <li class="w100pre none"><a href="{site_url('index')}" target="_blank">官方网站<sub></sub></a></li>
        </ul>
      </dd>
    </dl>
    <div class="clear"></div>
  </div>
{include file="common/main_footer.tpl"}