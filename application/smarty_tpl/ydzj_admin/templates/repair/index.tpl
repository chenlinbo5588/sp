{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  	 <input type="hidden" name="page" value="{$currentPage}"/>
	 <table class="tb-type1 noborder search" >
	    <tbody>
	        <tr>
	          <td>{#repair_type#}:</td>
	          <td>
	          	<select name="repair_type" id="id_type">
		          <option value="">请选择...</option>
		          {foreach from=$repairType key=key item=item}
		          <option value="{$key}" {if $search['repair_type'] == $key}selected{/if}>{$item}</option>
	              {/foreach}
		        </select>
	          </td>
	          <th><label for="name">{#yezhu_name#}</label></th>
	          <td><input class="txt" name="name" value="{$smarty.get['name']|escape}" type="text"></td>
	          <th><label for="name">{#mobile#}</label></th>
	          <td><input class="txt" name="mobile" value="{$smarty.get['mobile']|escape}" type="text"></td>
  	          <td>{#status#}:</td>
	          <td>
	          	<select name="status" id="id_type">
		          <option value="">请选择...</option>
		          {foreach from=$repairStatus key=key item=item}
		          <option value="{$key}" {if $search['status'] == $key}selected{/if}>{$item}</option>
	              {/foreach}
		        </select>
	          </td>
	          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
	        </tr>
	    </tbody>
	  </table>
    <table class="table tb-type2 mgbottom">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th>{#repair_type#}</th>
          <th>{#yezhu_name#}</th>
          <th>{#mobile#}</th>
          <th>{#address#}</th>
          <th>{#status#}</th>
          <th>{#worker_name#}</th>
          <th>{#worker_mobile#}</th>
          <th class="w72 align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input value="{$item['id']}" class="checkitem" group="chkVal" type="checkbox" name="id[]"></td>
          <td><div>{$repairType[$item['repair_type']]}</div></td>
          <td>{$item['yezhu_name']}</td>
          <td><span class="editable" data-id="{$item['id']}" data-fieldname="mobile">{$item['mobile']}</span></td>
		  <td><span class="editable" data-id="{$item['id']}" data-fieldname="address">{if $item['address']}{$item['address']|escape}{else}未填写{/if}</span></td>
          <td><div>{$repairStatus[$item['status']]}</div></td>
          <td><span class="editable" data-id="{$item['id']}" data-fieldname="worker_name">{$item['worker_name']|escape}</span></td>
          <td><span class="editable" data-id="{$item['id']}" data-fieldname="worker_mobile">{$item['worker_mobile']|escape}</span></td>
          <td class="align-center">
          	<a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a>&nbsp;|&nbsp;
          	<a href="javascript:void(0)" class="delete" data-url="{admin_site_url($moduleClassName|cat:'/delete')}" data-id="{$item['id']}">删除</a>
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
    <div class="fixedOpBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
        <a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/delete')}"><span>删除</span></a>
        <a href="javascript:void(0);" class="btn opBtn" data-title="确实受理吗?" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/received')}" data-ajaxformid="#verifyForm"><span>受理</span></a>
        <a href="javascript:void(0);" class="btn verifyBtn" data-title="派遣" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/dispatch')}" data-ajaxformid="#ajaxForm"><span>派遣</span></a>
        <a href="javascript:void(0);" class="btn verifyBtn" data-title="完成" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/complete_repair')}" data-ajaxformid="#ajaxForm"><span>完成</span></a>
        {include file="common/pagination.tpl"}
    </div>
  </form>
  <script type="text/javascript" src="{resource_url('js/jquery.edit.js')}"></script>
  <div id="verifyDlg"></div>
  
  <script>
  	var submitUrl = [new RegExp("{admin_site_url($moduleClassName|cat:'/dispatch')}")];
  	var editUrl = "{admin_site_url($moduleClassName|cat:'/inline_edit')}";
  	
  </script>
  <script type="text/javascript" src="{resource_url('js/wuye/repair_index.js',true)}"></script>
{include file="common/main_footer.tpl"}