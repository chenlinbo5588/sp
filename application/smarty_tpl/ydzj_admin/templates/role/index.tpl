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
        <tr class="space">
          <th colspan="5" class="nobg">列表</th>
        </tr>
        <tr class="thead">
          <th></th>
          <th>角色</th>
          <th>状态</th>
          <th>创建时间</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover">
      	  <td class="w24"><input name="del_id[]" group="chkVal" type="checkbox" value="{$item['id']}"></td>
          <td>{$item['name']|escape}</td>
          <td>{$item['status']}</td>
          <td>{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td class="align-center"><a href="{$editUrl}?id={$item['id']}">编辑</a></td>
        </tr>
      	{foreachelse}
      	<tr class="no_data">
          <td colspan="5">没有符合条件的记录</td>
        </tr>
        {/foreach}
      </tbody>
      <tfoot>
      	<tr>
      		<td colspan="5">
      			<label><input type="checkbox" class="checkall" name="chkVal">全选</label>&nbsp;
          		<a href="javascript:void(0);" class="btn" onclick="$('#submit_type').val('开启');go();"><span>开启</span></a>
          		<a href="javascript:void(0);" class="btn" onclick="$('#submit_type').val('关闭');go();"><span>关闭</span></a>
          		{include file="common/pagination.tpl"}
          	</td>
      	</tr>
      </tfoot>
    </table>
  </form>
  <script type="text/javascript">
	function go(){
		$("#formSearch").submit();
	}
  </script>
{include file="common/main_footer.tpl"}