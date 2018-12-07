{include file="common/main_header_navs.tpl"}
  {config_load file="order.conf"}

  {form_open(site_url($uri_string),'id="orderForm"')}
    <table class="table tb-type2 mgbottom">
      <tbody>
   
      {if empty($info)}
      <tr>
      	<td colspan="2" class="colorgreen">该房屋物业费本年度已缴清</td>	
      </tr>
      {/if}
      
        <tr class="noborder">
          <td colspan="2" class="required">{#address#}: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" readonly value="{$houseInfo['address']|escape}" name="address" id="address" class="txt">
          </td>
          <td class="vatop tips"><label class="errtip" id="error_address"></label>{form_error('address')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#wuye_type#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
 				<input type="text" readonly value="物业费" name="wuye_type" id="wuye_type" class="txt">
          </td>
        </tr>
		<tr class="noborder">
          <td colspan="2" class="required">{#amount#}: 
		  <a class="popwin" href="javascript:void(0);" 
          data-title="{$info['address']|escape}"
          data-url="{admin_site_url('plan/detail')}?id={$info['id']}&year={$info['year']}">费用详情</a>
          </td>
        </tr>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input readonly type="text" value="{$info['amount_real']|escape}" name="amount" id="amount" class="txt"></td>
          <td class="vatop tips">{form_error('amount')}</td>
        </tr>
		<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="amount_payed">{#amount_payed#}: </label></td>
        </tr>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['amount_real']|escape}" name="amount_payed" id="amount_payed" class="txt"></td>
          <td class="vatop tips">{form_error('amount_payed')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#start_date#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input readonly type="text" value="{if 0 < $planDetailInfo['stat_date']}{date('Y-m-d',$planDetailInfo['stat_date'])}{/if}" name="start_date" value="" class="datepicker txt"/></td>
        </tr>
		<tr class="noborder">
          <td colspan="2" class="required">{#end_date#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input readonly type="text" value="{if 0 < $planDetailInfo['end_date']}{date('Y-m-d',$planDetailInfo['end_date'])}{/if}" name="end_date" value="" class="datepicker txt"/></td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation">{#pay_method#}:</label><label class="errtip" ></label>{form_error('pay_method')}</td>
        </tr>
        <tr class="noborder">
        	<td colspan="2">
        		<select name="pay_method">
        			<option value="">请选择</option>
        			{foreach from=$payMethodList key=key item=item}
        			<option value="{$item}">{$key}</option>
        			{/foreach}
        		</select>
	         </td>
        </tr>
    <div class="fixedOpBar">
		<input type="submit" name="tijiao" value="创建" class="msbtn"/>
    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
    </div>
  </form>
  <div id="detailDlg"></div>
  <script type="text/javascript">
	var submitUrl = [new RegExp("{$uri_string}")];
	

	$(function(){
		bindAjaxSubmit("#orderForm");
		$(".popwin").bind('click',function(){
			popWindowFn($(this),{ position: window, selector:"#detailDlg", width:'80%',height:'auto',title:'费用计划明细-' + $(this).attr('data-title') },{});
		});
     	$( ".datepicker" ).datepicker({
    	changeYear: true
   	 	});
	
	});
  </script>
{include file="common/main_footer.tpl"}