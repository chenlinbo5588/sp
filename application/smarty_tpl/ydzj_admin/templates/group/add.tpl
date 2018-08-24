{include file="common/main_header_navs.tpl"}
  {if $info['id']}
  {form_open(site_url($uri_string),'id="add_form"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open(site_url($uri_string),'id="add_form"')}
  {/if}
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">组名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="name" value="{$info['name']|escape}" name="name" class="txt"></td>
          <td class="vatop tips"><label id="error_name" class="errtip"></label>{form_error('name')} 请输入组名称 </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label class="validation">状态:</label>{form_error('status')}</td>
        </tr>
        <tr class="noborder">
          <td>
          	<select name="enable">
          		<option value="1" {if 1 == $info['enable']}selected{/if}>开启</option>
          		<option value="0" {if 0 == $info['enable']}selected{/if}>关闭</option>
          	</select>
          </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label>到期日期:</label>{form_error('expire')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="expire"  value="{if $info['expire']}{date('Y-m-d',$info['expire'])}{/if}" name="expire" class="datepicker txt-short"></td>
          <td class="vatop tips"><label id="error_expire" class="errtip"></label>{form_error('expire')} 不填表示永不过期</td>
        </tr>
        <tr>
          <td colspan="2"><label for="group_data">可见数据区域:</label><label id="error_group_data" class="errtip"></label>{form_error('group_data[]')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform" colspan="2">
          	<ul class="ulListStyle1 clearfix">
          	{foreach from=$residentList item=item}
          		{if in_array($item['id'],$info['group_data'])}
          		<li class="selected"><label><input type="checkbox" name="group_data[]" checked="checked" value="{$item['id']}"/><span>{$item['name']|escape}</span></label></li>
          		{else}
          		<li><label><input type="checkbox" name="group_data[]" value="{$item['id']}"/><span>{$item['name']|escape}</span></label></li>
          		{/if}
          	{/foreach}
          	</ul>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2">
          	<input type="submit" name="submit" value="保存" class="msbtn"/>
          	{if $gobackUrl}
	    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
	    	{/if}
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
  <script>
  	$(function(){
  		$.loadingbar({ text: "正在提交..." , urls: [ new RegExp("{$uri_string}") ] , container : "#add_form" });
  		
  		$(".datepicker").datepicker();
  		
  		bindAjaxSubmit("#add_form");
  	});
  </script>
{include file="common/main_footer.tpl"}