{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th class="nobg" colspan="12"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div></th>
      </tr>
      <tr class="noborder">
    	<td colspan="2" >
    		<ol class="tip-yellowsimple">
    			<li>1、导出内容为{$moduleTitle}信息的Excel表格</li>
    			<li>2、由于导出的记录可能过于庞大，处于对系统的保护，一次最多导出记录上限为{config_item('excel_export_limit')}条，如您需要进行全部导出，请输入页码,请进行多次导出操作</li>
    		</ol>
    	</td>
      </tr>
    </tbody>
  </table>
  {form_open(site_url($uri_string),'id="exportForm" target="exportFrame"')}
  <table class="table tb-type2">
  	  <thead>
    		<tr class="thead" >
          		<th colspan="2">导出您的{$moduleTitle}数据?</th>
      		</tr>
      </thead>
      <tbody>
      		{include file="common/resident_radio.tpl"}
			<tr class="noborder">
	          <td colspan="2"><label for="name">{#yezhu_name#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform"><input class="txt" name="name" value="" type="text"></td>
	          <td class="vatop tips">选填</td>
	        </tr>
			<tr class="noborder">
	          <td colspan="2"><label for="name">{#mobile#}</label></th>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">
	          	<input class="txt" name="mobile" value="" type="text">
	          </td>
	          <td class="vatop tips">选填</td>
	        </tr>   
	        <tr class="noborder">
	          <td colspan="2"><label for="name">{#age#}</label></th>
	        </tr>
	        <tr class="noborder">
	          <td>
        		从&nbsp;<input type="text" value="" name="age_s" value="" class="smtxt"/>
        		-
        		到&nbsp;<input type="text" value="" name="age_e" value="" class="smtxt"/>
		        </td>
		        <td class="vatop tips">选填</td>
	        </tr>
	        {include file="common/export_pager.tpl"}
	        {include file="common/export_format.tpl"}
      	</tbody>
      	<tfoot>
      		<tr class="tfoot">
        		<td colspan="2">
        			<input type="submit" name="submit" value="导出" class="msbtn"/>
        		</td>
      		</tr>
      	</tfoot>
    </table>
  </form>
  <iframe name="exportFrame" frameborder="0" height="0" width="0"></iframe>
{include file="common/main_footer.tpl"}