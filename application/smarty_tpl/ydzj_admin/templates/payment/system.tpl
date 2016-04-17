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
      {foreach from=$paymentList item=item}
      <tr class="hover">
        <td>{$item['payment_name']|escape}</td>
        <td class="w25pre align-center">{if $item['payment_state'] == 1}已开启{else}已关闭{/if}</td>
        <td class="w156 align-center"><a href="{admin_site_url('payment/edit')}?payment_code={$item['payment_code']}">编辑</a></td>
      </tr>
      {/foreach}
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td colspan="15"></td>
      </tr>
    </tfoot>
  </table>
{include file="common/main_footer.tpl"}