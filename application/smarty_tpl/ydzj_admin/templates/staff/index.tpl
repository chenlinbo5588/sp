{include file="common/main_header_navs.tpl"}
  {config_load file="worker.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  <input type="hidden" name="page" value="{$currentPage}"/>
  <div class="fixedBar">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="name">{#name#}</label></th>
          <td><input type="text" value="{$search['name']|escape}" name="name" id="name" class="txt"></td>
          <th><label>{#verify#}</label></th>
          <td>
          	<select name="status">
          	  <option value="">请选中</option>
          	  {foreach from=$statusConfig key=key item=item}
          	  <option value="{$key}" {if $search['status'] == $key}selected{/if}>{$item}</option>
          	  {/foreach}
            </select>
          </td>
          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
        </tr>
        <tr>
        	<td>年龄:</td>
        	<td>
        		<input type="text" value="{$search['age_s']}" name="age_s" value="" class="txt-short"/>
        		-
        		<input type="text" value="{$search['age_e']}" name="age_e" value="" class="txt-short"/>
	        </td>
	        <td>{#mobile#}:</td>
	        <td colspan="2">
	        	<input type="text" value="{$search['mobile']|escape}" name="mobile" id="mobile" class="txt">
	        </td>
        </tr>
      </tbody>
    </table>
  </div>
  </form>
    <table class="table tb-type2 mgbottom">
      <thead>
        <tr class="thead">
          <th class="w24">选择</th>
          <th class="w120">{#avatar#}</th>
          <th>{#name#}</th>
          <th>{#show_name#}</th>
          <th>{#id_type#}</th>
          <th>{#id_no#}</th>
          <th>{#sex#}</th>
          <th>{#age#}</th>
          <th>{#jiguan#}</th>
          <th>{#verify#}</th>
          <th>{#mobile#}</th>
          <th>{#address#}</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
         <td><input type="checkbox" name="id[]" group="chkVal" value="{$item['id']}" class="checkitem"></td>
         <td class="w120 picture"><a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}"><img class="size-100x100" src="{if $item['avatar_s']}{resource_url($item['avatar_s'])}{else if $item['avatar_b']}{resource_url($item['avatar_b'])}{else if $item['avatar_m']}{resource_url($item['avatar_m'])}{else}{resource_url('img/default.jpg')}{/if}"/></a></td>
         <td class="name"><a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">{$item['name']|escape}</a></td>
         <td>{$item['show_name']|escape}</td>
         <td>{$basicData[$item['id_type']]['show_name']}</td>
         <td>{$item['id_no']}</td>
         <td>{if $item['sex'] == 1}男{else}女{/if}</td>
         <td>{$item['age']}</td>
         <td>{$jiguanList[$item['jiguan']]['show_name']}</td>
         <td>
         	<div>{$statusConfig[$item['status']]}</div>
         	<div>{$item['reason']|escape}</div>
         </td>
         
         <td>{$item['mobile']}</td>
         <td>{$item['address']|escape}</td>
         <td>
          	<p>
          		<a href="{admin_site_url('worker/edit')}?id={$item['worker_id']}">人员信息</a> |
          		{if $statusConfig[$item['status']] == '待审核'}<a href="{admin_site_url($moduleClassName|cat:'/single_verify')}?id={$item['id']}">审核</a> |{/if}
          		<a href="{admin_site_url($moduleClassName|cat:'/detail')}?id={$item['id']}">详情</a> | 
          		<a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a>
          	</p>
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
    <div class="fixedOpBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
    	<a href="javascript:void(0);" class="btn opBtn handleVerifyBtn" data-title="确定提交审核吗?" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/handle_verify')}"><span>提交审核</span></a>
    	<a href="javascript:void(0);" class="btn verifyBtn" data-title="审核" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/batch_verify')}" data-ajaxformid="#verifyForm"><span>审核</span></a>
        <a href="javascript:void(0);" class="btn opBtn publishBtn" data-title="确定发布吗?" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/batch_published')}"><span>发布</span></a>
        <a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/delete')}"><span>删除</span></a>
        {include file="common/pagination.tpl"}
    </div>
  </form>
  <div id="verifyDlg"></div>
  <script type="text/javascript" src="{resource_url('js/service/staff_index.js',true)}"></script>
{include file="common/main_footer.tpl"}