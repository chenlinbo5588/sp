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
          <td colspan="2" class="required">财付通商户号: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <input name="tenpay_account" id="tenpay_account" value="{$info['payment_config']['tenpay_account']}" class="txt" type="text"></td>
          <td class="vatop tips">{form_error('tenpay_account')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required">财付通密钥: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input name="tenpay_key" id="tenpay_key" value="{$info['payment_config']['tenpay_key']}" class="txt" type="text"></td>
          <td class="vatop tips">{form_error('tenpay_key')}</td>
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
<script>
$(document).ready(function(){
	$('form[name=form1]').validate({
		rules : {
            tenpay_account : {
                required   : true
            },
            tenpay_key : {
                required   : true
            }
        },
        messages : {
            tenpay_account  : {
                required  : '财付通商户号不能为空'
            },
            tenpay_key  : {
                required   : '财付通密钥不能为空'
            }
        }
			
	});
});
</script>
{include file="common/main_footer.tpl"}