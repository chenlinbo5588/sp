{include file="common/main_header_navs.tpl"}
  {config_load file="order.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  	<input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="order_id">{#order_id#}</label></th>
          <td><input type="text" value="{$search['order_id']|escape}" name="order_id" id="order_id" class="txt"></td>
          <th><label for="mobile">{#mobile#}</label></th>
          <td><input type="text" value="{$search['mobile']|escape}" name="mobile" id="mobile" class="txt"></td>
          <th><label for="add_username">{#name#}</label></th>
          <td><input type="text" value="{$search['add_username']|escape}" name="add_username" id="add_username" class="txt"></td>
    	  <td>{#amount#}:</td>
    	  <td>
    		<input type="text" value="{$search['amount_s']}" name="amount_s" value="" class="txt-short"/>
    		-
    		<input type="text" value="{$search['amount_e']}" name="amount_e" value="" class="txt-short"/>
          </td>
          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
        </tr>
      </tbody>
    </table>
    <table class="table tb-type2 mgbottom">
      <thead>
        <tr class="thead">
          <th class="w24">选择</th>
          <th class="">{#order_id#}</th>
          <th>{#customer_name#}</th>
          <th>{#mobile#}</th>
          <th>{#order_typename#}</th>
          <th>{#pay_channel#}</th>
          <th>{#pay_method#}</th>
          <th>{#amount#}(元)</th>
          <th>{#refund_amount#}</th>
          <th>{#refund_cnt#}</th>
          <th>{#verify_status#}</th>
          <th>{#goods_name#}</th>
          <th>{#status#}</th>
          <th>{#order_time#}</th>
          <th>{#time_expire#}</th>        
          <th>{#pay_time_end#}</th>
          <th>{#ip#}</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input type="checkbox" name="id[]" group="chkVal" value="{$item['id']}" class="checkitem"></td>
          <td><a href="{admin_site_url($moduleClassName|cat:'/detail')}?id={$item['id']}">{$item['order_id']}</a></td>
          <td>{$item['add_username']}</td>
          <td>{$item['mobile']}</td>
          <td>{$item['order_typename']}</td>
          <td>{$item['pay_channel']}</td>
          <td>{$item['pay_method']}</td> 
          <td>{$item['amount']/100}</td>
          <td>{$item['refund_amount']/100}</td>
          <td>{$item['refund_cnt']}</td>
          <td>{$OrderVerify[$item['verify_status']]}</td>
          <td>{$item['goods_name']}</td>
          <td>{$OrderStatus[$item['status']]}</td>      
          <td>{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td>{$item['time_expire']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td>{$item['pay_time_end']|date_format:"%Y-%m-%d %H:%M:%S"}</td>  
          <td>{$item['ip']}</td>
          <td>
          	<p>
          		<a href="{admin_site_url($moduleClassName|cat:'/detail')}?id={$item['id']}">详情</a>
          		{if '已支付' == $OrderStatus[$item['status']] && $item['refund_amount'] < $item['amount']}
          		<a href="javascript:void(0);"  class="refundLink" data-title="申请退款" data-ajaxformid="#refundForm" data-url="{admin_site_url('refund/apply_refund')}?id={$item['id']}" ><span>申请退款</span></a>
          		{/if}
          	</p>
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
    <div class="fixedOpBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
        <a href="javascript:void(0);" class="btn opBtn" data-checkbox="id[]" data-title="确认关闭未支付的订单吗?" data-url="{admin_site_url($moduleClassName|cat:'/batch_close')}" ><span>关闭</span></a>
        <a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/batch_delete')}"><span>删除</span></a>
        {include file="common/pagination.tpl"}
    </div>
  </form>
  <div id="verifyDlg"></div>
  <script type="text/javascript" src="{resource_url('js/order/index.js',true)}"></script>
{include file="common/main_footer.tpl"}