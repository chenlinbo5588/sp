{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>支付方式</h3>
      <ul class="tab-base"><li><a class="current"><span>支付方式</span></a></li>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {form_open(admin_site_url('payment/edit'),'name="form1"')}
    <input type="hidden" name="payment_code" value="{$info['payment_code']}" />
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td class="vatop rowform">{$info['payment_name']}</td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required">支付宝账号: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <input name="alipay_account" id="alipay_account" value="{$info['payment_config']['alipay_account']}" class="txt" type="text"></td>
          <td class="vatop tips">{form_error('alipay_account')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required">交易安全校验码（key）: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input name="alipay_key" id="alipay_key" value="{$info['payment_config']['alipay_key']}" class="txt" type="text"></td>
          <td class="vatop tips">{form_error('alipay_key')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required">合作者身份（partner ID）: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input name="alipay_partner" id="alipay_partner" value="{$info['payment_config']['alipay_partner']}" class="txt" type="text"></td>
          <td class="vatop tips">{form_error('alipay_partner')}<a href="https://app.alipay.com/market/productIndex.htm" target="_blank">get my key and partner ID</a></td>
        </tr>
                <tr>
          <td colspan="2" class="required">启用: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="payment_state1" {if $info['payment_state'] == '1'}class="cb-enable selected"{else}class="cb-enable"{/if} ><span>是</span></label>
            <label for="payment_state2" {if $info['payment_state'] == '1'}class="cb-disable"{else}class="cb-disable selected"{/if} ><span>否</span></label>
            <input type="radio" {if $info['payment_state'] == '1'}checked="checked"{/if} value="1" name="payment_state" id="payment_state1">
            <input type="radio" {if $info['payment_state'] == '0'}checked="checked"{/if} value="0" name="payment_state" id="payment_state2"></td>
          <td class="vatop tips">{form_error('payment_state')}</td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"> <a href="{admin_site_url('payment/system')}" class="btn"><span>返回</span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>

{include file="common/main_footer.tpl"}