{include file="common/main_header_navs.tpl"}
  {form_open(site_url($uri_string),'id="formSearch"')}
  	 <input type="hidden" name="page" value=""/>
	 <table class="tb-type1 noborder search">
	    <tbody>
	        <tr>
	          <th><label>系统消息发送时间</label></th>
	          <td><input class="txt" name="stime" type="text"></td>
	          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
	        </tr>
	    </tbody>
	  </table>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th>序号</th>
          <th>标题</th>
          <th>发送方式</th>
          <th>发送对象匹配模式</th>
          <th>成员</th>
          <th>创建时间</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['brand_id']}">
          <td>{$item['id']}</td>
          <td>{$item['title']|escape}</td>
          <td>{$item['send_ways']}</td>
          <td>{$msgMode[$item['msg_mode']]}</td>
          <td>{cutText($item['users'],80)}</td>
          <td>{time_tran($item['gmt_create'])}</td>
          <td><a href="{admin_site_url('notify/detail')}?id={$item['id']}">查看</a></td>
        </tr>
        {/foreach}
      </tbody>
      <tfoot>
      	<tr class="tfoot">
          <td colspan="7">
          	{include file="common/pagination.tpl"}
           </td>
        </tr>
       </tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}