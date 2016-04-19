{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>添加角色</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('authority/role')}" ><span>角色管理</span></a></li>
      	<li><a  class="current"><span>添加角色</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="add_form" method="post">
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="admin_name">权限组:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="gname" maxlength="40" name="gname" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2"><table class="table tb-type2 nomargin">
              <thead>
                <tr class="space">
                  <th> <input id="limitAll" id="limitAll" value="1" type="checkbox">&nbsp;&nbsp;设置权限</th>
                </tr>
              </thead>
              <tbody>
              	<tr>
                  <td>
                  <label style="width:100px">&nbsp;</label>
                  <input id="limit0" type="checkbox" onclick="selectLimit('limit0')">
                    <label for="limit0"><b>设置</b>&nbsp;&nbsp;</label>
                                              <label><input nctype='limit' class="limit0" type="checkbox" name="permission[]" value="setting">
                        站点设置&nbsp;</label>
                                              <label><input nctype='limit' class="limit0" type="checkbox" name="permission[]" value="account">
                        账号同步&nbsp;</label>
                                              <label><input nctype='limit' class="limit0" type="checkbox" name="permission[]" value="upload">
                        上传设置&nbsp;</label>
                                              <label><input nctype='limit' class="limit0" type="checkbox" name="permission[]" value="setting.seo">
                        SEO设置&nbsp;</label>
                                              <label><input nctype='limit' class="limit0" type="checkbox" name="permission[]" value="payment">
                        支付方式&nbsp;</label>
                                              <label><input nctype='limit' class="limit0" type="checkbox" name="permission[]" value="message">
                        消息通知&nbsp;</label>
                                              <label><input nctype='limit' class="limit0" type="checkbox" name="permission[]" value="express">
                        快递公司&nbsp;</label>
                                              <label><input nctype='limit' class="limit0" type="checkbox" name="permission[]" value="offpay_area">
                        配送地区&nbsp;</label>
                                              <label><input nctype='limit' class="limit0" type="checkbox" name="permission[]" value="cache">
                        清理缓存&nbsp;</label>
                                              <label><input nctype='limit' class="limit0" type="checkbox" name="permission[]" value="perform">
                        性能优化&nbsp;</label>
                                              <label><input nctype='limit' class="limit0" type="checkbox" name="permission[]" value="search">
                        搜索设置&nbsp;</label>
                                              <label><input nctype='limit' class="limit0" type="checkbox" name="permission[]" value="admin_log">
                        操作日志&nbsp;</label>
                                          </td>
                </tr>
                                <tr>
                  <td>
                  <label style="width:100px">&nbsp;</label>
                  <input id="limit1" type="checkbox" onclick="selectLimit('limit1')">
                    <label for="limit1"><b>商品</b>&nbsp;&nbsp;</label>
                                              <label><input nctype='limit' class="limit1" type="checkbox" name="permission[]" value="goods">
                        商品管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit1" type="checkbox" name="permission[]" value="goods_class">
                        分类管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit1" type="checkbox" name="permission[]" value="brand">
                        品牌管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit1" type="checkbox" name="permission[]" value="type">
                        类型管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit1" type="checkbox" name="permission[]" value="spec">
                        规格管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit1" type="checkbox" name="permission[]" value="goods_album">
                        图片空间&nbsp;</label>
                                          </td>
                </tr>
                                <tr>
                  <td>
                  <label style="width:100px">&nbsp;</label>
                  <input id="limit2" type="checkbox" onclick="selectLimit('limit2')">
                    <label for="limit2"><b>店铺</b>&nbsp;&nbsp;</label>
                                              <label><input nctype='limit' class="limit2" type="checkbox" name="permission[]" value="store">
                        店铺管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit2" type="checkbox" name="permission[]" value="store_grade">
                        店铺等级&nbsp;</label>
                                              <label><input nctype='limit' class="limit2" type="checkbox" name="permission[]" value="store_class">
                        店铺分类&nbsp;</label>
                                              <label><input nctype='limit' class="limit2" type="checkbox" name="permission[]" value="domain">
                        二级域名&nbsp;</label>
                                              <label><input nctype='limit' class="limit2" type="checkbox" name="permission[]" value="sns_strace">
                        店铺动态&nbsp;</label>
                                          </td>
                </tr>
                                <tr>
                  <td>
                  <label style="width:100px">&nbsp;</label>
                  <input id="limit3" type="checkbox" onclick="selectLimit('limit3')">
                    <label for="limit3"><b>会员</b>&nbsp;&nbsp;</label>
                                              <label><input nctype='limit' class="limit3" type="checkbox" name="permission[]" value="member">
                        会员管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit3" type="checkbox" name="permission[]" value="notice">
                        会员通知&nbsp;</label>
                                              <label><input nctype='limit' class="limit3" type="checkbox" name="permission[]" value="points">
                        积分管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit3" type="checkbox" name="permission[]" value="sns_sharesetting">
                        分享绑定&nbsp;</label>
                                              <label><input nctype='limit' class="limit3" type="checkbox" name="permission[]" value="sns_malbum">
                        会员相册&nbsp;</label>
                                              <label><input nctype='limit' class="limit3" type="checkbox" name="permission[]" value="snstrace">
                        买家动态&nbsp;</label>
                                              <label><input nctype='limit' class="limit3" type="checkbox" name="permission[]" value="sns_member">
                        会员标签&nbsp;</label>
                                              <label><input nctype='limit' class="limit3" type="checkbox" name="permission[]" value="predeposit">
                        预存款&nbsp;</label>
                                          </td>
                </tr>
                                <tr>
                  <td>
                  <label style="width:100px">&nbsp;</label>
                  <input id="limit4" type="checkbox" onclick="selectLimit('limit4')">
                    <label for="limit4"><b>交易</b>&nbsp;&nbsp;</label>
                                              <label><input nctype='limit' class="limit4" type="checkbox" name="permission[]" value="order">
                        订单管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit4" type="checkbox" name="permission[]" value="refund">
                        退款管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit4" type="checkbox" name="permission[]" value="return">
                        退货管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit4" type="checkbox" name="permission[]" value="consulting">
                        咨询管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit4" type="checkbox" name="permission[]" value="inform">
                        举报管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit4" type="checkbox" name="permission[]" value="evaluate">
                        评价管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit4" type="checkbox" name="permission[]" value="complain">
                        投诉管理&nbsp;</label>
                                          </td>
                </tr>
                                <tr>
                  <td>
                  <label style="width:100px">&nbsp;</label>
                  <input id="limit5" type="checkbox" onclick="selectLimit('limit5')">
                    <label for="limit5"><b>网站</b>&nbsp;&nbsp;</label>
                                              <label><input nctype='limit' class="limit5" type="checkbox" name="permission[]" value="article_class">
                        文章分类&nbsp;</label>
                                              <label><input nctype='limit' class="limit5" type="checkbox" name="permission[]" value="article">
                        文章管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit5" type="checkbox" name="permission[]" value="document">
                        系统文章&nbsp;</label>
                                              <label><input nctype='limit' class="limit5" type="checkbox" name="permission[]" value="navigation">
                        页面导航&nbsp;</label>
                                              <label><input nctype='limit' class="limit5" type="checkbox" name="permission[]" value="adv">
                        广告管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit5" type="checkbox" name="permission[]" value="web_config|web_api">
                        首页管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit5" type="checkbox" name="permission[]" value="rec_position">
                        推荐位&nbsp;</label>
                                          </td>
                </tr>
                                <tr>
                  <td>
                  <label style="width:100px">&nbsp;</label>
                  <input id="limit6" type="checkbox" onclick="selectLimit('limit6')">
                    <label for="limit6"><b>运营</b>&nbsp;&nbsp;</label>
                                              <label><input nctype='limit' class="limit6" type="checkbox" name="permission[]" value="operation">
                        基本设置&nbsp;</label>
                                              <label><input nctype='limit' class="limit6" type="checkbox" name="permission[]" value="groupbuy">
                        团购管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit6" type="checkbox" name="permission[]" value="activity">
                        活动管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit6" type="checkbox" name="permission[]" value="promotion_xianshi">
                        限时折扣&nbsp;</label>
                                              <label><input nctype='limit' class="limit6" type="checkbox" name="permission[]" value="promotion_mansong">
                        满即送&nbsp;</label>
                                              <label><input nctype='limit' class="limit6" type="checkbox" name="permission[]" value="promotion_bundling">
                        优惠套装&nbsp;</label>
                                              <label><input nctype='limit' class="limit6" type="checkbox" name="permission[]" value="promotion_bundling">
                        推荐展位&nbsp;</label>
                                              <label><input nctype='limit' class="limit6" type="checkbox" name="permission[]" value="pointprod|pointorder">
                        兑换礼品&nbsp;</label>
                                              <label><input nctype='limit' class="limit6" type="checkbox" name="permission[]" value="voucher">
                        代金券&nbsp;</label>
                                              <label><input nctype='limit' class="limit6" type="checkbox" name="permission[]" value="bill">
                        结算管理&nbsp;</label>
                                          </td>
                </tr>
                                <tr>
                  <td>
                  <label style="width:100px">&nbsp;</label>
                  <input id="limit7" type="checkbox" onclick="selectLimit('limit7')">
                    <label for="limit7"><b>统计</b>&nbsp;&nbsp;</label>
                                              <label><input nctype='limit' class="limit7" type="checkbox" name="permission[]" value="stat_member">
                        会员统计&nbsp;</label>
                                              <label><input nctype='limit' class="limit7" type="checkbox" name="permission[]" value="stat_store">
                        店铺统计&nbsp;</label>
                                              <label><input nctype='limit' class="limit7" type="checkbox" name="permission[]" value="stat_trade">
                        销量分析&nbsp;</label>
                                              <label><input nctype='limit' class="limit7" type="checkbox" name="permission[]" value="stat_marketing">
                        营销分析&nbsp;</label>
                                              <label><input nctype='limit' class="limit7" type="checkbox" name="permission[]" value="stat_aftersale">
                        售后分析&nbsp;</label>
                                          </td>
                </tr>
                                <tr>
                  <td>
                  <label style="width:100px">&nbsp;</label>
                  <input id="limit8" type="checkbox" onclick="selectLimit('limit8')">
                    <label for="limit8"><b>微商城</b>&nbsp;&nbsp;</label>
                                              <label><input nctype='limit' class="limit8" type="checkbox" name="permission[]" value="microshop.manage">
                        微商城管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit8" type="checkbox" name="permission[]" value="microshop.goods|microshop.goods_manage">
                        随心看管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit8" type="checkbox" name="permission[]" value="microshop.goodsclass|microshop.goodsclass_list">
                        随心看分类&nbsp;</label>
                                              <label><input nctype='limit' class="limit8" type="checkbox" name="permission[]" value="microshop.personal|microshop.personal_manage">
                        个人秀管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit8" type="checkbox" name="permission[]" value="microshop.personalclass|microshop.personalclass_list">
                        个人秀分类&nbsp;</label>
                                              <label><input nctype='limit' class="limit8" type="checkbox" name="permission[]" value="microshop.store|microshop.store_manage">
                        店铺街管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit8" type="checkbox" name="permission[]" value="microshop.comment|microshop.comment_manage">
                        评论管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit8" type="checkbox" name="permission[]" value="microshop.adv|microshop.adv_manage">
                        广告管理&nbsp;</label>
                                          </td>
                </tr>
                                <tr>
                  <td>
                  <label style="width:100px">&nbsp;</label>
                  <input id="limit9" type="checkbox" onclick="selectLimit('limit9')">
                    <label for="limit9"><b>CMS</b>&nbsp;&nbsp;</label>
                                              <label><input nctype='limit' class="limit9" type="checkbox" name="permission[]" value="cms_manage">
                        CMS管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit9" type="checkbox" name="permission[]" value="cms_index">
                        首页管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit9" type="checkbox" name="permission[]" value="cms_article|cms_article_class">
                        文章管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit9" type="checkbox" name="permission[]" value="cms_picture|cms_picture_class">
                        画报管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit9" type="checkbox" name="permission[]" value="cms_special">
                        专题管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit9" type="checkbox" name="permission[]" value="cms_navigation">
                        导航管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit9" type="checkbox" name="permission[]" value="cms_tag">
                        标签管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit9" type="checkbox" name="permission[]" value="cms_comment">
                        评论管理&nbsp;</label>
                                          </td>
                </tr>
                                <tr>
                  <td>
                  <label style="width:100px">&nbsp;</label>
                  <input id="limit10" type="checkbox" onclick="selectLimit('limit10')">
                    <label for="limit10"><b>圈子</b>&nbsp;&nbsp;</label>
                                              <label><input nctype='limit' class="limit10" type="checkbox" name="permission[]" value="circle_setting.index">
                        圈子设置&nbsp;</label>
                                              <label><input nctype='limit' class="limit10" type="checkbox" name="permission[]" value="circle_memberlevel.index">
                        成员头衔设置&nbsp;</label>
                                              <label><input nctype='limit' class="limit10" type="checkbox" name="permission[]" value="circle_class">
                        圈子分类管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit10" type="checkbox" name="permission[]" value="circle_manage">
                        圈子管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit10" type="checkbox" name="permission[]" value="circle_theme">
                        圈子话题管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit10" type="checkbox" name="permission[]" value="circle_member">
                        圈子成员管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit10" type="checkbox" name="permission[]" value="circle_inform">
                        圈子举报管理&nbsp;</label>
                                              <label><input nctype='limit' class="limit10" type="checkbox" name="permission[]" value="circle_setting.adv_manage">
                        圈子首页广告&nbsp;</label>
                                          </td>
                </tr>
                              </tbody>
            </table></td>
        </tr>
      </tbody>
    
  </form>
<script>
function selectLimit(name){
    if($('#'+name).attr('checked')) {
        $('.'+name).attr('checked',true);
    }else {
        $('.'+name).attr('checked',false);
    }
}
$(document).ready(function(){
	//按钮先执行验证再提交表单
	$("#submitBtn").click(function(){
	    if($("#add_form").valid()){
	     $("#add_form").submit();
		}
	});

	$('#limitAll').click(function(){
		$('input[type="checkbox"]').attr('checked',$(this).attr('checked') == 'checked');
	});
	
	$("#add_form").validate({
		errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            gname : {
                required : true,
				remote	: {
                    url :'index.php?act=admin&op=ajax&branch=check_gadmin_name',
                    type:'get',
                    data:{
                    	gname : function(){
                            return $('#gname').val();
                        }
                    }
                }
            }
        },
        messages : {
            gname : {
                required : '请输入',
                remote	 : '该名称已存在'
            }
        }
	});
});
</script>
{include file="common/main_footer.tpl"}