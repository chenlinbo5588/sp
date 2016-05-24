{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>投诉建议</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('suggestion/index')}"><span>管理</span></a></li>
      	<li><a class="current"><span>{if $info['sug_id']}编辑{else}新增{/if}</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {if $info['sug_id']}
  {form_open_multipart(admin_site_url('suggestion/edit'),'id="suggestion_form"')}
  {else}
  {form_open_multipart(admin_site_url('suggestion/add'),'id="suggestion_form"')}
  {/if}
  	<input type="hidden" name="sug_id" value="{$info['sug_id']}"/>
  	<table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">用户姓名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['username']|escape}" name="username" id="username" class="txt"></td>
          <td class="vatop tips">{form_error('username')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">公司名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['company_name']|escape}" name="company_name" id="company_name" class="txt"></td>
          <td class="vatop tips">{form_error('company_name')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">手机号码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['mobile']|escape}" name="mobile" id="mobile" class="txt"></td>
          <td class="vatop tips">{form_error('mobile')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">城市:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['city']|escape}" name="city" id="city" class="txt"></td>
          <td class="vatop tips">{form_error('city')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>城市:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['tel']|escape}" name="tel" id="tel" class="txt"></td>
          <td class="vatop tips">{form_error('tel')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>邮箱地址:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['email']|escape}" name="email" id="email" class="txt"></td>
          <td class="vatop tips">{form_error('email')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>微信号码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['weixin']|escape}" name="weixin" id="weixin" class="txt"></td>
          <td class="vatop tips">{form_error('weixin')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">合同编号:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['doc_no']|escape}" name="doc_no" id="doc_no" class="txt"></td>
          <td class="vatop tips">{form_error('doc_no')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">备注:</label>{form_error('remark')}</td>
        </tr>
        <tr>
        	<td colspan="2"><textarea id="remark" name="remark" style="width:100%;height:150px;">{$info['remark']|escape}</textarea></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">状态:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="status">
        		<option value="未处理" {if $info['status'] == '未处理'}selected{/if}>未处理</option>
        		<option value="处理中" {if $info['status'] == '处理中'}selected{/if}>处理中</option>
        		<option value="已处理" {if $info['status'] == '已处理'}selected{/if}>已处理</option>
        	</select>
          </td>
          <td class="vatop tips">{form_error('status')}</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
   </form>
{include file="common/main_footer.tpl"}