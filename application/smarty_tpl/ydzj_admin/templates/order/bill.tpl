{include file="common/main_header_navs.tpl"}
  {config_load file="order.conf"}
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th class="nobg" colspan="12"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div></th>
      </tr>
      <tr class="noborder">
    	<td colspan="2" >
    		<ol class="tip-yellowsimple">
    			<li>1、导出内容为对账单信息的Excel表格</li>
    			<li>2、微信侧未成功下单的交易不会出现在对账单中。支付成功后撤销的交易会出现在对账单中，跟原支付单订单号一致.</li>
    			<li>3、微信在次日9点启动生成前一天的对账单，建议10点后再获取.</li>
    			<li>4、对账单接口只能下载三个月以内的账单.</li>
    		</ol>
    	</td>
      </tr>
    </tbody>
  </table>
  {form_open(site_url($uri_string),'id="exportForm"')}
  <table class="table tb-type2">
      <tbody>
	        <tr class="noborder">
	          <td colspan="2"><label for="order_typename">账单类型:</label>{form_error('bill_type')}</td>
	        </tr>
  	        <tr class="noborder">
  	          <td class="vatop" colspan="2">
  	          	<ul class="ulListStyle1 clearfix">
					<li class="selected"><label><input type="radio" name="bill_type" value="ALL" checked="checked"/><span>所有订单</span></label></li>
					<li><label><input type="radio" name="bill_type" value="SUCCESS"/><span>成功支付的订单</span></label></li>
					<li><label><input type="radio" name="bill_type" value="REFUND"/><span>退款订单</span></label></li>
					<li><label><input type="radio" name="bill_type" value="RECHARGE_REFUND"/><span>充值退款订单</span></label></li>
				</ul>
			  </td>
	        </tr>
  	        <tr class="noborder">
	          <td colspan="2"><label class="validation" for="bill_date">账单日期:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform"><input type="text" autocomplete="off"  value="{$smarty.post['bill_date']}" name="bill_date" id="bill_date"  class="datepicker txt-short"/></td>
	          <td class="vatop tips">{form_error('bill_date')}</td>
	        </tr>
	        {include file="common/export_format.tpl"}
      	</tbody>
      	<tfoot>
      		<tr class="tfoot">
        		<td colspan="2"><input type="submit" id="importBtn" name="submit" value="下载" class="msbtn"/></td>
      		</tr>
      	</tfoot>
    </table>
  </form>
  <script>
  	$(function(){
  		$( ".datepicker" ).datepicker();
  		
  		
  		
  	});
  </script>
{include file="common/main_footer.tpl"}