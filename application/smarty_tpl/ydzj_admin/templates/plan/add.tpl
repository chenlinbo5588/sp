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
    			<li>请选择需要生成收费计划的小区</li>
    		</ol>
    	</td>
      </tr>
    </tbody>
  </table>
  {form_open(site_url($uri_string),'id="planForm"')}
  <table class="table tb-type2">
      <tbody>
      		{include file="common/resident_radio.tpl"}
      </tbody>
      	<tfoot>
      		<tr class="tfoot">
        		<td colspan="2">
        			<input type="submit" name="submit" value="生成" class="msbtn"/>
        		</td>
      		</tr>
      	</tfoot>
    </table>
  </form>
  <script type="text/javascript">
	var submitUrl = [new RegExp("{$uri_string}")];
	
	$(function(){
		$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#planForm" });
		bindAjaxSubmit("#planForm");
	});
  </script>
{include file="common/main_footer.tpl"}