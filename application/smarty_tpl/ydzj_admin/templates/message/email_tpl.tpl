{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>消息通知</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('message/email')}" ><span>邮件设置</span></a></li>
      	<li><a  class="current"><span>消息模板</span></a></li>
      </ul>
     </div>
  </div>
  <div class="fixed-empty"></div>
  
  {form_open(admin_site_url('message/email_tpl'),'id="formSearch" name="formSearch"')}
    <input type="hidden" name="page" value="{$currentPage}"/>
    <input type="hidden" name="submit_type" id="submit_type" value="" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>描述</td>
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
          <th>&nbsp;</th>
          <th>模板描述</th>
          <th class="align-center">开启/关闭</th>
          <th class="align-center">类型</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover">
          <td class="w24"><input type="checkbox" name="del_id[]" value="{$item['code']}" class="checkitem"></td>
          <td class="w50pre">{$item['name']}</td>
          <td class="align-center power-onoff">{if $item['is_off'] == 1}关闭{else}开启{/if}</td>
          <td class="align-center">{if $item['type'] == 0}邮件{else}短信{/if}</td>
          <td class="w60 align-center"><a href="{admin_site_url('message/email_tpl_edit')}?code={$item['code']}">编辑</a></td>
        </tr>
       	{foreachelse}
       	<tr>
       		<td colspan="5" class="align-center">找不到记录</td>
       	</tr>
      	{/foreach}
      </tbody>
      <tfoot>
        <tr>
          <td colspan="5">
          	<label for="checkallBottom">全选</label>&nbsp;&nbsp;<a href="javascript:void(0);" class="btn" onclick="$('#submit_type').val('mail_switchON');go();"><span>开启</span></a><a href="javascript:void(0);" class="btn" onclick="$('#submit_type').val('mail_switchOFF');go();"><span>关闭</span></a>
            {include file="common/pagination.tpl"}
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
  <script type="text/javascript">
	function go(){
		document.formSearch.submit();
	}
  </script>
{include file="common/main_footer.tpl"}