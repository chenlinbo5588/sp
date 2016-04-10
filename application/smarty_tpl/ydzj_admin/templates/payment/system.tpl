{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>支付方式</h3>
      <ul class="tab-base"><li><a class="current"><span>支付方式</span></a></li></ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li>此处列出了系统支持的支付方式，点击编辑可以设置支付参数及开关状态</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <table class="table tb-type2">
    <thead>
      <tr class="thead">
        <th>支付方式</th>
        <th class="align-center">启用</th>
        <th class="align-center">操作</th>
      </tr>
    </thead>
    <tbody>
            <tr class="hover">
        <td>货到付款</td>
        <td class="w25pre align-center">
          开启中        </td>
        <td class="w156 align-center"><a href="index.php?act=payment&op=edit&payment_id=1">编辑</a></td>
      </tr>
            <tr class="hover">
        <td>支付宝</td>
        <td class="w25pre align-center">
          开启中        </td>
        <td class="w156 align-center"><a href="index.php?act=payment&op=edit&payment_id=2">编辑</a></td>
      </tr>
            <tr class="hover">
        <td>财付通</td>
        <td class="w25pre align-center">
          开启中        </td>
        <td class="w156 align-center"><a href="index.php?act=payment&op=edit&payment_id=3">编辑</a></td>
      </tr>
            <tr class="hover">
        <td>网银在线</td>
        <td class="w25pre align-center">
          开启中        </td>
        <td class="w156 align-center"><a href="index.php?act=payment&op=edit&payment_id=4">编辑</a></td>
      </tr>
            <tr class="hover">
        <td>预存款</td>
        <td class="w25pre align-center">
          开启中        </td>
        <td class="w156 align-center"><a href="index.php?act=payment&op=edit&payment_id=5">编辑</a></td>
      </tr>
          </tbody>
    <tfoot>
      <tr class="tfoot">
        <td colspan="15"></td>
      </tr>
    </tfoot>
  </table>
{include file="common/main_footer.tpl"}