 {include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  	 <input type="hidden" name="page" value="{$currentPage}"/>
	 <table class="tb-type1 noborder search" >
	    <tbody>
	        <tr>
	          <th><label for="address">{#address#}</label></th>
	          <td><input class="txt" name="address" id="address" value="{$smarty.get['address']|escape}" type="text"></td>
	          <th><label for="resident_name">{#resident_name#}</label></th>
	          <td><input class="txt" name="resident_name" id="resident_name" value="{$smarty.get['resident_name']|escape}" type="text"></td>
	          <th><label for="wuye_type">{#wuye_type#}</label></th>
	          <td class="vatop rowform">
	          	<select name="wuye_type">
		          <option value="">请选择...</option>
		          {foreach from=$wuyeTypeList key=key item=item}
		          <option {if $search['wuye_type'] == $key}selected{/if} value="{$item['show_name']}">{$item['show_name']}</option>
		          {/foreach}
		        </select>
	          </td>	
	        </tr>
	        <tr>
	          <td>{#wuye_expire#}:</td>
	    	   <td>
	    		<input type="text" autocomplete="off"  value="{$search['wuye_expire_s']}" name="wuye_expire_s" id="wuye_expire_s"  class="datepicker txt-short"/>
	    		-
	    		<input type="text" autocomplete="off"  value="{$search['wuye_expire_e']}" name="wuye_expire_e" id="wuye_expire_e" class="datepicker txt-short"/>
          		</td>	
          		<td>{#nenghao_expire#}:</td>
	    	   <td>
	    		<input type="text" autocomplete="off"  value="{$search['nenghao_expire_s']}" name="nenghao_expire_s" id="nenghao_expire_s"  class="datepicker txt-short"/>
	    		-
	    		<input type="text" autocomplete="off"  value="{$search['nenghao_expire_e']}" name="nenghao_expire_e" id="nenghao_expire_e" class="datepicker txt-short"/>
          		</td>
	        </tr>
	        <tr>
	        	<th><label for="name">{#yezhu_name#}</label></th>
		        <td><input class="txt" name="yezhu_name" value="{$smarty.get['yezhu_name']|escape}" type="text"></td>
		        <th><label for="name">{#mobile#}</label></th>
		        <td><input class="txt" name="mobile" value="{$smarty.get['mobile']|escape}" type="text"></td>
		        <td colspan="2"><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
	        </tr>
	    </tbody>
	  </table>
    <table class="table tb-type2 mgbottom">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th>{#displayorder#}</th>
          <th>{#address#}</th>
          <th>{#wuye_type#}<br>{#jz_area#}</th>
          <th>{#yezhu_name#}<br>{#mobile#}</th>
          <th>{#car_no#}</th>
          <th>{#wuye_expire#}</th>
          <th>{#nenghao_expire#}</th>
          <th>{#amount_recrive_count#}<br>{#amount_arrears_count#}</th>
          <th>{#amount_recrive_now#}<br>{#amount_arrears_now#}</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input value="{$item['id']}" class="checkitem" group="chkVal" type="checkbox" name="id[]"></td>
          <td class="sort"><span class="editable" data-id="{$item['id']}" data-fieldname="displayorder">{$item['displayorder']}</span></td>
          <td class="name"><span class="editable" data-id="{$item['id']}" data-fieldname="address">{$item['address']|escape}</span></td>
          <td>{$item['wuye_type']|escape}<br>{$item['jz_area']|escape}</td>
          <td><a href="{admin_site_url(yezhu|cat:'/index')}?name={$item['yezhu_name']}">{if $item['yezhu_id']}{$item['yezhu_name']}{else}暂未入驻{/if}<br><a href="{admin_site_url(yezhu|cat:'/index')}?mobile={$item['mobile']}">{$item['mobile']|escape}</td>
          <td>{$item['car_no']|escape}</td>
          <td>{if $item['wuye_expire']}{$item['wuye_expire']|date_format:"%Y-%m-%d"}{else}无缴费记录{/if}</td>
          <td>{if $item['nenghao_expire']}{$item['nenghao_expire']|date_format:"%Y-%m-%d"}{else}无缴费记录{/if}</td>
          <td><a href="{admin_site_url(plan|cat:'/index')}?address={$item['address']}">{$item['amount_recrive_count']|escape}<a href="{admin_site_url(plan|cat:'/index')}?address={$item['address']}&charge_status=1"><br>{$item['amount_arrears_count']|escape}</td>
          <td><a href="{admin_site_url(plan|cat:'/index')}?address={$item['address']}&year={date('Y')}">{$item['amount_recrive_now']|escape}<br><a href="{admin_site_url(plan|cat:'/index')}?address={$item['address']}&year={date('Y')}&charge_status=1">{$item['amount_arrears_now']|escape}</td>
          <td class="align-center">
          	{if isset($permission[$moduleClassName|cat:'/edit'])}<a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a>{/if}&nbsp;
          	<!--{if isset($permission[$moduleClassName|cat:'/yezhu_add'])}<a href="{admin_site_url($moduleClassName|cat:'/yezhu_add')}?id={$item['id']}">增加业主</a>{/if}&nbsp;-->
          	{if isset($permission[$moduleClassName|cat:'/yezhu_change'])}<a href="{admin_site_url($moduleClassName|cat:'/yezhu_change')}?id={$item['id']}">业主变更</a>{/if}&nbsp;
          	{if isset($permission[$moduleClassName|cat:'/pay_house'])}<a href="{admin_site_url($moduleClassName|cat:'/pay_house')}?id={$item['id']}">缴费</a>{/if}&nbsp;
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
    <div class="fixedOpBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
        {if isset($permission[$moduleClassName|cat:'/delete'])}<a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/delete')}"><span>删除</span></a>{/if}
        {if isset($permission[$moduleClassName|cat:'/delete_yezhu'])}<a href="javascript:void(0);" class="btn deleteYezhu" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/delete_yezhu')}"><span>删除业主</span></a>{/if}
        {if isset($permission[$moduleClassName|cat:'/create_plan'])}<a href="javascript:void(0);" class="btn verifyBtn" data-title="生成收费计划" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/create_plan')}" data-ajaxformid="#ajaxForm"><span>生成收费计划</span></a>{/if}
        {include file="common/pagination.tpl"}
        
    </div>
  </form>
  <script type="text/javascript" src="{resource_url('js/jquery.edit.js')}"></script>
<script>
$(function(){

	{if isset($permission[$moduleClassName|cat:'/delete'])}
    bindDeleteEvent();
    bindDeleteEvent({ btnClass : '.deleteYezhu'},function(ids,json){
    	if(check_success(json.message)){
			showToast('success',json.message);
		}else{
			showToast('error',json.message);
		}
    })
    {/if}

	
	
     $( ".datepicker" ).datepicker({
    	changeYear: true
    });
    
    
    {if isset($permission[$moduleClassName|cat:'/inline_edit'])}
    $("span.editable").inline_edit({ 
    	url: "{admin_site_url($moduleClassName|cat:'/inline_edit')}",
    	clickNameSpace:'inlineEdit'
    });
    {/if}
	    
});
</script>
  <script>
  	var submitUrl = [new RegExp("{admin_site_url($moduleClassName|cat:'/create_plan')}"),new RegExp("{admin_site_url($moduleClassName|cat:'/choise_year')}")];
  </script>
<div id="verifyDlg"></div>
  <script type="text/javascript" src="{resource_url('js/service/house_index.js',true)}"></script>
{include file="common/main_footer.tpl"}