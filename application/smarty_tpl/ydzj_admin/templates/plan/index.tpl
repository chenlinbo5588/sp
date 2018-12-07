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
	          
	          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
	        </tr>
	    </tbody>
	  </table>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th>{#address#}</th>
          <th>{#resident_name#}</th>
          <th>{#type_name#}</th>
          <th>{#plan_year#}</th>
          <th>{#amount_plan#}</th>
          <th>{#amount_real#}</th>
          <th>{#amount_payed#}</th>
          <th>{#pay_method#}</th>
          <th>{#order_id#}</th>

        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><a class="popwin" href="javascript:void(0);" 
          data-title="{$item['address']|escape}"
          data-url="{admin_site_url($moduleClassName|cat:'/detail?id='|cat:$item['id']|cat:'&year='|cat:$item['year'])}">{$item['address']|escape}</a>
          </td>
          <td>{$residentList[$item['resident_id']]['name']|escape}</td>
          <td>{$item['feetype_name']|escape}</td>
          <td>{$item['year']|escape}</td>
          <td>{$item['amount_plan']|escape}</td>
          <td>{$item['amount_real']|escape}</td>
          <td>{$item['amount_payed']|escape}</td>
          <td>{$payMethod['method'][$payMethod['channel'][substr($item['pay_method'],0,1)]][$item['pay_method']]|escape}</td>
          <td>{$item['order_id']|escape}</td> 
        </tr>
        {/foreach}
      </tbody>
    </table>

  </form>
	      <div class="fixedOpBar">
	        {include file="common/pagination.tpl"}
	    </div>
  <div id="detailDlg"></div>
  <script type="text/javascript" src="{resource_url('js/jquery.edit.js')}"></script>
  <script type="text/javascript">
  	var configUrls = {
	  	'targetId' : '#listtable',
	  	'dataUrl' : "{admin_site_url($moduleClassName|cat:'/category')}",
	  	'inlineUrl' : "{admin_site_url($moduleClassName|cat:'/inline_edit')}"
	};
	
	$(function(){
		$(".popwin").bind('click',function(){
			popWindowFn($(this),{ position: window, selector:"#detailDlg", width:'80%',height:'auto',title:'费用计划明细-' + $(this).attr('data-title') },{});
		});
	});
	
  </script>
{include file="common/main_footer.tpl"}