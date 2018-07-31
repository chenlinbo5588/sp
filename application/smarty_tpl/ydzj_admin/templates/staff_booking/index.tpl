{include file="common/main_header_navs.tpl"}
  {config_load file="worker.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="username">{#username#}</label></th>
          <td><input type="text" value="{$search['username']|escape}" name="username" id="username" class="txt"></td>
          <th><label for="staff_name">{#staff_name#}</label></th>
          <td><input type="text" value="{$search['staff_name']|escape}" name="staff_name" id="staff_name" class="txt"></td>
          <th><label>{#meet_result#}</label></th>
          <td>
          	<select name="meet_result">
          	  <option value="">请选中</option>
          	  {foreach from=$bookingMeet key=key item=item}
          	  <option value="{$key}" {if $search['meet_result'] == $key}selected{/if}>{$item}</option>
          	  {/foreach}
            </select>
          </td>
          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
        </tr>
        <tr>	
	      <th><label for="username">客户{#mobile#}</label></th>
          <td><input type="text" value="{$search['mobile']|escape}" name="mobile" id="mobile" class="txt"></td>
          <th><label for="staff_mobile">{#staff_mobile#}</label></th>
          <td><input type="text" value="{$search['staff_mobile']|escape}" name="staff_mobile" id="staff_mobile" class="txt"></td>
          <th><label>{#order_status#}</label></th>
          <td>
          	<select name="order_status">
          	  <option value="">请选中</option>
          	  {foreach from=$bookingStatus key=key item=item}
          	  <option value="{$key}" {if $search['order_status'] == $key}selected{/if}>{$item}</option>
          	  {/foreach}
            </select>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
    <table class="table tb-type2 mgbottom">
      <thead>
        <tr class="thead">
          <th class="w24">选择</th>
          <th>{#username#}<br/>{#mobile#}</th>
          <th>{#staff_name#}<br/>{#staff_mobile#}</th>
          <th>{#staff_sex#}</th>
          <th>{#avatar_url#}</th>
          <th>{#service_name#}</th>
          <th>{#meet_time#}</th>
          <th>{#is_cancel#}</th>
          <th>{#meet_result#}</th>
          <th>{#is_notify#}</th>
          <th>{#order_status#}</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input type="checkbox" name="id[]" group="chkVal" value="{$item['id']}" class="checkitem"></td>
          <td class="username"><a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">{$item['username']|escape}</a><br/>{$item['mobile']}</td>
         <td>{$item['staff_name']}<br/>{$item['staff_mobile']}</td>
         <td>{if $item['staff_sex'] == 1}男{else}女{/if}</td>
         <td class="w120 picture"><a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}"><img class="size-100x100" src="{if $item['avatar_url']}{resource_url($item['avatar_url'])}{else}{resource_url('img/default.jpg')}{/if}"/></a></td>
         <td>{$item['service_name']}</td>
         <td>{$item['meet_time']|date_format:"%Y-%m-%d %H:%M"}</td>
         <td>{if $item['is_cancel'] == 0}正常{elseif $item['is_cancel'] == 1}已取消{/if}</td>
         <td>{if $item['meet_result']==1}未碰面{elseif $item['meet_result']==2}预约成功{elseif $item['meet_result']==3}预约失败{/if	}</td>
         <td>{if $item['is_notify']==1}已提醒{else}未提醒{/if}</td>
         <td>{if $item['order_status']==1}预约单未完成{elseif $item['order_status']==2}预约单完成{elseif $item['order_status']==3}预约单取消{/if}</td>
         <td>
          	<a href="{admin_site_url($moduleClassName|cat:'/detail')}?id={$item['id']}">预约单详情</a>
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
    <div class="fixedOpBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
       	<a href="javascript:void(0);" class="btn verifyBtn" data-title="取消预约" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/batch_cancel')}" data-ajaxformid="#verifyForm"><span>取消预约</span></a>
       	<a href="javascript:void(0);" class="btn verifyBtn" data-title="恢复预约" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/batch_cancel')}" data-ajaxformid="#verifyForm"><span>恢复预约</span></a>
       	<a href="javascript:void(0);" class="btn opBtn" data-title="确定要发送提醒吗" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/notify')}" data-ajaxformid="#verifyForm"><span>提醒</span></a>
        {include file="common/pagination.tpl"}
    </div>
  </form>
  <div id="verifyDlg"></div>
  <script type="text/javascript" src="{resource_url('js/service/staff_index.js',true)}"></script>
{include file="common/main_footer.tpl"}