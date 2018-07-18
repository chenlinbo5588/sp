{include file="common/main_header.tpl"}
  {config_load file="order.conf"}
  {include file="common/sub_nav.tpl"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  <input type="hidden" name="page" value="{$currentPage}"/>
  <div class="fixedBar">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="order_id">{#order_id#}</label></th>
          <td><input type="text" value="{$search['order_id']|escape}" name="order_id" id="order_id" class="txt"></td>
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
  </div>
  </form>
    <table class="table tb-type2 mgbottom">
      <thead>
        <tr class="thead">
          <th class="w24">选择</th>
          <th class="">{#order_id#}</th>
          <th>{#name#}</th>
          <th>{#ref_order#}</th>
          <th>{#order_typename#}</th>
          <th>{#pay_channel#}</th>
          <th>{#pay_method#}</th>
          <th>{#amount#}</th>
          <th>{#order_time#}</th>
          <th>{#pay_time_end#}</th>
          <th>{#ip#}</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input type="checkbox" name="id[]" group="chkVal" value="{$item['id']}" class="checkitem"></td>
          <td><a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">{$item['order_id']}</a></td>
          <td>{$item['uid']}</td>
          <td>{$item['ref_order']}</td>
          <td>{$item['order_typename']}</td>
          <td>{$item['pay_channel']}</td>
          <td>{$item['pay_method']}</td>
          <td>{$item['amount']}</td>
          <td>{$item['gmt_modify']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td>{$item['pay_time_end']}</td>
          <td>{$item['ip']}</td>
          <td>
          	<p>
          		{if $statusConfig[$item['status']] == '待审核'}<a href="{admin_site_url($moduleClassName|cat:'/single_verify')}?id={$item['id']}">审核</a> |{/if}
          		<a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a>
          	</p>
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
    <div class="fixedOpBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
    	<a href="javascript:void(0);" class="btn handleVerifyBtn" data-title="确定提交审核吗?" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/handle_verify')}"><span>提交审核</span></a>
    	<a href="javascript:void(0);" class="btn verifyBtn" data-title="审核" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/batch_verify')}" data-ajaxformid="#verifyForm"><span>审核</span></a>
        <a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/delete')}"><span>删除</span></a>
        {include file="common/pagination.tpl"}
    </div>
  </form>
  <div id="verifyDlg"></div>
{include file="common/main_footer.tpl"}