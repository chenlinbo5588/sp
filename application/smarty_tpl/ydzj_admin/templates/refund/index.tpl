{include file="common/main_header_navs.tpl"}
  {config_load file="order.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  	<input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="order_id">{#refund_order_id#}</label></th>
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
          <th class="">{#refund_order_id#}</th>
          <th>{#order_old#}</th>
          <th>{#name#}</th>
          <th>{#mobile#}</th>
          <th>{#order_typename#}</th>
          <th>{#pay_channel#}</th>
          <th>{#pay_method#}</th>
          <th>{#amount#}(元)</th>
          <th>{#goods_name#}</th>
          <th>{#status#}</th>
          <th>{#refund_amount#}</th>
          <th>{#refund_cnt#}</th>
          <th>{#ip#}</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input type="checkbox" name="id[]" group="chkVal" value="{$item['id']}" class="checkitem"></td>
          <td><a href="{admin_site_url($moduleClassName|cat:'/detail')}?id={$item['id']}">{$item['order_id']}</a></td>
          <td>{$item['order_old']}</td>
          <td>{$item['add_username']}</td>
          <td>{$item['mobile']}</td>
          <td>{$item['order_typename']}</td>
          <td>{$item['pay_channel']}</td>
          <td>{$item['pay_method']}</td> 
          <td>{{$item['amount']}/100}</td>
          <td>{$item['goods_name']}</td>
          <td>{$OrderStatus[$item['status']]}</td>      
          <td>{$item['refund_amount']}</td>
          <td>{$item['refund_cnt']}</td>
          <td>{$item['ip']}</td>
          <td>
          	<p>
          		<a href="{admin_site_url($moduleClassName|cat:'/detail')}?id={$item['id']}">详情</a>
          	</p>
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
    <div class="fixedOpBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
    	<a href="javascript:void(0);" class="btn verifyBtn" data-title="审核" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/batch_verify')}" data-ajaxformid="#verifyForm"><span>审核</span></a>
        {include file="common/pagination.tpl"}
    </div>
  </form>
  <div id="verifyDlg"></div>
  <script type="text/javascript" src="{resource_url('js/refund/index.js',true)}"></script>
{include file="common/main_footer.tpl"}