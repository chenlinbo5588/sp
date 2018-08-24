{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {form_open_multipart(site_url($uri_string),'name="form1" target="importFrame"')}
    <table class="table tb-type2">
      <tbody>
      	<tr class="noborder">
          <td colspan="2">
            <a class="excelDownload" href="{resource_url('example/wuye.xls')}"><span>点击下载导入例子文件</span></a>
           </td>
        </tr>
        <tr class="noborder">
        	<td colspan="2" >
        		<ol class="tip-yellowsimple">
        			<li>1、最多一次处理{config_item('excel_import_limit')}条记录，如果导入速度较慢，建议您把文件拆分为几个小文件，然后分别导入</li>
        			<li>2、数据库中房屋的业主将会更新为Excel表格中的业主信息</li>
        		</ol>
        	</td
        </tr>
        {include file="common/resident_radio.tpl"}
        <tr class="noborder">
          <td colspan="2"><label for="name">更新缴费信息:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<ul class="ulListStyle1 clearfix">
          		<li><label><input type="radio" name="update_fee" value="是"/><span>是</span></label></li>
          		<li class="selected"><label><input type="radio" name="update_fee" checked="checked" value="否"/><span>否</span></label></li>
          	</ul>
          </td>
          <td class="vatop tips"><div class="orange">将会覆盖原缴费到期时间，请务必谨慎操作。注意：Excel表格中的物业费、能耗费缴费到期日期必须晚于已缴费日期，否则更新不会成功。</div></td>
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