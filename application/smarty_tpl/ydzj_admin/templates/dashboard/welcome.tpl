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
          <li class="w100pre normal"><a href="index.php?act=member&op=member">本周新增<sub><em id="statistics_week_add_member"></em></sub></a></li>
        </ul>
      </dd>
    </dl>
    {*
    <dl class="shop">
      <dt>
        <div class="ico"><i></i><sub title="新增场馆数"><span><em id="statistics_store"></em></span></sub></div>
        <h3>场馆</h3>
        <h5>新场馆审核</h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre none"><a href="index.php?act=store&op=store_joinin">场馆审核<sub><em id="statistics_store_joinin">0</em></sub></a></li>
          <li class="w33pre none"><a href="index.php?act=store&op=store&store_type=expired">已到期<sub><em id="statistics_store_expired">0</em></sub></a></li>
          <li class="w34pre none"><a href="index.php?act=store&op=store&store_type=expire">即将到期<sub><em id="statistics_store_expire">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <dl class="goods">
      <dt>
        <div class="ico"><i></i><sub title="商品总数"><span><em id="statistics_goods"></em></span></sub></div>
        <h3>商品</h3>
        <h5>新增商品/品牌申请审核</h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre normal"><a href="index.php?act=goods&op=goods">本周新增<sub title=""><em id="statistics_week_add_product"></em></sub></a></li>
          <li class="w33pre none"><a href="http://www.nzbestprice.com/admin/index.php?act=goods&op=goods&type=waitverify&search_verify=10">商品审核<sub><em id="statistics_product_verify">0</em></sub></a></li>
          <li class="w33pre none"><a href="index.php?act=inform&op=inform_list">举报<sub><em id="statistics_inform_list">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <dl class="team">
      <dt>
        <div class="ico"><i></i></div>
        <h3>队伍</h3>
        <h5>审核开通/圈内话题及举报</h5>
      </dt>
      <dd>
        <ul>
          <li class="w50pre none"><a href="http://www.nzbestprice.com/admin/index.php?act=circle_manage&op=circle_verify">队伍审核<sub><em id="statistics_circle_verify">0</em></sub></a></li>
          <li class="w50pre none"><a href="http://www.nzbestprice.com/admin/index.php?act=circle_inform&op=inform_list">举报</a></li>
        </ul>
      </dd>
    </dl>
    
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
        <dl class="cms">
      <dt>
        <div class="ico"><i></i></div>
        <h3>CMS</h3>
        <h5>资讯文章/图片画报/会员评论</h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre none"><a href="http://www.nzbestprice.com/admin/index.php?act=cms_article&op=cms_article_list_verify">文章审核<sub><em id="statistics_cms_article_verify">0</em></sub></a></li>
          <li class="w33pre none"><a href="http://www.nzbestprice.com/admin/index.php?act=cms_picture&op=cms_picture_list_verify">画报审核<sub><em id="statistics_cms_picture_verify">0</em></sub></a></li>
          <li class="w34pre none"><a href="http://www.nzbestprice.com/admin/index.php?act=cms_comment&op=comment_manage">评论<sub></sub></a></li>
        </ul>
      </dd>
    </dl>
    *}
    <dl class="weixin">
      <dt>
        <div class="ico"><i></i></div>
        <h3>微信营销</h3>
        <h5>关注成员/文章推送/回复管理</h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre none"><a href="http://www.nzbestprice.com/admin/index.php?act=microshop&op=goods_manage">关注成员</a></li>
          <li class="w33pre none"><a href="http://www.nzbestprice.com/admin/index.php?act=circle_theme&op=theme_list">文章推送</a></li>
          <li class="w34pre none"><a href="http://www.nzbestprice.com/admin/index.php?act=circle_inform&op=inform_list">回复管理</a></li>
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