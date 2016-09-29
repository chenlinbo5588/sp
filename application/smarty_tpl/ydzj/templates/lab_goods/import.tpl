{include file="common/main_header.tpl"}
	{config_load file="goods.conf"}
    {include file="./goods_common.tpl"}
    <div class="feedback">{$feedback}</div>
    <div class="fixed-empty"></div>
	<form name="categoryForm" method="post" action="{admin_site_url('goods/import')}" target="tagetframe" onsubmit="return validation(this);">
		<table class="table tb-type2">
	      <tbody>
	        <tr class="noborder">
	          <td colspan="2" class="required"><label class="validation">导入文件:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">
	              <input type="hidden" name="file_id" id="file_id" value=""/>
	              <div class="upload"><input class="txt" type="text" name="goods_file" id="goods_file" value="" /><input type="button" id="uploadButton" value="选择Excel文件" /></div>
	          </td>
	          <td class="vatop tips"><span class="warning"> 文件最大6M</span></td>
	        </tr>
	        <tr class="noborder">
	          <td colspan="2" class="required"><label class="validation">模式:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform">
	          	  <select name="import_mode">
	                <option value="覆盖模式">覆盖模式</option>
	                <option value="累加模式">累加模式</option>
	              </select>
	          </td>
	          <td class="vatop tips"><span class="tip">累加模式将EXCEL表格中库存累加到现有库存上,覆盖模将用EXCEL表中的库存值覆盖数据库中的库存值</span></td>
	        </tr>
	       </tbody>
	       <tfoot>
	        <tr>
	          <td colspan="2"><input type="submit" id="begin_import" name="submit" value="开始导入" class="msbtn btndisabled"/></td>
	        </tr>
      		</tfoot>
    	</table>
    </form>
        <div class="rounded_box" id="process_area" style="display:none;"><iframe name="tagetframe" width="100%" height="350"  frameborder="no" border="0" marginwidth="0" marginheight="0"></iframe></div>
        <div class="rounded_box">
          <div class="excel"><a  href="{resource_url('img/lab/import_templte.xls')}">Excel导入模版下载</a></div>
          <div class="tip">【Excel 数据示例如下】</div>
          <img src="{resource_url('img/lab/excel_template.png')}"/>
      </div>
      
      <div id="dialog-confirm" title="上传EXCEL文件" style="display:none;"><p><span class="ui-icon ui-icon-alert" style="float:left;"></span><span>请先上传文件,<span class="hightlight">3秒后自动关闭</span></p></div>
      {include file="common/jquery_ui.tpl"}
	  {include file="common/ke.tpl"}
		<script>
		    var editor1;
            KindEditor.ready(function(K) {
                var uploadbutton = K.uploadbutton({
                        button : K('#uploadButton')[0],
                        fieldName : 'imgFile',
                        url : '{admin_site_url('common/upload_excel/?mod=goods')}',
                        afterUpload : function(data) {
                            if (data.error === 0) {
                                 $("#file_id").val(data.id);
                                 $("#goods_file").val(data.title);
                                 
                                 $("#begin_import").removeClass("btndisabled");
                            } else {
                            	alert(data.message);
                            }
                        }
                });
                uploadbutton.fileBox.change(function(e) {
                	uploadbutton.submit();
                });

            });
            
            function validation(form){
                if(form['file_id'].value == ''){
                	$("#dialog-confirm").dialog({
					      autoOpen: false,
					      height: 80,
					      width: '20%',
					      modal: false,
					      resizable: false
                	}).dialog( "open" );
                	
                	setTimeout(function(){
                		$("#dialog-confirm").dialog( "close" );
                	},2000);
                	
                    return false;
                }
                
                $("#process_area").fadeIn();
                
                return true;
            }
            
		</script>
{include file="common/main_footer.tpl"}