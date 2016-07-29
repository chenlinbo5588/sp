{include file="common/main_header.tpl"}
{config_load file="sport.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#meta_title#}</h3>
      <ul class="tab-base">
        <li><a href="{admin_site_url('sports_meta/index')}" class="current"><span>管理</span></a></li>
        <li><a href="{admin_site_url('sports_meta/add')}"><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  {form_open(admin_site_url('sports_meta/index'),'id="formSearch"')}
  <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="category_name">{#cate_title#}</label></th>
          <td>
          	<select name="category_name" id="category_name">
          	<option value="">请选择</option>
            {foreach from=$cateList item=item}
            <option value="{$item['name']}" {if $smarty.post['category_name'] == $item['name']}selected{/if}>{$item['name']}</option>
            {/foreach}
        	</select>
          </td>
          <th><label for="gname">{#groupname#}</label></th>
          <td><input type="text" value="{$smarty.post['gname']|escape}" name="gname" id="gname" class="txt"></td>
          <th><label for="search_name">名称</label></th>
          <td><input type="text" value="{$smarty.post['search_name']|escape}" name="search_name" id="search_name" class="txt"></td>
          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
        </tr>
      </tbody>
   </table>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          {*<th class="w24"></th>*}
          <th>序号</th>
          <th>{#cate_title#} - {#groupname#} - 名称</th>
          <th>状态</th>
          <th>添加时间</th>
          <th>添加人</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          {*<td><input type="checkbox" name="id[]" group="chkVal" value="{$item['id']}" class="checkitem"></td>*}
          <td>{$item['id']}</td>
          <td>{$item['category_name']|escape} - {$item['gname']|escape} - {$item['name']|escape}</td>
          <td class="yes-onoff">
	      	<a href="javascript:void(0);" data-url="{admin_site_url('sports_meta/onoff')}" data-id="{$item['id']}" class="{if $item['status'] == 1}enabled{else}disabled{/if}" data-fieldname="status">&nbsp;</a>
	     </td>
          <td class="nowrap">{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td class="nowrap">{$item['add_username']|escape}</td>
          <td>
          	<p><a href="{admin_site_url('sports_meta/edit')}?id={$item['id']}">编辑</a></p>
          </td>
        </tr>
      	{/foreach}
      </tbody>
      <tfoot>
      	<tr class="tfoot">
          <td colspan="8">{include file="common/pagination.tpl"}</td>
        </tr>
      </tfoot>
    </table>
  </form>
  <script>
	$(function(){
		bindOnOffEvent();
	});
  </script>
{include file="common/main_footer.tpl"}