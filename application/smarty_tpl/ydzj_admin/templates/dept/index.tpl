{include file="common/main_header.tpl"}
  {form_open(admin_site_url('dept/index'),'id="formSearch"')}
  	 <input type="hidden" name="page" value=""/>
	 <table class="tb-type1 noborder search">
	    <tbody>
	        <tr>
	          <th><label for="search_name">名称</label></th>
	          <td><input class="txt" name="name" id="search_name" value="{$smarty.post['name']|escape}" type="text"></td>
	          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
	        </tr>
	    </tbody>
	  </table>
	  <table class="table tb-type2" id="prompt">
	    <tbody>
	      <tr class="space odd">
	        <th colspan="2"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div></th>
	      </tr>
	      <tr>
	        <td>
		        <ul>
		            <li>管理本平台上办事机构信息</li>
		        </ul>
	        </td>
	      </tr>
	    </tbody>
	  </table>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th class="w48">排序</th>
          <th>代码</th>
          <th>全称</th>
          <th>简称</th>
          <th>机构类型</th>
          <th>LOGO</th>
          <th>状态</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input value="{$item['id']}" class="checkitem" group="chkVal" type="checkbox" name="del_id[]"></td>
          <td class="sort">{$item['displayorder']}</td>
          <td>{$item['code']|escape}</td>
          <td>{$item['name']|escape}</td>
          <td>{$item['short_name']|escape}</td>
          <td>{$item['org_type']|escape}</td>
          <td class="picture"><div class="brand-picture">{if $item['logo_pic']}<img src="{resource_url($item['logo_pic'])}"/>{/if}</div></td>
          <td class="yes-onoff">
          	<a href="JavaScript:void(0);" {if $item['status']}class="enabled"{else}class="disabled"{/if} data-id="{$item['id']}" data-fieldname="status"><img src="{resource_url('img/transparent.gif')}"></a>
          </td>
          <td class="align-center"><a href="{admin_site_url('dept/edit')}?id={$item['id']}">编辑</a>&nbsp;|&nbsp;<a href="javascript:void(0)" class="delete" data-title="删除" data-url="{admin_site_url('dept/delete')}" data-id="{$item['id']}">删除</a></td>
        </tr>
        {/foreach}
      </tbody>
      <tfoot>
      	<tr class="tfoot">
          <td colspan="9">
          	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
          	<a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="del_id[]" data-url="{admin_site_url('dept/delete')}"><span>删除</span></a>
          	{include file="common/pagination.tpl"}
           </td>
        </tr>
       </tfoot>
    </table>
  </form>
<script>
$(function(){
    bindDeleteEvent();
    bindOnOffEvent();
});
</script>
{include file="common/main_footer.tpl"}