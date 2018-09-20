{include file="common/main_header_navs.tpl"}
  {config_load file="order.conf"}

  {form_open(site_url($uri_string),'id="orderForm"')}

  <input type="hidden" name="avatar_url" value=""/>
    <table class="table tb-type2 mgbottom">
      <tbody>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="address">{#address#}: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="{$info['address']|escape}" name="address" id="address" class="txt">
          </td>
          <td class="vatop tips"><label class="errtip" id="error_address"></label>{form_error('address')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="type_name">{#wuye_type#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="wuye_type">
	          <option value="">请选择...</option>
	            <option {if $info['name'] == '物业费'}selected{/if} value='物业费'>物业费</option>
				<option {if $info['name'] == '能耗费'}selected{/if} value='能耗费'>能耗费</option>
	        </select>
          </td>
        </tr>
		<tr class="noborder">
          <td colspan="2"><label class="validation" for="name">{#amount#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['amount']|escape}" name="amount" id="amount" class="txt"></td>
          <td class="vatop tips">{form_error('amount')}</td>
        </tr>
		<tr class="noborder">
          <td colspan="2"><label class="validation" for="year">{#year#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['year']|escape}" name="year" id="year" class="txt"></td>
          <td class="vatop tips">{form_error('year')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="year">{#end_month#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['end_month']|escape}" name="end_month" id="end_month" class="txt"></td>
          <td class="vatop tips">{form_error('end_month')}</td>
        </tr>
    <div class="fixedOpBar">
		<input type="submit" name="tijiao" value="创建" class="msbtn"/>
    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
    </div>
  </form>
  <script type="text/javascript">
	var submitUrl = [new RegExp("{$uri_string}")];
	
	$(function(){
		$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#orderForm" });
		bindAjaxSubmit("#orderForm");
	});
  </script>
{include file="common/main_footer.tpl"}