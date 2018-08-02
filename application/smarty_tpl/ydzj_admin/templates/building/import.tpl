{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {form_open_multipart(site_url($uri_string),'name="form1" target="importFrame"')}
    <table class="table tb-type2">
      <tbody>
      	<tr class="noborder">
          <td colspan="2">
            <a class="excelDownload" href="{resource_url('example/building.xls')}"><span>点击下载导入例子文件</span></a>
           </td>
        </tr>
        <tr class="noborder">
        	<td colspan="2" >
        		<ol class="tip-yellowsimple">
        			<li>1、最多一次处理{config_item('excel_import_limit')}条记录，如果导入速度较慢，建议您把文件拆分为几个小文件，然后分别导入</li>
        			<li>2、如果Excel表格中{$moduleTitle}已经存在，则将进行忽略处理</li>
        		</ol>
        	</td
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation">{#resident_name#}:</label></td>
        </tr>
        <tr class="noborder">
	         <td class="vatop rowform">
	        	<select name="resident_id">
	        		<option value="">请选择待目标小区</option>
	          	{foreach from=$residentList item=item}
	          		<option value="{$item['id']}" {if $smarty.post['resident_id'] == $item['id']}selected{/if}>{$item['name']|escape}</option>
	          	{/foreach}
	          	</select>
	         </td>
          	 <td class="vatop tips">{form_error('resident_id')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation">请选择待导入的Excel文件:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="file" name="excelFile"  /></td>
          <td class="vatop tips"></td>
        </tr>
      <tbody>
      <tfoot>
        <tr class="tfoot noborder">
          <td colspan="2"><input type="submit" id="importBtn" name="submit" value="开始导入" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
  <script>
  	$(function(){
  		$("form[name=form1]").bind('submit',function(){
			$("#importBtn").addClass("disabled").val('正在导入,请稍后').attr('disabled',true);
			return true;
		});
  	});
  </script>
  <iframe name="importFrame"  frameborder="0" style="border:1px solid #d0d0d0;" height="500" width="100%"></iframe>
{include file="common/main_footer.tpl"}