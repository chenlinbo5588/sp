{include file="common/main_header_navs.tpl"}
  {form_open(site_url($uri_string),'name="form1"')}
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="service_max_cnt">单次预约单最大预约人数:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="service_staff_maxcnt" name="service_staff_maxcnt" value="{$currentSetting['service_staff_maxcnt']['value']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips">{form_error('service_staff_maxcnt')}<span class="vatop rowform">小程序预约单中提交订单时最大的预约人数 0 表示不限制</span></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="service_prepay_amount">预约单预约金:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="service_prepay_amount" name="service_prepay_amount" value="{$currentSetting['service_prepay_amount']['value']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips">{form_error('service_prepay_amount')}<span class="vatop rowform">用于支付预约单的预约金额</span></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name_en">用户当日最大可预约订单数量:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="service_order_limit" name="service_order_limit" value="{$currentSetting['service_order_limit']['value']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips">{form_error('service_order_limit')}<span class="vatop rowform"> 0 表示不限制</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required">预约功能状态:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="service_booking_status1" {if $currentSetting['service_booking_status']['value'] == '开启'}class="cb-enable selected"{else}class="cb-enable"{/if} ><span>开启</span></label>
            <label for="service_booking_status0" {if $currentSetting['service_booking_status']['value'] == '开启'}class="cb-disable"{else}class="cb-disable selected"{/if} ><span>关闭</span></label>
            <input id="service_booking_status1" name="service_booking_status" {if $currentSetting['service_booking_status']['value'] == '开启'}checked="checked"{/if} value="开启" type="radio">
            <input id="service_booking_status0" name="service_booking_status" {if $currentSetting['service_booking_status']['value'] == '关闭'}checked="checked"{/if} value="关闭" type="radio"></td>
          <td class="vatop tips"><span class="vatop rowform">可暂时将预约功能关闭，其他人无法预约，但不影响小程序的服务功能的正常使用</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="closed_reason">关闭原因:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea name="service_closed_reason" rows="6" class="tarea" id="service_closed_reason" >{$currentSetting['service_closed_reason']['value']|escape}</textarea></td>
          <td class="vatop tips"><span class="vatop rowform">当处于关闭状态时，关闭原因将显示在前台</span></td>
        </tr>
      </tbody>
      <tfoot id="submit-holder">
        <tr class="tfoot">
          <td colspan="2" ><input type="submit" name="submit" value="保存" class="msbtn"></td>
        </tr>
      </tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}