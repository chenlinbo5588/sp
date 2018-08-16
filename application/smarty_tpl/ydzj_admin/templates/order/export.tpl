{include file="common/main_header_navs.tpl"}
  {config_load file="order.conf"}
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
	        <tr class="noborder">
	          <td colspan="2"><label for="order_typename">{#status#}:</label></td>
	        </tr>
  	          <tr class="noborder">
  	          <td class="vatop" colspan="2">
				<input type="checkbox" name="status[]" value="2" checked="checked" /> 已付款
				<input type="checkbox" name="status[]" value="3"  /> 退款中
				<input type="checkbox" name="status[]" value="4" checked="checked" /> 已退款
			  </td>
	         <td class="vatop tips">选填</td>
	        </tr>
  	        <tr class="noborder">
	          <td colspan="2"><label for="order_id">{#order_id#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform"><input class="txt" name="order_id" value="" type="text"></td>
	          <td class="vatop tips">选填</td>
	        </tr>
  	        <tr class="noborder">
	          <td colspan="2"><label for="order_typename">{#order_typename#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform"><input class="txt" name="order_typename" value="" type="text"></td>
	          <td class="vatop tips">选填</td>
	        </tr>  
			<tr class="noborder">
	          <td colspan="2"><label for="username">{#customer_name#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform"><input class="txt" name="username" value="" type="text"></td>
	          <td class="vatop tips">选填</td>
	        </tr>
	        <tr class="noborder">
	          <td colspan="2"><label for="mobile">{#mobile#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform"><input class="txt" name="mobile" value="" type="text"></td>
	          <td class="vatop tips">选填</td>
	        </tr>
	        <tr class="noborder">
	          <td>{#order_time#}:</td>
	          <tr>
	    	  <td>
	    		从&nbsp;<input type="text" autocomplete="off"  value="{$search['order_time_s']}" name="order_time_s" value="" class="datepicker txt-short"/>
	    		-
	    		到&nbsp;<input type="text" autocomplete="off"  value="{$search['order_time_e']}" name="order_time_e" value="" class="datepicker txt-short"/>
	          </td>
	          <td class="vatop tips">选填</td>
	          </tr>
	        </tr>
 	        <tr class="noborder">
	          <td colspan="2"><label for="name">{#old_amount#}</label></td>
	        </tr>
	        <tr class="noborder">
	          <td>
        		从&nbsp;<input type="text" value="" name="old_amount_s"  class="smtxt"/>
        		-
        		到&nbsp;<input type="text" value="" name="old_amount_e"  class="smtxt"/>
		        </td>
		        <td class="vatop tips">选填</td>
	        </tr>    
	        <tr class="noborder">
	          <td colspan="2"><label for="name">页码</label>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">
	          	<input class="txt" name="page" value="1" type="text">
	          </td>
	          <td class="vatop tips">选填 多次导出的用于接续记录</td>
	        </tr> 
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
  <script type="text/javascript" src="{resource_url('js/service/order_index.js',true)}"></script>
{include file="common/main_footer.tpl"}