{include file="common/main_header_navs.tpl"}
  {form_open(site_url($uri_string),'name="formSearch" id="formSearch" method=get')}
    <input type="hidden" name="page" value=""/>
    <input type="hidden" name="submit_type" id="submit_type" value="" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>角色名称</td>
          <td><input type="text" value="{$smarty.post['keywords']}" name="keywords" class="txt"></td>
          <td>
            <input type="submit" class="msbtn" name="tijiao" value="查询"/>
          </td>
        </tr>
      </tbody>
    </table>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th class="align-center">角色</th>
          <th class="align-center">成员数量</th>
          <th class="align-center">是否启用</th>
          <th class="align-center">添加人</th>
          <th class="align-center">添加时间</th>
          <th class="align-center">最后修改人</th>
          <th class="align-center">最后修改时间</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover" id="row{$item['id']}">
      	  <td><input type="checkbox" name="id[]" group="chkVal" value="{$item['id']}"></td>
          <td class="align-center">{$item['name']|escape}</td>
          <td class="align-center">{$item['user_cnt']}</td>
          <td class="align-center">已{if $item['enable'] == 0}禁用{else}启用{/if}</td>
          <td class="align-center">{$item['add_username']}</td>
          <td class="align-center">{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td class="align-center">{$item['edit_username']}</td>
          <td class="align-center">{$item['gmt_modify']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td class="align-center">
          	{if isset($permission[$moduleClassName|cat:'/edit'])}<a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a>{/if} &nbsp;
          	{if isset($permission[$moduleClassName|cat:'/delete'])}<a class="delete" href="javascript:void(0);" data-url="{admin_site_url($moduleClassName|cat:'/delete')}?id={$item['id']}" data-id="{$item['id']}">删除</a>{/if} 
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
    <div class="fixedOpBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
        {if isset($permission[$moduleClassName|cat:'/turnon'])}<a href="javascript:void(0);" class="btn opBtn" data-checkbox="id[]" data-title="确认要启用吗?" data-url="{admin_site_url($moduleClassName|cat:'/turnon')}" ><span>启用</span></a>{/if}
        {if isset($permission[$moduleClassName|cat:'/turnoff'])}<a href="javascript:void(0);" class="btn opBtn" data-checkbox="id[]" data-title="确认要禁用吗?" data-url="{admin_site_url($moduleClassName|cat:'/turnoff')}"><span>禁用</span></a>{/if}
        {include file="common/pagination.tpl"}
    </div>
  </form>
  <script type="text/javascript">
	$(function(){
		{if isset($permission[$moduleClassName|cat:'/delete'])}bindDeleteEvent();{/if}
	
	    bindOpEvent({ selector : '.opBtn' } ,{ }, function(ids,json){
	    	if(check_success(json.message)){
				showToast('success',json.message);
				
				setTimeout(function(){
					location.reload();
				},2000);
			}else{
				showToast('error',json.message);
			}
	    })
	    
	});
  </script>
{include file="common/main_footer.tpl"}