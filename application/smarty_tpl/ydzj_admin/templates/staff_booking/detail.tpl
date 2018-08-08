{include file="common/main_header_navs.tpl"}
  {config_load file="worker.conf"}
  {if $info['id']}
  {form_open(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open(site_url($uri_string),'id="infoform"')}
  {/if}
    <table class="table tb-type2 mgbottom">
      <tbody>
		<tr class="noborder">
          <td colspan="2" class="required">{#username#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" readonly value="{$info['username']|escape}" name="username" id="username" class="txt"></td>
          <td class="vatop tips">{form_error('username')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#workaddress#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" readonly value="{$info['address']|escape}" name="address" id="address" class="txt"></td>
          <td class="vatop tips">{form_error('address')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#meet_time#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" readonly value="{$info['meet_time']|date_format:"%Y-%m-%d %H:%M"}" name="meet_time" id="meet_time" class="txt"></td>
          <td class="vatop tips">{form_error('meet_time')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">客户{#mobile#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" readonly value="{$info['mobile']|escape}" name="mobile" id="mobile" class="txt"></td>
          <td class="vatop tips">{form_error('mobile')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#staff_name#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text"  value="{$info['staff_name']|escape}" name="staff_name" id="staff_name" class="txt"></td>
          <td class="vatop tips">{form_error('staff_name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#staff_mobile#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text"  value="{$info['staff_mobile']|escape}" name="staff_mobile" id="staff_mobile" class="txt"></td>
          <td class="vatop tips">{form_error('staff_mobile')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#staff_sex#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" 	 value="{if $info['staff_sex'] == 1}男{else if $info['staff_sex'] == 2}女{/if}" name="staff_sex" id="staff_sex" class="txt"></td>
          <td class="vatop tips">{form_error('staff_sex')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#service_name#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" readonly value="{$info['service_name']|escape}" name="service_name" id="service_name" class="txt"></td>
          <td class="vatop tips">{form_error('service_name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#meet_result#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" readonly value={$bookingMeet[$info['meet_result']]} name="meet_result" id="meet_result" class="txt"></td>
          <td class="vatop tips">{form_error('meet_result')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#is_cancel#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" readonly value="{if $info['is_cancel']==1}预约中 {else}预约取消{/if	}" name="is_cancel" id="is_cancel" class="txt"></td>
          <td class="vatop tips">{form_error('is_cancel')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#is_notify#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" readonly value="{if $info['is_notify']==2}已提醒{else}未提醒{/if}" name="is_notify" id="username" class="txt"></td>
          <td class="vatop tips">{form_error('is_notify')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#order_id#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" readonly value="{$info['order_id']|escape}" name="order_id" id="order_id" class="txt"></td>
          <td class="vatop tips">{form_error('order_id')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#order_refund#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" readonly value="{$info['order_refund']|escape}" name="order_refund" id="order_refund" class="txt"></td>
          <td class="vatop tips">{form_error('order_refund')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#order_status#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" readonly value="{if $info['order_status']==1}预约单未完成{elseif $info['order_status']==2}预约单完成{elseif $info['order_status']==3}预约单取消{/if}" name="username" id="order_status" class="txt"></td>
          <td class="vatop tips">{form_error('order_status')}</td>
        </tr>
      </tbody>
    </table>
    <div class="fixedOpBar">
    	{if $compile == 'yes'}
    		<input type="submit" name="tijiao" value="保存" class="msbtn"/>
    	{/if}
    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
    </div>
  </form>

{include file="common/main_footer.tpl"}