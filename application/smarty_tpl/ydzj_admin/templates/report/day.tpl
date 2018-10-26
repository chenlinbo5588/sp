 {include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  	 <input type="hidden" name="page" value="{$currentPage}"/>
	 <table class="tb-type1 noborder search" >
	    <tbody>
	        <tr>
	          <td>数据范围</td>
	    	   <td>
	    		<input type="text" autocomplete="off"  value="{$search['date_s']}" name="date_s" id="date_s"  {if $search['report_mode'] == '每日报表'} class="datepicker txt-short"{/if}/>
	    		{if  '每周报表' == $reportMode}周{else if '每月报表' == $reportMode}月{else if '每年报表' == $reportMode}年{/if}
	    		<input type="text" autocomplete="off"  value="{$search['date_e']}" name="date_e" id="date_e" {if $search['report_mode'] == '每日报表'} class="datepicker txt-short"{/if}/>
          		{if  '每周报表' == $reportMode}周{else if '每月报表' == $reportMode}月{else if '每年报表' == $reportMode}年{/if}
          		</td>
			    {if '每周报表' == $reportMode || '每月报表' == $reportMode}
	          	<th><label for="year">报表年份</label></th>
	          	<td><input class="txt" name="year" id="year" value="{$search['year']}" type="text"></td>
			    {/if}
  				<td>{#feetype_name#}:</td>
			    <td>
					<select name="feetype_name" id="id_type">
					  <option value="">请选择...</option>
					  <option value="物业费" {if $search['feetype_name'] == "物业费"}selected{/if}>物业费</option>
					  <option value="能耗费" {if $search['feetype_name'] == "能耗费"}selected{/if}>能耗费</option>
					</select>
			    </td>

		    <td>请选择小区:</td>
        	<td colspan="2">
        		<select name="resident_id">
        			{foreach from=$residentList item=item}
        			<option value="{$item['id']}" {if $residentId == $item['id']}selected{/if}>{$item['name']|escape}</option>
        			{/foreach}
        		</select>
	         </td>
          		<td colspan="2"><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
	        </tr>
	    </tbody>
	  </table>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
       	  <th>{#date#}</th>
          <th>{#resident_name#}</th>
          <th>{#feetype_name#}</th>
          <th>{#amount_real#}</th>
          <th>{#amount_payed#}</th>
          <th>{#collection_rate#}</th>
          <th>{#all_hushu#}</th>
          <th>{#paid_hushu#}</th>
          <th>{#selfpaid_hushu#}</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
      	  <td>{if $item['date_key']} {date('Y-m-d',$item['date_key'])}	{else if $item['week']} {$item['year']}年第{$item['week']}周 {else if $item['month']} {$item['year']}年{$item['month']}月 {else} {$item['year']}年{/if}</td>
          <td>{$item['resident_name']|escape}</td>
          <td>{$item['feetype_name']|escape}</td>
          <td>{$item['amount_real']|escape}</td>
          <td>{$item['amount_payed']|escape}</td>
          <td>{$item['collection_rate']|escape}%</td>
          <td>{$item['all_hushu']|escape}</td>
          <td>{$item['paid_hushu']|escape}</td>
          <td>{$item['selfpaid_hushu']|escape}</td>
          
        </tr>
        {/foreach}
      </tbody>
    </table>
    <div class="fixedOpBar">
        {include file="common/pagination.tpl"}
    </div>
  </form>
<script>
$(function(){

     $( ".datepicker" ).datepicker({
    	changeYear: true
    });
    
});
</script>
{include file="common/main_footer.tpl"}