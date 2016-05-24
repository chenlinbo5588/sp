{include file="common/main_header.tpl"}
{config_load file="article.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>客户留言</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>管理</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  {form_open(admin_site_url('leavemsg/index'),'id="formSearch"')}
  <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="username">手机号码</label></th>
          <td><input type="text" value="{$smarty.post['mobile']|escape}" name="mobile" id="mobile" class="txt"></td>
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
          <th>手机号码</th>
          <th>备注</th>
          <th>处理状态</th>
          <th>提交时间</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['sug_id']}">
          <td>{$item['leave_id']}{*<input type="checkbox" name="id[]" group="chkVal" value="{$item['leave_id']}" class="checkitem">*}</td>
          <td>{$item['mobile']|escape}</td>
          <td>{$item['remark']|escape}</td>
          <td>{$item['status']|escape}</td>
          <td>{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td><a href="{admin_site_url('leavemsg/edit')}?leave_id={$item['leave_id']}">编辑</a></td>
        </tr>
      	{/foreach}
      </tbody>
      <tfoot>
      	<tr class="tfoot">
          <td colspan="6">
          	{include file="common/pagination.tpl"}
           </td>
        </tr>
      </tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}