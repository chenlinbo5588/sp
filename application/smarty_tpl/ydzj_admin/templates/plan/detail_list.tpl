{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  	 <input type="hidden" name="page" value="{$currentPage}"/>
	 <table class="tb-type1 noborder search" >
	    <tbody>
<tr>
	          <th><label for="address">{#address#}</label></th>
	          <td><input class="txt" name="address" value="{$smarty.get['address']|escape}" type="text"></td>
  	          <th><label for="resident_name">{#resident_name#}</label></th>
	          <td><input class="txt" name="resident_name" value="{$smarty.get['resident_name']|escape}" type="text"></td>
  	          <th><label for="year">{#year#}</label></th>
	          <td><input class="txt" name="year" value="{$smarty.get['year']|escape}" type="text"></td>
	          <td>{#type_name#}:</td>
	          <td>
	          	<select name="feetype_name" id="id_type">
		          <option value="">请选择...</option>
		          <option value="物业费" {if $search['feetype_name'] == "物业费"}selected{/if}>物业费</option>
		          <option value="车位费" {if $search['feetype_name'] == "车位费"}selected{/if}>车位费</option>
		          <option value="能耗费" {if $search['feetype_name'] == "能耗费"}selected{/if}>能耗费</option>
		        </select>
	          </td>
	          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
	        </tr>
	    </tbody>
	  </table>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24">选择</th>
          <th>{#plan_year#}</th>
          <th>{#resident_name#}</th>
          <th>{#address#}</th>
          <th>{#type_name#}</th>
          <th>{#parking_name#}</th>
          <th>{#jz_area#}</th>
          <th>{#price#}</th>
          <th>{#wuye_type#}</th>
          <th>{#billing_style#}</th>
          <th>{#amount_plan#}</th>
          <th>{#amount_real#}</th>
          <th>{#amount_payed#}</th>
          <th>{#pay_method#}</th>
          <th>{#month#}</th>
          <th>{#stat_date#}</th>
          <th>{#end_date#}</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	  <tr class="hover edit" id="row{$item['id']}">
          <td><input value="{$item['id']}" class="checkitem" group="chkVal" type="checkbox" name="id[]"></td>
          <td>{$item['year']|escape}</td>
          <td>{$residentList[$item['resident_id']]['name']|escape}</td>
          <td>{$item['address']|escape}</td> 
          <td>{$item['feetype_name']|escape}</td>
          <td>{$item['parking_name']|escape}</td>
          <td>{$item['jz_area']|escape}</td>
          <td>{$item['price']|escape}</td>
          <td>{$item['wuye_type']|escape}</td>
          <td>{$item['billing_style']|escape}</td>
          <td>{$item['amount_plan']|escape}</td>
          <td><span class="editable" data-id="{$item['id']}" data-year="{$item['year']}" data-fieldname="really_plan_money">{$item['amount_real']|escape}</span></td>
          <td>{$item['amount_payed']|escape}</td>
          <td>{$item['pay_method']|escape}</td>
          <td>{$item['month']|escape}</td> 
          <td>{$item['stat_date']|escape|date_format:"%Y-%m-%d %H:%M"}</td> 
          <td>{$item['end_date']|escape|date_format:"%Y-%m-%d %H:%M"}</td> 
        </tr>
        {/foreach}
      </tbody>
    </table>	
    <div class="fixedOpBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
        {if isset($permission[$moduleClassName|cat:'/edit_money'])}<a href="javascript:void(0);" class="btn verifyBtn" data-title="修改金额" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/edit_money')}" data-ajaxformid="#ajaxForm"><span>修改金额</span></a>{/if}
        {include file="common/pagination.tpl"}
    </div>
  </form>
  <script type="text/javascript" src="{resource_url('js/wuye/plan_edit.js')}"></script>
  <div id="verifyDlg"></div>
  <script>
  	var submitUrl = [new RegExp("{admin_site_url($moduleClassName|cat:'/dispatch')}"),new RegExp("{admin_site_url($moduleClassName|cat:'/complete_repair')}")];
  	var editUrl = "{admin_site_url($moduleClassName|cat:'/inline_edit')}"; 	
  </script>
  <script type="text/javascript" src="{resource_url('js/wuye/plan_detail_index.js',true)}"></script>
{include file="common/main_footer.tpl"}