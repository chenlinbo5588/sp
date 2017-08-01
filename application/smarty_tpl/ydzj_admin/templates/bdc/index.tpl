{include file="common/main_header.tpl"}
  {config_load file="common.conf"}
  {form_open(admin_site_url('bdc/index'),'id="formSearch"')}
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
    <table class="table tb-type2">
      <thead>
        <tr>
            <th>{#cm_lsno#}</th>
            <th>登记名称</th>
            <th>{#cm_id_type#}</th>
            <th>{#cm_id_no#}</th>
            <th>{#cm_mobile#}</th>
            <th>{#cm_address#}</th>
            <th>{#cm_status#}</th>
            <th>{#cm_add_username#}<br/>{#cm_gmt_create#}</th>
            <th>{#cm_edit_username#}<br/>{#cm_gmt_modify#}</th>
            <th>{#cm_op#}</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list item=item}
      	<tr id="row{$item['id']}">
           <td><a href="{site_url('bdc/edit?id='|cat:$item['id'])}">{$item['lsno']}</a></td>
           <td><a href="{site_url('bdc/edit?id='|cat:$item['id'])}">{$item['name']|escape}</a></td>
           <td>{$id_type[$item['id_type']]}</td>
           <td>{$item['id_no']}</td>
           <td>{$item['mobile']|escape}</td>
           <td>{$item['address']}</td>
           <td>{$item['status']}</td>
           <td>
           		{$item['add_username']|escape}<br/>
           		{time_tran($item['gmt_create'])}
           </td>
           <td>
           		{$item['edit_username']|escape}<br/>
           		{time_tran($item['gmt_modify'])}
           </td>
           <td>
           		<a href="{site_url('budongchan/edit?id='|cat:$item['id'])}">编辑</a>
           		<a href="javascript:void(0);" class="delete" data-id="{$item['id']}" data-title="{$item['name']|escape}以及关联建筑信息" data-url="{site_url('budongchan/delete')}">删除</a>
           </td>
        <tr>
        {/foreach}
      </tbody>
      <tfoot>
      	<tr class="tfoot">
          <td colspan="10">
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