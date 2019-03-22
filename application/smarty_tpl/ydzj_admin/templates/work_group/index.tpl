{include file="common/main_header_navs.tpl"}
  {form_open(site_url($uri_string),'id="formSearch" class="formSearch"')}
  	<input type="hidden" name="page" value=""/>
  	<input type="hidden" name="submit_type" id="submit_type" value="" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>组名称</td>
          <td><input type="text" value="{$smarty.post['username']}" name="username" class="txt"></td>
          <td>
            <input type="submit" class="msbtn" name="tijiao" value="查询"/>
          </td>
        </tr>
      </tbody>
    </table>
    <table class="table tb-type2 mgbottom">
      <thead>
        <tr class="thead">
          <th class="w24">选择</th>
          <th class="align-center">组名称</th>
          <th class="align-center">组长姓名</th>
          <th class="align-center">成员数量</th>
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
          <td class="align-center">{$item['group_name']|escape}</td>
          <td class="align-center">{$item['group_leader_name']}</td>
          <td class="align-center">{$item['user_cnt']}</td>
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
        <a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/delete')}"><span>删除</span></a>
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