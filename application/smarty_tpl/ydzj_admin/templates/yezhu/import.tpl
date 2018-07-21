{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {form_open_multipart(site_url($uri_string),'name="form1"')}
    <table class="table tb-type2">
      <tbody>
      	<tr class="noborder">
          <td colspan="2">
            <a class="excelDownload" href="{resource_url('example/yezhu.xls')}"><span>点击下载导入例子文件</span></a>
           </td>
        </tr>
        <tr class="noborder">
        	<td colspan="2" >
        		<ol class="tip-yellowsimple">
        			<li>1、最多一次处理 1000条记录，如果导入速度较慢，建议您把文件拆分为几个小文件，然后分别导入</li>
        			<li>2、如果Excel表格中房屋已经存在，则将进行忽略处理</li>
        		</ol>
        	</td
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
        <tr class="tfoot">
          <td colspan="2"><input type="submit" name="submit" value="开始导入" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
  
	{if $result}
	<div id="result" style="height:600px;overflow: auto;">
	<table class="table" style="width:50%">
		<thead>
			<tr>
				<th>{#yezhu_name#}</th>
				<th>{#id_type#}</th>
				<th>{#id_no#}</th>
				<th>{#mobile#}</th>
				<th>结果</th>
			</tr>
		<thead>
		<tbody>
	{foreach from=$result item=item}
			<tr>
				<td>{$item['name']|escape}</td>
				<td>{$item['id_type']|escape}</td>
				<td>{$item['id_no']|escape}</td>
				<td>{$item['mobile']|escape}</td>
				<td>{$item['message']|escape}</td>
			</tr>
	{/foreach}
		</tbody>
	</table>
	
	</div>
	{/if}
	
  
{include file="common/main_footer.tpl"}