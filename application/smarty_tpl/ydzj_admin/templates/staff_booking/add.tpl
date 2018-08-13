{include file="common/main_header_navs.tpl"}
  {config_load file="worker.conf"}
  {if $info['id']}
  {form_open(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open(site_url($uri_string),'id="infoform"')}
  {/if}
  <input type="hidden" name="avatar_url" value=""/>
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
          <td colspan="2" class="required"><label class="validation" for="staff_mobile">{#staff_mobile#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['staff_mobile']|escape}" name="staff_mobile" id="staff_mobile" class="txt"></td>
          <td class="vatop tips"><label id="error_staff_mobile"></label>{form_error('staff_mobile')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="staff_name">{#staff_name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text"  value="{$info['staff_name']|escape}" name="staff_name" id="staff_name" class="txt"></td>
          <td class="vatop tips">{form_error('staff_name')}</td>
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
          <td colspan="2" class="required">{#order_id#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" readonly value="{$info['order_id']|escape}" name="order_id" id="order_id" class="txt"></td>
          <td class="vatop tips">{form_error('order_id')}</td>
        </tr>
      </tbody>
    </table>
    <div class="fixedOpBar">
		<input type="submit" name="tijiao" value="保存" class="msbtn"/>
    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
    </div>
  </form>

  <script>
  	var submitUrl = [new RegExp("{$uri_string}")],searchMobileUrl = "{admin_site_url('staff_booking/getStaffMobile')}";
  	
  </script>
    <script type="text/javascript" src="{resource_url('js/service/staff_booking.js',true)}"></script>
{include file="common/main_footer.tpl"}