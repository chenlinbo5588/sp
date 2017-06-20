{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>系统信息<!--上次登录的时间：2015-09-23 08:37:39--></h3>
    </div>
  </div>
  <div class="info-panel">
    
    {*
    <dl class="goods">
      <dt>
        <div class="ico"><i></i><sub title="货品总数"><span><em id="statistics_goods"></em></span></sub></div>
        <h3>货品</h3>
        <h5>新增货品</h5>
      </dt>
      <dd>
        <ul>
          <li class="w50pre normal"><a href="{admin_site_url('goods/index')}">本周新增<sub title=""><em id="statistics_week_add_product"></em></sub></a></li>
          <li class="w50pre none"><a href="index.php?act=inform&op=inform_list">举报<sub><em id="statistics_inform_list">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    
    <dl class="shop">
      <dt>
        <div class="ico"><i></i><sub title="新增场馆数"><span><em id="statistics_store"></em></span></sub></div>
        <h3>存量建筑</h3>
        <h5>新增审核</h5>
      </dt>
      <dd>
        <ul>
          <li class="w25pre normal"><a href="javascript:void(0);">已暂缓<sub><em id="statistics_store_expired">1200</em></sub></a></li>
          <li class="w25pre normal"><a href="javascript:void(0);">已补办<sub><em id="statistics_store_expire">5602</em></sub></a></li>
          <li class="w25pre normal"><a href="javascript:void(0);">已没收<sub><em id="statistics_store_expire">23</em></sub></a></li>
          <li class="w25pre normal"><a href="javascript:void(0);">已拆除<sub><em id="statistics_store_expire">450</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    
    <dl class="team">
      <dt>
        <div class="ico"><i></i></div>
        <h3>巴里村</h3>
        <h5>审核开通/圈内话题及举报</h5>
      </dt>
      <dd>
        <ul>
          <li class="w20pre normal"><a href="javascript:void(0);">已暂缓<sub><em>1200</em></sub></a></li>
          <li class="w20pre normal"><a href="javascript:void(0);">已补办<sub><em>5602</em></sub></a></li>
          <li class="w20pre normal"><a href="javascript:void(0);">已没收<sub><em>23</em></sub></a></li>
          <li class="w20pre normal"><a href="javascript:void(0);">已拆除<sub><em>450</em></sub></a></li>
          <li class="w20pre normal"><a href="javascript:void(0);">举报<sub><em>2</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    *}
    <dl class="member">
      <dt>
        <div class="ico"><i></i><sub title="操作员总数"><span><em id="statistics_member"></em></span></sub></div>
        <h3>操作员</h3>
        <h5>新增操作员</h5>
      </dt>
      <dd>
        <ul>
          <li class="w100pre normal"><a href="{admin_site_url('member/index')}">操作员<sub><em>0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    {*
    <dl class="trade">
      <dt>
        <div class="ico"><i></i><sub title="订单总数"><span><em id="statistics_order"></em></span></sub></div>
        <h3>交易</h3>
        <h5>交易订单及投诉/举报</h5>
      </dt>
      <dd>
        <ul>
          <li class="w25pre none"><a href="index.php?act=refund&op=refund_manage">退款<sub><em id="statistics_refund"></em></sub></a></li>
          <li class="w25pre none"><a href="index.php?act=return&op=return_manage">退货<sub><em id="statistics_return"></em></sub></a></li>
          <li class="w25pre none"><a href="index.php?act=complain&op=complain_new_list">投诉<sub><em id="statistics_complain_new_list">0</em></sub></a></li>
          <li class="w25pre none"><a href="index.php?act=complain&op=complain_handle_list">待仲裁<sub><em id="statistics_complain_handle_list">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    
    
    <dl class="operation">
      <dt>
        <div class="ico"><i></i></div>
        <h3>运营</h3>
        <h5>系统运营类设置及审核</h5>
      </dt>
      <dd>
        <ul>
          <li class="w25pre none"><a href="index.php?act=groupbuy&op=groupbuy_verify_list">团购<sub><em id="statistics_groupbuy_verify_list">0</em></sub></a></li>
          <li class="w25pre none"><a href="index.php?act=pointorder&op=pointorder_list">积分订单<sub><em id="statistics_points_order">0</em></sub></a></li>
          <li class="w25pre none"><a href="index.php?act=bill&op=show_statis&os_month=&query_store=&bill_state=2">账单审核<sub><em id="statistics_check_billno">0</em></sub></a></li>
          <li class="w25pre none"><a href="index.php?act=bill&op=show_statis&os_month=&query_store=&bill_state=3">账单支付<sub><em id="statistics_pay_billno">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    
    *}
    <dl class="cms">
      <dt>
        <div class="ico"><i></i></div>
        <h3>CMS</h3>
        <h5>资讯文章/图片画报/会员评论</h5>
      </dt>
      <dd>
        <ul>
          <li class="w100pre none"><a href="{admin_site_url('cms_article/index')}">文章审核<sub><em id="statistics_cms_article_verify">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    
    {*
    <dl class="weixin">
      <dt>
        <div class="ico"><i></i></div>
        <h3>微信</h3>
        <h5>关注成员/文章推送/回复管理</h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre none"><a href="javascript:void(0);">关注成员</a></li>
          <li class="w33pre none"><a href="javascript:void(0);">文章推送</a></li>
          <li class="w34pre none"><a href="javascript:void(0);">回复管理</a></li>
        </ul>
      </dd>
    </dl>
    *}
    
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