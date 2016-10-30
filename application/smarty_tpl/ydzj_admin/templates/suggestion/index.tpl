{include file="common/main_header.tpl"}
{config_load file="article.conf"}
  {form_open(admin_site_url('suggestion/index'),'id="formSearch"')}
  <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="username">用户名称</label></th>
          <td><input type="text" value="{$smarty.post['username']|escape}" name="username" id="username" class="txt"></td>
          <th><label for="company_name">公司名称</label></th>
          <td><input type="text" value="{$smarty.post['company_name']|escape}" name="company_name" id="company_name" class="txt"></td>
          <th><label>状态</label></th>
          <td>
        	<select name="status">
        		<option value="">全部</option>
        		<option value="未处理" {if $smarty.post['status'] == '未处理'}selected{/if}>未处理</option>
        		<option value="处理中" {if $smarty.post['status'] == '处理中'}selected{/if}>处理中</option>
        		<option value="已处理" {if $smarty.post['status'] == '已处理'}selected{/if}>已处理</option>
        	</select>
          </td>
          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
        </tr>
      </tbody>
   </table>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24">序号</th>
          <th>用户名</th>
          <th>公司名称</th>
          <th>手机号码</th>
          <th>城市</th>
          <th>座机号码</th>
          <th>微信号码</th>
          <th>合同号</th>
          <th>备注</th>
          <th>处理状态</th>
          <th>提交时间</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['sug_id']}">
          <td>{$item['sug_id']}{*<input type="checkbox" name="id[]" group="chkVal" value="{$item['sug_id']}" class="checkitem">*}</td>
          <td>{$item['username']|escape}</td>
          <td>{$item['company_name']|escape}</td>
          <td>{$item['mobile']|escape}</td>
          <td>{$item['city']|escape}</td>
          <td>{$item['tel']|escape}</td>
          <td>{$item['weixin']|escape}</td>
          <td>{$item['doc_no']|escape}</td>
          <td>{$item['remark']|escape}</td>
          <td>{$item['status']|escape}</td>
          <td>{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td><a href="{admin_site_url('suggestion/edit')}?sug_id={$item['sug_id']}">编辑</a></td>
        </tr>
      	{/foreach}
      </tbody>
      <tfoot>
      	<tr class="tfoot">
          <td colspan="12">
          	{*<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
          	<a href="javascript:void(0);" class="btn" id="deleteBtn" data-checkbox="id[]" data-url="{admin_site_url('article/delete')}"><span>设为已处理</span></a>*}
          	{include file="common/pagination.tpl"}
           </td>
        </tr>
      </tfoot>
    </table>
  </form>
  
<script>
$(function(){
    bindDeleteEvent();
});
</script>

{include file="common/main_footer.tpl"}