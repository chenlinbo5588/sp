{include file="common/main_header.tpl"}
{config_load file="sport.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#cate_title#}</h3>
      <ul class="tab-base">
        <li><a href="{admin_site_url('sports_cate/index')}" class="current"><span>管理</span></a></li>
        <li><a href="{admin_site_url('sports_cate/add')}"><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  {form_open(admin_site_url('sports_cate/index'),'id="formSearch"')}
  <input type="hidden" name="page" value=""/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
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
          <th>名称</th>
          <th>LOGO</th>
          <th>{#teamwork#}</th>
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
          <td>{$item['name']|escape}</td>
          <td>{if $item['logo_url']}<img src="{resource_url($item['logo_url'])}"/>{/if}</td>
          <td>{if $item['teamwork'] == 1}团队{else if $item['teamwork'] == 2}团队或个人{else if $item['teamwork'] == 3}个人{/if}</td>
          <td class="yes-onoff">
	      	<a href="javascript:void(0);" data-url="{admin_site_url('sports_cate/onoff')}" data-id="{$item['id']}" class="{if $item['status'] == 1}enabled{else}disabled{/if}" data-fieldname="status">&nbsp;</a>
	     </td>
          <td class="nowrap">{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td class="nowrap">{$item['add_username']|escape}</td>
          <td>
          	<p><a href="{admin_site_url('sports_cate/edit')}?id={$item['id']}">编辑</a></p>
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